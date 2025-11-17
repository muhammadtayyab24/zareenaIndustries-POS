<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeOvertime;
use App\Models\EmployeeAdvanceSalary;
use App\Models\EmployeeSalary;
use App\Services\SalaryCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EmployeeReportController extends Controller
{
    protected $salaryCalculationService;

    public function __construct(SalaryCalculationService $salaryCalculationService)
    {
        $this->salaryCalculationService = $salaryCalculationService;
    }

    /**
     * Get complete employee report with all details
     */
    public function getEmployeeReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $employee = Employee::findOrFail($request->employee_id);
        
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // Get attendance summary
        $attendances = EmployeeAttendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $attendanceSummary = [
            'total_days' => $attendances->count(),
            'present_days' => $attendances->where('status', 'present')->count(),
            'absent_days' => $attendances->where('status', 'absent')->count(),
            'half_days' => $attendances->where('status', 'half_day')->count(),
            'total_working_days' => $attendances->where('status', 'present')->count() + ($attendances->where('status', 'half_day')->count() * 0.5),
        ];

        // Get monthly OT summary
        $overtimes = EmployeeOvertime::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $monthlyOT = [];
        $overtimes->groupBy(function($item) {
            return Carbon::parse($item->date)->format('Y-m');
        })->each(function($group, $month) use (&$monthlyOT, $employee) {
            $monthlyOT[$month] = [
                'total_hours' => $group->sum('ot_hours'),
                'ot_amount' => $group->sum('ot_hours') * $employee->ot_rate_per_hour,
            ];
        });

        // Get advance salary deductions
        $advances = EmployeeAdvanceSalary::where('employee_id', $employee->id)
            ->where('is_deleted', false)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $advanceSummary = [
            'total_advances' => $advances->count(),
            'total_amount' => $advances->sum('amount'),
            'advances' => $advances->map(function($advance) {
                return [
                    'id' => $advance->id,
                    'date' => $advance->date->format('Y-m-d'),
                    'amount' => $advance->amount,
                    'notes' => $advance->notes,
                ];
            }),
        ];

        // Get salary records for the period
        $salaries = EmployeeSalary::where('employee_id', $employee->id)
            ->whereBetween('month', [
                $startDate->format('Y-m'),
                $endDate->format('Y-m')
            ])
            ->orderBy('month', 'desc')
            ->get();

        $salarySummary = $salaries->map(function($salary) {
            return [
                'month' => $salary->month,
                'present_days' => $salary->present_days,
                'absent_days' => $salary->absent_days,
                'half_days' => $salary->half_days,
                'total_ot_hours' => $salary->total_ot_hours,
                'total_advance_amount' => $salary->total_advance_amount,
                'base_salary' => $salary->base_salary,
                'ot_amount' => $salary->ot_amount,
                'final_salary' => $salary->final_salary,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'employee' => [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'contact' => $employee->contact,
                    'designation' => $employee->designation,
                    'monthly_salary' => $employee->monthly_salary,
                    'ot_rate_per_hour' => $employee->ot_rate_per_hour,
                ],
                'period' => [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                ],
                'attendance_summary' => $attendanceSummary,
                'monthly_ot' => $monthlyOT,
                'advance_salary_deductions' => $advanceSummary,
                'salary_calculations' => $salarySummary,
            ]
        ]);
    }

    /**
     * Get employee report for a specific month
     */
    public function getMonthlyReport(Request $request)
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
