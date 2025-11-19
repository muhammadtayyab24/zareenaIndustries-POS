<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the warehouses.
     */
    public function index()
    {
        $warehouses = Warehouse::where('is_deleted', false)->orderBy('created_at', 'desc')->paginate(10);
        return view('pages.warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new warehouse.
     */
    public function create()
    {
        return view('pages.warehouses.create');
    }

    /**
     * Store a newly created warehouse in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:255', Rule::unique('warehouses')->where(function ($query) {
                return $query->where('is_deleted', false);
            })],
            'contact' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('warehouses')->where(function ($query) {
                return $query->where('is_deleted', false);
            })],
            'status' => ['sometimes', 'integer', 'in:0,1'],
        ]);

        Warehouse::create([
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'contact' => $validated['contact'] ?? null,
            'address' => $validated['address'] ?? null,
            'email' => $validated['email'] ?? null,
            'status' => $validated['status'] ?? 1, // Default to active if not provided
            'is_deleted' => false,
        ]);

        return redirect()->route('warehouses.index')->with('success', 'Warehouse created successfully.');
    }

    /**
     * Display the specified warehouse.
     */
    public function show(Warehouse $warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified warehouse.
     */
    public function edit(Warehouse $warehouse)
    {
        if ($warehouse->is_deleted) {
            return redirect()->route('warehouses.index')->with('error', 'Warehouse not found.');
        }
        return view('pages.warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified warehouse in storage.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        if ($warehouse->is_deleted) {
            return redirect()->route('warehouses.index')->with('error', 'Warehouse not found.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:255', Rule::unique('warehouses')->where(function ($query) {
                return $query->where('is_deleted', false);
            })->ignore($warehouse->id)],
            'contact' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('warehouses')->where(function ($query) {
                return $query->where('is_deleted', false);
            })->ignore($warehouse->id)],
            'status' => ['required', 'integer', 'in:0,1'],
        ]);

        $warehouse->update($validated);

        return redirect()->route('warehouses.index')->with('success', 'Warehouse updated successfully.');
    }

    /**
     * Remove the specified warehouse from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        if ($warehouse->is_deleted) {
            return redirect()->route('warehouses.index')->with('error', 'Warehouse not found.');
        }

        // Soft delete: mark as deleted and modify name/email
        $warehouse->is_deleted = true;
        $warehouse->name = $warehouse->name . '_deleted_' . time();
        if ($warehouse->email) {
            $warehouse->email = $warehouse->email . '_deleted_' . time();
        }
        $warehouse->save();

        return redirect()->route('warehouses.index')->with('success', 'Warehouse deleted successfully.');
    }

    /**
     * Toggle warehouse status.
     */
    public function toggleStatus(Request $request, Warehouse $warehouse)
    {
        if ($warehouse->is_deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Warehouse not found.'
            ], 404);
        }

        $warehouse->status = $request->status;
        $warehouse->save();

        return response()->json([
            'success' => true,
            'message' => 'Warehouse status updated successfully.',
            'status' => $warehouse->status
        ]);
    }
}

