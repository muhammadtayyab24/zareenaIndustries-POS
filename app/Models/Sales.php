<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $fillable = [
        'type',
        'customer_id',
        'warehouse_id',
        'order_taker_id',
        'salesman_id',
        'invoice_no',
        'po_no',
        'dc_no',
        'due_date',
        'invoice_date',
        'freight_charges',
        'subtotal',
        'total_gst',
        'adv_inc_tax_percentage',
        'adv_inc_tax_amount',
        'grand_total',
        'company_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'invoice_date' => 'date',
        'freight_charges' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total_gst' => 'decimal:2',
        'adv_inc_tax_percentage' => 'decimal:2',
        'adv_inc_tax_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function orderTaker()
    {
        return $this->belongsTo(User::class, 'order_taker_id');
    }

    public function salesman()
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }

    public function products()
    {
        return $this->hasMany(SalesProduct::class, 'sale_id');
    }
}
