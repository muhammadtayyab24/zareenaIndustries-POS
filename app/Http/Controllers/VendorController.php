<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    /**
     * Display a listing of the vendors.
     */
    public function index()
    {
        $vendors = Vendor::where('is_deleted', false)->orderBy('created_at', 'desc')->get();
        return view('pages.vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new vendor.
     */
    public function create()
    {
        return view('pages.vendors.create');
    }

    /**
     * Store a newly created vendor in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Cash,Credit'],
            'contact' => ['nullable', 'string', 'max:255'],
            'ntn' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('vendors')->where(function ($query) {
                return $query->where('is_deleted', false);
            })],
            'status' => ['sometimes', 'integer', 'in:0,1'],
        ]);

        $vendor = Vendor::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'contact' => $validated['contact'] ?? null,
            'ntn' => $validated['ntn'] ?? null,
            'address' => $validated['address'] ?? null,
            'email' => $validated['email'] ?? null,
            'status' => $validated['status'] ?? 1, // Default to active if not provided
            'is_deleted' => false,
        ]);

        // Return JSON response for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Vendor created successfully.',
                'vendor' => $vendor
            ]);
        }

        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');
    }

    /**
     * Show the form for editing the specified vendor.
     */
    public function edit(Vendor $vendor)
    {
        if ($vendor->is_deleted) {
            abort(404);
        }
        return view('pages.vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified vendor in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        if ($vendor->is_deleted) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Cash,Credit'],
            'contact' => ['nullable', 'string', 'max:255'],
            'ntn' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('vendors')->ignore($vendor->id)->where(function ($query) {
                return $query->where('is_deleted', false);
            })],
            'status' => ['required', 'integer', 'in:0,1'],
        ]);

        $vendor->update($validated);

        return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully.');
    }

    /**
     * Remove the specified vendor from storage (soft delete).
     */
    public function destroy(Vendor $vendor)
    {
        // Soft delete - modify email to allow reuse
        if ($vendor->email) {
            $vendor->email = $vendor->email . '_deleted_' . time();
        }
        $vendor->is_deleted = true;
        $vendor->save();

        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully.');
    }

    /**
     * Toggle vendor status (active/inactive).
     */
    public function toggleStatus(Request $request, Vendor $vendor)
    {
        if ($vendor->is_deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Vendor not found'
            ], 404);
        }

        $vendor->status = $vendor->status == 1 ? 0 : 1;
        $vendor->save();

        return response()->json([
            'success' => true,
            'status' => $vendor->status,
            'message' => $vendor->status == 1 ? 'Vendor activated successfully.' : 'Vendor deactivated successfully.'
        ]);
    }
}
