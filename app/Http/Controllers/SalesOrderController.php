<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\SalesProduct;
use App\Models\WarehouseStock;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of non-tax sales orders.
     */
    public function indexNonTax()
    {
        $sales = Sales::where('type', 'non_tax')
            ->with(['customer', 'warehouse', 'orderTaker', 'salesman'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('pages.sales-orders.non-tax.index', compact('sales'));
    }

    /**
     * Display a listing of tax sales orders.
     */
    public function indexTax()
    {
        $sales = Sales::where('type', 'tax')
            ->with(['customer', 'warehouse', 'orderTaker', 'salesman'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('pages.sales-orders.tax.index', compact('sales'));
    }

    /**
     * Get next invoice number
     */
    private function getNextInvoiceNumber()
    {
        $lastSale = Sales::orderBy('id', 'desc')->first();
        $nextId = $lastSale ? $lastSale->id + 1 : 1;
        return 'INV-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get customer ledger balance
     */
    public function getCustomerLedger(Request $request)
    {
        $customerId = $request->customer_id;
        
        if (!$customerId) {
            return response()->json([
                'success' => false,
                'message' => 'Customer ID is required'
            ], 400);
        }

        $customer = Customer::find($customerId);
        
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        // Calculate total sales (all sales for this customer)
        $totalSales = Sales::where('customer_id', $customerId)
            ->sum('grand_total');

        // For credit customers, calculate outstanding balance
        // This is a simplified version - in a real system, you'd track payments separately
        $balance = 0;
        
        if ($customer->type === 'Credit') {
            // Get all unpaid sales (sales where due_date is in the past or today, or null)
            // For now, we'll consider all sales as unpaid (you can add payment tracking later)
            $unpaidSales = Sales::where('customer_id', $customerId)
                ->where(function($query) {
                    $query->where('due_date', '<=', now())
                          ->orWhereNull('due_date');
                })
                ->sum('grand_total');
            
            $balance = $unpaidSales;
        }

        return response()->json([
            'success' => true,
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'type' => $customer->type,
            ],
            'total_sales' => number_format($totalSales, 2),
            'balance' => number_format($balance, 2),
            'balance_raw' => $balance
        ]);
    }

    /**
     * Show the form for creating a new non-tax sales order.
     */
    public function createNonTax()
    {
        $customers = Customer::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();
        $products = Product::where('is_deleted', false)->where('status', 1)->orderBy('product_name')->get();
        $users = User::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();
        $nextInvoiceNo = $this->getNextInvoiceNumber();
        
        return view('pages.sales-orders.non-tax.create', compact('customers', 'warehouses', 'products', 'users', 'nextInvoiceNo'));
    }

    /**
     * Show the form for creating a new tax sales order.
     */
    public function createTax()
    {
        $customers = Customer::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();
        $products = Product::where('is_deleted', false)->where('status', 1)->orderBy('product_name')->get();
        $users = User::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();
        $nextInvoiceNo = $this->getNextInvoiceNumber();
        
        return view('pages.sales-orders.tax.create', compact('customers', 'warehouses', 'products', 'users', 'nextInvoiceNo'));
    }

    /**
     * Store a newly created sales order.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:tax,non_tax',
            'customer_id' => 'required|exists:customers,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'order_taker_id' => 'nullable|exists:users,id',
            'salesman_id' => 'nullable|exists:users,id',
            'invoice_no' => 'nullable|string|max:255',
            'po_no' => 'nullable|string|max:255',
            'dc_no' => 'nullable|string|max:255',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'freight_charges' => 'nullable|numeric|min:0',
            'adv_inc_tax_percentage' => 'nullable|numeric|min:0|max:100',
            'adv_inc_tax_amount' => 'nullable|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.qty' => 'required|numeric|min:0.01',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.gst_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $request->all();
            $type = $data['type'];
            
            // Calculate totals
            $subtotal = 0;
            $totalGst = 0;
            
            foreach ($data['products'] as $productData) {
                $qty = floatval($productData['qty']);
                $price = floatval($productData['price']);
                $netAmount = $qty * $price;
                
                if ($type === 'tax' && isset($productData['gst_percentage'])) {
                    $gstPercentage = floatval($productData['gst_percentage']);
                    $gstAmount = ($netAmount * $gstPercentage) / 100;
                    $totalGst += $gstAmount;
                    $subtotal += $netAmount; // For tax, subtotal is net amount (before GST)
                } else {
                    // For non-tax, subtotal is the total amount (qty * price)
                    $subtotal += $netAmount;
                }
            }
            
            $freightCharges = floatval($data['freight_charges'] ?? 0);
            $advIncTaxPercentage = floatval($data['adv_inc_tax_percentage'] ?? 0);
            $advIncTaxAmount = floatval($data['adv_inc_tax_amount'] ?? 0);
            
            // Calculate grand total based on type
            if ($type === 'tax') {
                // For tax: subtotal + totalGst + advIncTaxAmount + freightCharges
                $grandTotal = $subtotal + $totalGst + $advIncTaxAmount + $freightCharges;
            } else {
                // For non-tax: subtotal + freightCharges
                $grandTotal = $subtotal + $freightCharges;
            }
            
            // Auto-generate invoice number if not provided
            $invoiceNo = $data['invoice_no'] ?? $this->getNextInvoiceNumber();
            
            $user = Auth::user();
            $companyId = $user->company_id;

            // Create sale
            $sale = Sales::create([
                'type' => $type,
                'customer_id' => $data['customer_id'],
                'warehouse_id' => $data['warehouse_id'] ?? null,
                'order_taker_id' => $data['order_taker_id'] ?? null,
                'salesman_id' => $data['salesman_id'] ?? null,
                'invoice_no' => $invoiceNo,
                'po_no' => $data['po_no'] ?? null,
                'dc_no' => $data['dc_no'] ?? null,
                'invoice_date' => $data['invoice_date'],
                'due_date' => $data['due_date'] ?? null,
                'freight_charges' => $freightCharges,
                'adv_inc_tax_percentage' => $advIncTaxPercentage,
                'adv_inc_tax_amount' => $advIncTaxAmount,
                'subtotal' => $subtotal,
                'total_gst' => $totalGst,
                'grand_total' => $grandTotal,
                'company_id' => $companyId,
            ]);
            
            // Create sales products and reduce warehouse stock
            foreach ($data['products'] as $productData) {
                $product = Product::find($productData['product_id']);
                $qty = floatval($productData['qty']);
                $price = floatval($productData['price']);
                
                // Check if enough stock is available
                if ($product->current_qty < $qty) {
                    throw new \Exception("Insufficient stock for product: {$product->product_name}. Available: {$product->current_qty}, Required: {$qty}");
                }
                
                if ($type === 'tax' && isset($productData['gst_percentage'])) {
                    $netAmount = $qty * $price;
                    $gstPercentage = floatval($productData['gst_percentage']);
                    $gstAmount = ($netAmount * $gstPercentage) / 100;
                    $totalAmount = $netAmount + $gstAmount;
                } else {
                    // Non-tax: total_amount = qty * price
                    $netAmount = $qty * $price;
                    $gstPercentage = null;
                    $gstAmount = 0;
                    $totalAmount = $netAmount;
                }
                
                SalesProduct::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productData['product_id'],
                    'unit_type' => $product->unit_type,
                    'qty' => $qty,
                    'price' => $price,
                    'gst_percentage' => $gstPercentage,
                    'net_amount' => $netAmount,
                    'gst_amount' => $gstAmount,
                    'total_amount' => $totalAmount,
                    'company_id' => $companyId,
                ]);
                
                // Reduce product current_qty (Stock Management)
                $product->current_qty -= $qty;
                $product->save();
                
                // Reduce warehouse stock if warehouse is selected
                if ($sale->warehouse_id) {
                    $warehouseStock = WarehouseStock::firstOrCreate(
                        [
                            'warehouse_id' => $sale->warehouse_id,
                            'product_id' => $productData['product_id'],
                            'company_id' => $companyId,
                        ],
                        ['qty' => 0, 'company_id' => $companyId]
                    );
                    
                    if ($warehouseStock->qty < $qty) {
                        throw new \Exception("Insufficient warehouse stock for product: {$product->product_name}. Available: {$warehouseStock->qty}, Required: {$qty}");
                    }
                    
                    $warehouseStock->qty -= $qty;
                    $warehouseStock->save();
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Sales order created successfully',
                'data' => $sale->load(['customer', 'warehouse', 'orderTaker', 'salesman', 'products.product'])
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating sales order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product unit type by product ID (AJAX)
     */
    public function getProductUnitType(Request $request)
    {
        $product = Product::find($request->product_id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'unit_type' => $product->unit_type
        ]);
    }

    /**
     * Display the specified sales order.
     */
    public function show($id)
    {
        $sale = Sales::with(['customer', 'warehouse', 'orderTaker', 'salesman', 'products.product', 'company'])->findOrFail($id);
        
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $sale->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access');
        }
        
        if ($sale->type === 'tax') {
            return view('pages.sales-orders.tax.show', compact('sale'));
        } else {
            return view('pages.sales-orders.non-tax.show', compact('sale'));
        }
    }

    /**
     * Print invoice for the specified sales order.
     */
    public function print($id)
    {
        $sale = Sales::with(['customer', 'warehouse', 'orderTaker', 'salesman', 'products.product', 'company'])->findOrFail($id);
        $user = Auth::user();
        
        if (!$user->isSuperAdmin() && $sale->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access');
        }
        
        $printedBy = $user;
        
        return view('pages.sales-orders.print', compact('sale', 'printedBy'));
    }
}
