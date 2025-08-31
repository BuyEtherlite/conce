<?php

namespace App\Modules\Water\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaterBill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'connection_id',
        'bill_number',
        'bill_date',
        'due_date',
        'billing_period',
        'consumption',
        'basic_charge',
        'consumption_charge',
        'service_charge',
        'penalty',
        'total_amount',
        'paid_amount',
        'status'
    ];

    protected $casts = [
        'bill_date' => 'date',
        'due_date' => 'date',
        'consumption' => 'decimal:2',
        'basic_charge' => 'decimal:2',
        'consumption_charge' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'penalty' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2'
    ];

    public function connection()
    {
        return $this->belongsTo(WaterConnection::class, 'connection_id');
    }
}
