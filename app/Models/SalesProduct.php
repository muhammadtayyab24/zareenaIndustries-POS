<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class SalesProduct extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'unit_type',
        'qty',
        'price',
        'gst_percentage',
        'net_amount',
        'gst_amount',
        'total_amount',
        'company_id',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'price' => 'decimal:2',
        'gst_percentage' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sales::class, 'sale_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
