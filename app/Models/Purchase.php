<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'type',
        'vendor_id',
        'warehouse_id',
        'vendor_invoice_no',
        'po_no',
        'grn_no',
        'credit_term',
        'due_date',
        'labour_charges',
        'freight_charges',
        'subtotal',
        'total_gst',
        'grand_total',
        'company_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'labour_charges' => 'decimal:2',
        'freight_charges' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total_gst' => 'decimal:2',
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

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function products()
    {
        return $this->hasMany(PurchaseProduct::class);
    }
}

