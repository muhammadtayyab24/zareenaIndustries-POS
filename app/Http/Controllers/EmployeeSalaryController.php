<?php

namespace App\Http\Controllers;

use App\Models\EmployeeSalary;
use App\Models\Employee;
use App\Services\SalaryCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeSalaryController extends Controller
{
    protected $salaryCalculationService;

    public function __construct(SalaryCalculationService $salaryCalculationService)
    {
        $this->salaryCalculationService = $salaryCalculationService;
    }

    /**
     * Display a listing of salaries.
     */
    public function index(Request $request)
    {
        $query = EmployeeSalary::with('employee');

        // Filter by employee_id
        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter by month
        if ($request->has('month')) {
            $query->where('month', $request->month);
        }

        // Filter by date range (based on month)
        if ($request->has('start_month')) {
            $query->where('month', '>=', $request->start_month);
        }
        if ($request->has('end_month')) {
            $query->where('month', '<=', $request->end_month);
        }

        $salaries = $query->orderBy('month', 'desc')->get();
        $employees = Employee::where('is_deleted', false)->where('status', 1)->orderBy('name')->get();

        // Return JSON for API requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $salaries
            ]);
        }

        // Return view for web requests
        return view('pages.employees.salaries.index', compact('salaries', 'employees'));
    }

    /**
     * Show the form for creating a new salary calculation.
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

        return view('pages.employees.salaries.create', compact('employees'));
    }

    /**
     * Calculate and create salary for an employee for a specific month.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|date_format:Y-m',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $salary = $this->salaryCalculationService->calculateSalary(
                $request->employee_id,
                $request->month
            );

            return response()->json([
                'success' => true,
                'message' => 'Salary calculated and saved successfully',
                'data' => $salary->load('employee')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified salary with breakdown.
     */
    public function show($id)
    {
        $salary = EmployeeSalary::with('employee')->findOrFail($id);
        
        $breakdown = $this->salaryCalculationService->getSalaryBreakdown(
            $salary->employee_id,
            $salary->month
        );

        return response()->json([
            'success' => true,
            'data' => $breakdown
        ]);
    }

    /**
     * Recalculate salary for an employee for a specific month.
     */
    public function update(Request $request, $id)
    {
        $salary = EmployeeSalary::findOrFail($id);

        try {
            $updatedSalary = $this->salaryCalculationService->calculateSalary(
                $salary->employee_id,
                $salary->month
            );

            return response()->json([
                'success' => true,
                'message' => 'Salary recalculated successfully',
                'data' => $updatedSalary->load('employee')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get salary breakdown for an employee for a specific month.
     */
    public function getBreakdown(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|date_format:Y-m',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $breakdown = $this->salaryCalculationService->getSalaryBreakdown(
                $request->employee_id,
                $request->month
            );

            return response()->json([
                'success' => true,
                'data' => $breakdown
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
