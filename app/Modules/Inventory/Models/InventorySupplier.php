<?php

namespace App\Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySupplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'email',
        'contact_person',
        'tax_number',
        'status',
        'credit_limit',
        'payment_terms_days',
        'notes',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'payment_terms_days' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'supplier_id');
    }

    public function purchaseOrders()
    {
        return $this->hasMany(InventoryPurchaseOrder::class, 'supplier_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getTotalOrdersAttribute()
    {
        return $this->purchaseOrders()->count();
    }

    public function getTotalAmountAttribute()
    {
        return $this->purchaseOrders()->sum('total_amount');
    }
}
