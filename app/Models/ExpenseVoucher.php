<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class ExpenseVoucher extends Model
{
    protected $fillable = [
        'cat_id',
        'month',
        'amount',
        'description',
        'is_deleted',
        'company_id',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'cat_id');
    }
}
