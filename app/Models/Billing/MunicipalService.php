<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Council;

class MunicipalService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'service_code',
        'base_rate',
        'billing_frequency',
        'is_taxable',
        'tax_percentage',
        'active',
        'category_id',
        'council_id'
    ];

    protected $casts = [
        'base_rate' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'is_taxable' => 'boolean',
        'active' => 'boolean'
    ];

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function category()
    {
        return $this->belongsTo(MunicipalServiceCategory::class, 'category_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(ServiceSubscription::class, 'service_id');
    }

    public function billLineItems()
    {
        return $this->hasMany(BillLineItem::class, 'service_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function calculateTaxAmount($amount)
    {
        if (!$this->is_taxable) {
            return 0;
        }
        
        return ($amount * $this->tax_percentage) / 100;
    }
}
