<?php

namespace App\Http\Controllers;

use App\Models\EmployeeOvertime;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeOvertimeController extends Controller
{
    /**
     * Display a listing of overtime hours.
     */
    public function index(Request $request)
    {
        $query = EmployeeOvertime::with('employee');

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

        $overtimes = $query->orderBy('date', 'desc')->orderBy('created_at', 'desc')->get();
        $employees = Employee::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();

        // Return JSON for API requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $overtimes
            ]);
        }

        // Return view for web requests
        return view('pages.employees.overtimes.index', compact('overtimes', 'employees'));
    }

    /**
     * Show the form for creating a new overtime entry.
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

        return view('pages.employees.overtimes.create', compact('employees'));
    }

    /**
     * Store a newly created overtime entry.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'ot_hours' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if overtime already exists for this employee on this date
        $existing = EmployeeOvertime::where('employee_id', $request->employee_id)
            ->where('date', $request->date)
            ->first();

        if ($existing) {
            // Update existing overtime
            $existing->update(['ot_hours' => $request->ot_hours]);
            $overtime = $existing->fresh();
        } else {
            // Create new overtime
            $overtime = EmployeeOvertime::create($request->all());
        }

        return response()->json([
            'success' => true,
            'message' => 'Overtime created successfully',
            'data' => $overtime->load('employee')
        ], 201);
    }

    /**
     * Display the specified overtime entry.
     */
    public function show($id)
    {
        $overtime = EmployeeOvertime::with('employee')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $overtime
        ]);
    }

    /**
     * Update the specified overtime entry.
     */
    public function update(Request $request, $id)
    {
        $overtime = EmployeeOvertime::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'date' => 'sometimes|date',
            'ot_hours' => 'sometimes|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // If date is being updated, check for duplicate
        if ($request->has('date') && $request->date != $overtime->date) {
            $existing = EmployeeOvertime::where('employee_id', $overtime->employee_id)
                ->where('date', $request->date)
                ->where('id', '!=', $id)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Overtime already exists for this employee on this date.'
                ], 409);
            }
        }

        $overtime->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Overtime updated successfully',
            'data' => $overtime->load('employee')
        ]);
    }

    /**
     * Remove the specified overtime entry.
     */
    public function destroy($id)
    {
        $overtime = EmployeeOvertime::findOrFail($id);
        $overtime->delete();

        return response()->json([
            'success' => true,
            'message' => 'Overtime deleted successfully'
        ]);
    }
}
