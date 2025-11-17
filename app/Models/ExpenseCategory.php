<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $fillable = [
        'name',
        'status',
        'is_deleted',
    ];

    public function vouchers()
    {
        return $this->hasMany(ExpenseVoucher::class, 'cat_id');
    }
}
