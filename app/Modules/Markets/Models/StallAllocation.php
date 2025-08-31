<?php

namespace App\Modules\Markets\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StallAllocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'market_id',
        'stall_id',
        'trader_name',
        'trader_contact',
        'trader_id_number',
        'business_name',
        'business_type',
        'allocation_date',
        'expiry_date',
        'daily_fee',
        'monthly_fee',
        'status',
        'council_id'
    ];

    protected $casts = [
        'allocation_date' => 'date',
        'expiry_date' => 'date',
        'daily_fee' => 'decimal:2',
        'monthly_fee' => 'decimal:2'
    ];

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function stall()
    {
        return $this->belongsTo(MarketStall::class);
    }

    public function council()
    {
        return $this->belongsTo(\App\Models\Council::class);
    }
}