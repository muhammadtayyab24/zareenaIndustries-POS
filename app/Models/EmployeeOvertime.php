<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class EmployeeOvertime extends Model
{
    protected $table = 'employee_overtime_hours';

    protected $fillable = [
        'employee_id',
        'date',
        'ot_hours',
        'company_id',
    ];

    protected $casts = [
        'date' => 'date',
        'ot_hours' => 'decimal:2',
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
