<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseVoucher extends Model
{
    protected $fillable = [
        'cat_id',
        'month',
        'amount',
        'description',
        'is_deleted',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'cat_id');
    }
}
