<?php

namespace App\Modules\Markets\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class MarketRevenueCollection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'council_id',
        'market_id',
        'collection_date',
        'collection_period',
        'collector_id',
        'stall_fees_collected',
        'penalty_fees_collected',
        'other_fees_collected',
        'total_amount_collected',
        'payment_method',
        'receipt_number_start',
        'receipt_number_end',
        'receipts_issued',
        'cash_amount',
        'cheque_amount',
        'mobile_money_amount',
        'bank_deposit_reference',
        'collection_status',
        'verification_status',
        'verified_by',
        'verified_at',
        'discrepancies',
        'notes'
    ];

    protected $casts = [
        'collection_date' => 'date',
        'stall_fees_collected' => 'decimal:2',
        'penalty_fees_collected' => 'decimal:2',
        'other_fees_collected' => 'decimal:2',
        'total_amount_collected' => 'decimal:2',
        'cash_amount' => 'decimal:2',
        'cheque_amount' => 'decimal:2',
        'mobile_money_amount' => 'decimal:2',
        'verified_at' => 'datetime',
        'discrepancies' => 'array'
    ];

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}