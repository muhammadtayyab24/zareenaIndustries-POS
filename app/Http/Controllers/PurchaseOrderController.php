<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\WarehouseStock;
use App\Models\Vendor;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of non-tax purchase orders.
     */
    public function indexNonTax()
    {
        $purchases = Purchase::where('type', 'non_tax')
            ->with(['vendor', 'warehouse'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('pages.purchase-orders.non-tax.index', compact('purchases'));
    }

    /**
     * Display a listing of tax purchase orders.
     */
    public function indexTax()
    {
        $purchases = Purchase::where('type', 'tax')
            ->with(['vendor', 'warehouse'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('pages.purchase-orders.tax.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new non-tax purchase order.
     */
    public function createNonTax()
    {
        $vendors = Vendor::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();
        $products = Product::where('is_deleted', false)->where('status', 1)->orderBy('product_name')->get();
        
        return view('pages.purchase-orders.non-tax.create', compact('vendors', 'warehouses', 'products'));
    }

    /**
     * Show the form for creating a new tax purchase order.
     */
    public function createTax()
    {
        $vendors = Vendor::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();
        $products = Product::where('is_deleted', false)->where('status', 1)->orderBy('product_name')->get();
        
        return view('pages.purchase-orders.tax.create', compact('vendors', 'warehouses', 'products'));
    }

    /**
     * Store a newly created purchase order.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:tax,non_tax',
            'vendor_id' => 'required|exists:vendors,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'vendor_invoice_no' => 'required|string|max:255',
            'po_no' => 'nullable|string|max:255',
            'grn_no' => 'nullable|string|max:255',
            'credit_term' => 'nullable|string|max:255',
            'invoice_date' => 'required|date',
            'labour_charges' => 'nullable|numeric|min:0',
            'freight_charges' => 'nullable|numeric|min:0',
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
            
            $labourCharges = floatval($data['labour_charges'] ?? 0);
            $freightCharges = floatval($data['freight_charges'] ?? 0);
            $grandTotal = $subtotal + $totalGst + $labourCharges + $freightCharges;
            
            // Create purchase
            $purchase = Purchase::create([
                'type' => $type,
                'vendor_id' => $data['vendor_id'],
                'warehouse_id' => $data['warehouse_id'] ?? null,
                'vendor_invoice_no' => $data['vendor_invoice_no'],
                'po_no' => $data['po_no'] ?? null,
                'grn_no' => $data['grn_no'] ?? null,
                'credit_term' => $data['credit_term'] ?? null,
                'due_date' => $data['invoice_date'] ?? null, // Using due_date field to store invoice_date
                'labour_charges' => $labourCharges,
                'freight_charges' => $freightCharges,
                'subtotal' => $subtotal,
                'total_gst' => $totalGst,
                'grand_total' => $grandTotal,
            ]);
            
            // Create purchase products and update warehouse stock
            foreach ($data['products'] as $productData) {
                $product = Product::find($productData['product_id']);
                $qty = floatval($productData['qty']);
                $price = floatval($productData['price']);
                
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
                
                PurchaseProduct::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $productData['product_id'],
                    'unit_type' => $product->unit_type,
                    'qty' => $qty,
                    'price' => $price,
                    'gst_percentage' => $gstPercentage,
                    'net_amount' => $netAmount,
                    'gst_amount' => $gstAmount,
                    'total_amount' => $totalAmount,
                ]);
                
                // Update product current_qty
                $product->current_qty += $qty;
                $product->save();
                
                // Update warehouse stock if warehouse is selected
                if ($purchase->warehouse_id) {
                    $warehouseStock = WarehouseStock::firstOrCreate(
                        [
                            'warehouse_id' => $purchase->warehouse_id,
                            'product_id' => $productData['product_id'],
                        ],
                        ['qty' => 0]
                    );
                    $warehouseStock->qty += $qty;
                    $warehouseStock->save();
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Purchase order created successfully',
                'data' => $purchase->load(['vendor', 'warehouse', 'products.product'])
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating purchase order: ' . $e->getMessage()
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
     * Display the specified purchase order.
     */
    public function show($id)
    {
        $purchase = Purchase::with(['vendor', 'warehouse', 'products.product'])->findOrFail($id);
        
        if ($purchase->type === 'tax') {
            return view('pages.purchase-orders.tax.show', compact('purchase'));
        } else {
            return view('pages.purchase-orders.non-tax.show', compact('purchase'));
        }
    }

    /**
     * Print invoice for the specified purchase order.
     */
    public function print($id)
    {
        $purchase = Purchase::with(['vendor', 'warehouse', 'products.product'])->findOrFail($id);
        
        return view('pages.purchase-orders.print', compact('purchase'));
    }
}

