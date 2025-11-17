<?php

namespace App\Http\Controllers;

use App\Models\EmployeeAttendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EmployeeAttendanceController extends Controller
{
    /**
     * Display a listing of attendances.
     */
    public function index(Request $request)
    {
        $query = EmployeeAttendance::with('employee');

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

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')->get();
        $employees = Employee::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();

        // Return JSON for API requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $attendances
            ]);
        }

        // Return view for web requests
        return view('pages.employees.attendances.index', compact('attendances', 'employees'));
    }

    /**
     * Show the form for creating a new attendance.
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

        return view('pages.employees.attendances.create', compact('employees'));
    }

    /**
     * Store a newly created attendance.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,half_day',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if attendance already exists for this employee on this date
        $existing = EmployeeAttendance::where('employee_id', $request->employee_id)
            ->where('date', $request->date)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance already exists for this employee on this date. Use update instead.'
            ], 409);
        }

        $attendance = EmployeeAttendance::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Attendance created successfully',
            'data' => $attendance->load('employee')
        ], 201);
    }

    /**
     * Display the specified attendance.
     */
    public function show($id)
    {
        $attendance = EmployeeAttendance::with('employee')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $attendance
        ]);
    }

    /**
     * Update the specified attendance.
     */
    public function update(Request $request, $id)
    {
        $attendance = EmployeeAttendance::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'date' => 'sometimes|date',
            'status' => 'sometimes|in:present,absent,half_day',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // If date is being updated, check for duplicate
        if ($request->has('date') && $request->date != $attendance->date) {
            $existing = EmployeeAttendance::where('employee_id', $attendance->employee_id)
                ->where('date', $request->date)
                ->where('id', '!=', $id)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attendance already exists for this employee on this date.'
                ], 409);
            }
        }

        $attendance->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Attendance updated successfully',
            'data' => $attendance->load('employee')
        ]);
    }

    /**
     * Remove the specified attendance.
     */
    public function destroy($id)
    {
        $attendance = EmployeeAttendance::findOrFail($id);
        $attendance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attendance deleted successfully'
        ]);
    }
}
