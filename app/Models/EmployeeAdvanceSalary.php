<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAdvanceSalary extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'amount',
        'notes',
        'is_deleted',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'is_deleted' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
