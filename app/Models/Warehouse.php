<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'code',
        'contact',
        'address',
        'email',
        'status',
        'is_deleted',
    ];

    public function stocks()
    {
        return $this->hasMany(WarehouseStock::class);
    }
}

