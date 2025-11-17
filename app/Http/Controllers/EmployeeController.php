<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     */
    public function index()
    {
        $employees = Employee::where('is_deleted', false)->orderBy('created_at', 'desc')->get();
        return view('pages.employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        return view('pages.employees.create');
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'monthly_salary' => ['nullable', 'numeric', 'min:0'],
            'ot_rate_per_hour' => ['nullable', 'numeric', 'min:0'],
            'status' => ['sometimes', 'integer', 'in:0,1'],
        ]);

        Employee::create([
            'name' => $validated['name'],
            'contact' => $validated['contact'] ?? null,
            'designation' => $validated['designation'] ?? null,
            'monthly_salary' => $validated['monthly_salary'] ?? null,
            'ot_rate_per_hour' => $validated['ot_rate_per_hour'] ?? null,
            'status' => $validated['status'] ?? 1, // Default to active if not provided
            'is_deleted' => false,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        if ($employee->is_deleted) {
            abort(404);
        }
        return view('pages.employees.edit', compact('employee'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        if ($employee->is_deleted) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'monthly_salary' => ['nullable', 'numeric', 'min:0'],
            'ot_rate_per_hour' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'integer', 'in:0,1'],
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified employee from storage (soft delete).
     */
    public function destroy(Employee $employee)
    {
        // Soft delete - modify name to allow reuse
        $employee->name = $employee->name . '_deleted_' . time();
        $employee->is_deleted = true;
        $employee->save();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    /**
     * Toggle employee status (active/inactive).
     */
    public function toggleStatus(Request $request, Employee $employee)
    {
        if ($employee->is_deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        $employee->status = $employee->status == 1 ? 0 : 1;
        $employee->save();

        return response()->json([
            'success' => true,
            'status' => $employee->status,
            'message' => $employee->status == 1 ? 'Employee activated successfully.' : 'Employee deactivated successfully.'
        ]);
    }
}
