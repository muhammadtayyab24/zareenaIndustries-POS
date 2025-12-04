<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'contact',
        'designation',
        'monthly_salary',
        'ot_rate_per_hour',
        'status',
        'is_deleted',
        'company_id',
    ];

    protected $casts = [
        'monthly_salary' => 'decimal:2',
        'ot_rate_per_hour' => 'decimal:2',
        'status' => 'integer',
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

    public function attendances()
    {
        return $this->hasMany(EmployeeAttendance::class);
    }

    public function advanceSalaries()
    {
        return $this->hasMany(EmployeeAdvanceSalary::class);
    }

    public function overtimes()
    {
        return $this->hasMany(EmployeeOvertime::class);
    }

    public function salaries()
    {
        return $this->hasMany(EmployeeSalary::class);
    }
}
