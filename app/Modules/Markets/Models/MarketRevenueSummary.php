<?php

namespace App\Modules\Markets\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketRevenueSummary extends Model
{
    protected $fillable = [
        'market_id',
        'period_type',
        'period_date',
        'total_stalls',
        'occupied_stalls',
        'vacant_stalls',
        'total_rent_due',
        'total_rent_collected',
        'total_utilities_collected',
        'total_penalties_collected',
        'total_deposits_collected',
        'total_revenue',
        'collection_rate',
        'new_allocations',
        'terminated_allocations'
    ];

    protected $casts = [
        'period_date' => 'date',
        'total_rent_due' => 'decimal:2',
        'total_rent_collected' => 'decimal:2',
        'total_utilities_collected' => 'decimal:2',
        'total_penalties_collected' => 'decimal:2',
        'total_deposits_collected' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'collection_rate' => 'decimal:2',
    ];

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function getOutstandingAmountAttribute()
    {
        return $this->total_rent_due - $this->total_rent_collected;
    }
}