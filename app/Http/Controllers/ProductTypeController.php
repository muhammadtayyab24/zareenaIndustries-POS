<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the product types.
     */
    public function index()
    {
        $types = ProductType::where('is_deleted', false)->orderBy('created_at', 'desc')->get();
        return view('pages.product-types.index', compact('types'));
    }

    /**
     * Show the form for creating a new product type.
     */
    public function create()
    {
        return view('pages.product-types.create');
    }

    /**
     * Store a newly created product type in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'status' => ['sometimes', 'integer', 'in:0,1'],
            ]);

            ProductType::create([
                'name' => $validated['name'],
                'status' => $validated['status'] ?? 1, // Default to active if not provided
                'is_deleted' => false,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product Type created successfully.'
                ]);
            }

            return redirect()->route('product-types.index')->with('success', 'Product Type created successfully.');
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
     * Show the form for editing the specified product type.
     */
    public function edit(ProductType $productType)
    {
        if ($productType->is_deleted) {
            abort(404);
        }
        return view('pages.product-types.edit', compact('productType'));
    }

    /**
     * Update the specified product type in storage.
     */
    public function update(Request $request, ProductType $productType)
    {
        if ($productType->is_deleted) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product Type not found'
                ], 404);
            }
            abort(404);
        }

        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'status' => ['required', 'integer', 'in:0,1'],
            ]);

            $productType->update($validated);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product Type updated successfully.'
                ]);
            }

            return redirect()->route('product-types.index')->with('success', 'Product Type updated successfully.');
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
     * Remove the specified product type from storage (soft delete).
     */
    public function destroy(ProductType $productType)
    {
        // Soft delete - modify name to allow reuse
        $productType->name = $productType->name . '_deleted_' . time();
        $productType->is_deleted = true;
        $productType->save();

        return redirect()->route('product-types.index')->with('success', 'Product Type deleted successfully.');
    }

    /**
     * Toggle product type status (active/inactive).
     */
    public function toggleStatus(Request $request, ProductType $productType)
    {
        if ($productType->is_deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Product Type not found'
            ], 404);
        }

        $productType->status = $productType->status == 1 ? 0 : 1;
        $productType->save();

        return response()->json([
            'success' => true,
            'status' => $productType->status,
            'message' => $productType->status == 1 ? 'Product Type activated successfully.' : 'Product Type deactivated successfully.'
        ]);
    }
}
