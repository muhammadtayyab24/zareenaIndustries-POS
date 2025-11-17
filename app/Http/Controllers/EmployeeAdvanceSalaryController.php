<?php

namespace App\Http\Controllers;

use App\Models\EmployeeAdvanceSalary;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeAdvanceSalaryController extends Controller
{
    /**
     * Display a listing of advance salaries.
     */
    public function index(Request $request)
    {
        $query = EmployeeAdvanceSalary::with('employee')
            ->where('is_deleted', false);

        // Filter by employee_id
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        $advances = $query->orderBy('date', 'desc')->get();
        $employees = Employee::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();

        // Return JSON for API requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $advances
            ]);
        }

        // Return view for web requests
        return view('pages.employees.advance-salaries.index', compact('advances', 'employees'));
    }

    /**
     * Show the form for creating a new advance salary.
     */
    public function create(Request $request)
    {
        $employees = Employee::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();
        
        // Return JSON for API requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => ['employees' => $employees]
            ]);
        }

        return view('pages.employees.advance-salaries.create', compact('employees'));
    }

    /**
     * Store a newly created advance salary.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $advance = EmployeeAdvanceSalary::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Advance salary created successfully',
            'data' => $advance->load('employee')
        ], 201);
    }

    /**
     * Display the specified advance salary.
     */
    public function show($id)
    {
        $advance = EmployeeAdvanceSalary::with('employee')
            ->where('is_deleted', false)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $advance
        ]);
    }

    /**
     * Update the specified advance salary.
     */
    public function update(Request $request, $id)
    {
        $advance = EmployeeAdvanceSalary::where('is_deleted', false)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'date' => 'sometimes|date',
            'amount' => 'sometimes|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $advance->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Advance salary updated successfully',
            'data' => $advance->load('employee')
        ]);
    }

    /**
     * Remove the specified advance salary (soft delete).
     */
    public function destroy($id)
    {
        $advance = EmployeeAdvanceSalary::findOrFail($id);
        $advance->is_deleted = true;
        $advance->save();

        return response()->json([
            'success' => true,
            'message' => 'Advance salary deleted successfully'
        ]);
    }
}
