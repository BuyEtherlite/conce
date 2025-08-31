<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ap_vendors';

    protected $fillable = [
        'vendor_number',
        'vendor_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'tax_number',
        'bank_name',
        'account_number',
        'payment_terms',
        'credit_limit',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credit_limit' => 'decimal:2'
    ];

    public function bills()
    {
        return $this->hasMany(ApBill::class, 'vendor_id');
    }

    public function payments()
    {
        return $this->hasMany(PaymentVoucher::class, 'vendor_id');
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_id');
    }
}
