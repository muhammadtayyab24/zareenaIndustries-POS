<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the product categories.
     */
    public function index()
    {
        $categories = ProductCategory::where('is_deleted', false)->orderBy('created_at', 'desc')->get();
        return view('pages.product-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new product category.
     */
    public function create()
    {
        return view('pages.product-categories.create');
    }

    /**
     * Store a newly created product category in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'status' => ['sometimes', 'integer', 'in:0,1'],
            ]);

            ProductCategory::create([
                'name' => $validated['name'],
                'status' => $validated['status'] ?? 1, // Default to active if not provided
                'is_deleted' => false,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product Category created successfully.'
                ]);
            }

            return redirect()->route('product-categories.index')->with('success', 'Product Category created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
    }

    /**
     * Show the form for editing the specified product category.
     */
    public function edit(ProductCategory $productCategory)
    {
        if ($productCategory->is_deleted) {
            abort(404);
        }
        return view('pages.product-categories.edit', compact('productCategory'));
    }

    /**
     * Update the specified product category in storage.
     */
    public function update(Request $request, ProductCategory $productCategory)
    {
        if ($productCategory->is_deleted) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product Category not found'
                ], 404);
            }
            abort(404);
        }

        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'status' => ['required', 'integer', 'in:0,1'],
            ]);

            $productCategory->update($validated);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product Category updated successfully.'
                ]);
            }

            return redirect()->route('product-categories.index')->with('success', 'Product Category updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
    }

    /**
     * Remove the specified product category from storage (soft delete).
     */
    public function destroy(ProductCategory $productCategory)
    {
        // Soft delete - modify name to allow reuse
        $productCategory->name = $productCategory->name . '_deleted_' . time();
        $productCategory->is_deleted = true;
        $productCategory->save();

        return redirect()->route('product-categories.index')->with('success', 'Product Category deleted successfully.');
    }

    /**
     * Toggle product category status (active/inactive).
     */
    public function toggleStatus(Request $request, ProductCategory $productCategory)
    {
        if ($productCategory->is_deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Product Category not found'
            ], 404);
        }

        $productCategory->status = $productCategory->status == 1 ? 0 : 1;
        $productCategory->save();

        return response()->json([
            'success' => true,
            'status' => $productCategory->status,
            'message' => $productCategory->status == 1 ? 'Product Category activated successfully.' : 'Product Category deactivated successfully.'
        ]);
    }
}
