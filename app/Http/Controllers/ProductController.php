<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::where('is_deleted', false)
            ->with(['category', 'type'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('pages.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = ProductCategory::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();
        $types = ProductType::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();
        return view('pages.products.create', compact('categories', 'types'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $companyId = $user->company_id;

        $validated = $request->validate([
            'cat_id' => ['required', 'exists:product_categories,id'],
            'type_id' => ['required', 'exists:product_types,id'],
            'product_name' => ['required', 'string', 'max:255'],
            'unit_type' => ['nullable', 'string', 'max:255'],
            'opening_qty' => ['nullable', 'numeric', 'min:0'],
            'current_qty' => ['nullable', 'numeric', 'min:0'],
            'status' => ['sometimes', 'integer', 'in:0,1'],
        ]);

        Product::create([
            'cat_id' => $validated['cat_id'],
            'type_id' => $validated['type_id'],
            'product_name' => $validated['product_name'],
            'unit_type' => $validated['unit_type'] ?? null,
            'opening_qty' => $validated['opening_qty'] ?? 0,
            'current_qty' => $validated['current_qty'] ?? $validated['opening_qty'] ?? 0,
            'status' => $validated['status'] ?? 1, // Default to active if not provided
            'is_deleted' => false,
            'company_id' => $companyId,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $product->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access');
        }

        if ($product->is_deleted) {
            abort(404);
        }
        $categories = ProductCategory::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();
        $types = ProductType::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();
        return view('pages.products.edit', compact('product', 'categories', 'types'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $product->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access');
        }

        if ($product->is_deleted) {
            abort(404);
        }

        $validated = $request->validate([
            'cat_id' => ['required', 'exists:product_categories,id'],
            'type_id' => ['required', 'exists:product_types,id'],
            'product_name' => ['required', 'string', 'max:255'],
            'unit_type' => ['nullable', 'string', 'max:255'],
            'opening_qty' => ['nullable', 'numeric', 'min:0'],
            'current_qty' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'integer', 'in:0,1'],
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage (soft delete).
     */
    public function destroy(Product $product)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $product->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access');
        }

        // Soft delete - modify product name to allow reuse
        $product->product_name = $product->product_name . '_deleted_' . time();
        $product->is_deleted = true;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * Toggle product status (active/inactive).
     */
    public function toggleStatus(Request $request, Product $product)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $product->company_id !== $user->company_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        if ($product->is_deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $product->status = $product->status == 1 ? 0 : 1;
        $product->save();

        return response()->json([
            'success' => true,
            'status' => $product->status,
            'message' => $product->status == 1 ? 'Product activated successfully.' : 'Product deactivated successfully.'
        ]);
    }
}
