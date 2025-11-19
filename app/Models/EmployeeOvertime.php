<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeOvertime extends Model
{
    protected $table = 'employee_overtime_hours';

    protected $fillable = [
        'employee_id',
        'date',
        'ot_hours',
    ];

    protected $casts = [
        'date' => 'date',
        'ot_hours' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
