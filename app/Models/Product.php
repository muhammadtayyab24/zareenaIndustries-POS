<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'cat_id',
        'type_id',
        'product_name',
        'unit_type',
        'opening_qty',
        'current_qty',
        'status',
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
        return $this->belongsTo(ProductCategory::class, 'cat_id');
    }

    public function type()
    {
        return $this->belongsTo(ProductType::class, 'type_id');
    }

    public function warehouseStocks()
    {
        return $this->hasMany(WarehouseStock::class);
    }
}
