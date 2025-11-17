<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeOvertime;
use App\Models\EmployeeAdvanceSalary;
use App\Models\EmployeeSalary;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalaryCalculationService
{
    /**
     * Calculate and create salary record for an employee for a specific month
     * 
     * @param int $employeeId
     * @param string $month Format: YYYY-MM
     * @return EmployeeSalary
     */
    public function calculateSalary(int $employeeId, string $month): EmployeeSalary
    {
        $employee = Employee::findOrFail($employeeId);
        
        if (!$employee->monthly_salary || !$employee->ot_rate_per_hour) {
            throw new \Exception('Employee monthly salary or OT rate not set.');
        }

        // Get date range for the month
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = Carbon::parse($month . '-01')->endOfMonth();

        // Calculate attendance
        $attendances = EmployeeAttendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $presentDays = $attendances->where('status', 'present')->count();
        $absentDays = $attendances->where('status', 'absent')->count();
        $halfDays = $attendances->where('status', 'half_day')->count();
        
        // Half day counts as 0.5 present days
        $totalPresentDays = $presentDays + ($halfDays * 0.5);

        // Calculate total OT hours
        $totalOtHours = EmployeeOvertime::where('employee_id', $employeeId)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('ot_hours');

        // Calculate total advance amount
        $totalAdvanceAmount = EmployeeAdvanceSalary::where('employee_id', $employeeId)
            ->where('is_deleted', false)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        // Calculate base salary: (present_days / 30) * monthly_salary
        $baseSalary = ($totalPresentDays / 30) * $employee->monthly_salary;

        // Calculate OT amount: total_ot_hours * ot_rate_per_hour
        $otAmount = $totalOtHours * $employee->ot_rate_per_hour;

        // Calculate final salary: base_salary + ot_amount - total_advance_amount
        $finalSalary = $baseSalary + $otAmount - $totalAdvanceAmount;

        // Create or update salary record
        $salary = EmployeeSalary::updateOrCreate(
            [
                'employee_id' => $employeeId,
                'month' => $month,
            ],
            [
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'half_days' => $halfDays,
                'total_ot_hours' => $totalOtHours,
                'total_advance_amount' => $totalAdvanceAmount,
                'base_salary' => round($baseSalary, 2),
                'ot_amount' => round($otAmount, 2),
                'final_salary' => round($finalSalary, 2),
            ]
        );

        return $salary;
    }

    /**
     * Get salary breakdown for an employee for a specific month
     * 
     * @param int $employeeId
     * @param string $month Format: YYYY-MM
     * @return array
     */
    public function getSalaryBreakdown(int $employeeId, string $month): array
    {
        $employee = Employee::findOrFail($employeeId);
        $salary = EmployeeSalary::where('employee_id', $employeeId)
            ->where('month', $month)
            ->first();

        if (!$salary) {
            // Calculate if not exists
            $salary = $this->calculateSalary($employeeId, $month);
        }

        return [
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
            'month' => $month,
            'monthly_salary' => $employee->monthly_salary,
            'ot_rate_per_hour' => $employee->ot_rate_per_hour,
            'attendance' => [
                'present_days' => $salary->present_days,
                'absent_days' => $salary->absent_days,
                'half_days' => $salary->half_days,
                'total_working_days' => $salary->present_days + ($salary->half_days * 0.5),
            ],
            'overtime' => [
                'total_hours' => $salary->total_ot_hours,
                'rate_per_hour' => $employee->ot_rate_per_hour,
                'total_amount' => $salary->ot_amount,
            ],
            'advances' => [
                'total_amount' => $salary->total_advance_amount,
            ],
            'salary_calculation' => [
                'base_salary' => $salary->base_salary,
                'ot_amount' => $salary->ot_amount,
                'advance_deduction' => $salary->total_advance_amount,
                'final_salary' => $salary->final_salary,
            ],
        ];
    }
}

