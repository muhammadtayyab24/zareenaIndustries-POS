<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class EmployeeAdvanceSalary extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'amount',
        'notes',
        'is_deleted',
        'company_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'is_deleted' => 'boolean',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
