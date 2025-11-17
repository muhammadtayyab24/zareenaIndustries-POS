<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    protected $fillable = [
        'employee_id',
        'month',
        'present_days',
        'absent_days',
        'half_days',
        'total_ot_hours',
        'total_advance_amount',
        'base_salary',
        'ot_amount',
        'final_salary',
        'notes',
    ];

    protected $casts = [
        'present_days' => 'integer',
        'absent_days' => 'integer',
        'half_days' => 'integer',
        'total_ot_hours' => 'decimal:2',
        'total_advance_amount' => 'decimal:2',
        'base_salary' => 'decimal:2',
        'ot_amount' => 'decimal:2',
        'final_salary' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
