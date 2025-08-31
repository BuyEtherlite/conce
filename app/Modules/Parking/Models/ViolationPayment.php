<?php

namespace App\Modules\Parking\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViolationPayment extends Model
{
    protected $fillable = [
        'violation_id',
        'payment_reference',
        'amount',
        'payment_method',
        'payment_date',
        'processed_by',
        'status',
        'payment_details',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'payment_details' => 'array'
    ];

    public function violation(): BelongsTo
    {
        return $this->belongsTo(ParkingViolation::class, 'violation_id');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function getPaymentMethodLabel(): string
    {
        $labels = [
            'cash' => 'Cash',
            'card' => 'Credit/Debit Card',
            'bank_transfer' => 'Bank Transfer',
            'online' => 'Online Payment',
            'mobile' => 'Mobile Payment'
        ];

        return $labels[$this->payment_method] ?? $this->payment_method;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (!$payment->payment_reference) {
                $payment->payment_reference = 'PAY-' . date('YmdHis') . '-' . str_pad(
                    mt_rand(1, 9999),
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }
}