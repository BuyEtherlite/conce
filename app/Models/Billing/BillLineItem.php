<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillLineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'service_id',
        'description',
        'quantity',
        'unit_rate',
        'amount',
        'tax_amount'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_rate' => 'decimal:2',
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2'
    ];

    public function bill()
    {
        return $this->belongsTo(MunicipalBill::class, 'bill_id');
    }

    public function service()
    {
        return $this->belongsTo(MunicipalService::class, 'service_id');
    }
}
