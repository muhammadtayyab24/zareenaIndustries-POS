<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = [
        'name',
        'status',
        'is_deleted',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'cat_id');
    }
}
