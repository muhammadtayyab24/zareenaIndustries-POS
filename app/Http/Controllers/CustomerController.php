<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index()
    {
        $customers = Customer::where('is_deleted', false)->orderBy('created_at', 'desc')->get();
        return view('pages.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('pages.customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $companyId = $user->company_id;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Cash,Credit'],
            'contact' => ['nullable', 'string', 'max:255'],
            'ntn' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('customers')->where(function ($query) use ($companyId) {
                return $query->where('company_id', $companyId)->where('is_deleted', false);
            })],
            'status' => ['sometimes', 'integer', 'in:0,1'],
        ]);

        Customer::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'contact' => $validated['contact'] ?? null,
            'ntn' => $validated['ntn'] ?? null,
            'address' => $validated['address'] ?? null,
            'email' => $validated['email'] ?? null,
            'status' => $validated['status'] ?? 1, // Default to active if not provided
            'is_deleted' => false,
            'company_id' => $companyId,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $customer->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access');
        }

        if ($customer->is_deleted) {
            abort(404);
        }
        return view('pages.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        if ($customer->is_deleted) {
            abort(404);
        }

        $user = Auth::user();
        $companyId = $user->company_id;

        // Check if user can access this customer
        if (!$user->isSuperAdmin() && $customer->company_id !== $companyId) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Cash,Credit'],
            'contact' => ['nullable', 'string', 'max:255'],
            'ntn' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('customers')->ignore($customer->id)->where(function ($query) use ($companyId) {
                return $query->where('company_id', $companyId)->where('is_deleted', false);
            })],
            'status' => ['required', 'integer', 'in:0,1'],
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage (soft delete).
     */
    public function destroy(Customer $customer)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $customer->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access');
        }

        // Soft delete - modify email to allow reuse
        if ($customer->email) {
            $customer->email = $customer->email . '_deleted_' . time();
        }
        $customer->is_deleted = true;
        $customer->save();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    /**
     * Toggle customer status (active/inactive).
     */
    public function toggleStatus(Request $request, Customer $customer)
    {
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $customer->company_id !== $user->company_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        if ($customer->is_deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        $customer->status = $customer->status == 1 ? 0 : 1;
        $customer->save();

        return response()->json([
            'success' => true,
            'status' => $customer->status,
            'message' => $customer->status == 1 ? 'Customer activated successfully.' : 'Customer deactivated successfully.'
        ]);
    }
}
