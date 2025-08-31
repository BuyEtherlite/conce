<?php

namespace App\Modules\Markets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MarketStall extends Model
{
    protected $fillable = [
        'market_id',
        'market_section_id',
        'stall_number',
        'stall_code',
        'length',
        'width',
        'area',
        'stall_type',
        'monthly_rent',
        'daily_rent',
        'status',
        'utilities',
        'features',
        'notes'
    ];

    protected $casts = [
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'area' => 'decimal:2',
        'monthly_rent' => 'decimal:2',
        'daily_rent' => 'decimal:2',
        'utilities' => 'array',
        'features' => 'array',
    ];

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(MarketSection::class, 'market_section_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(StallAllocation::class);
    }

    public function currentAllocation(): HasOne
    {
        return $this->hasOne(StallAllocation::class)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(StallInspection::class);
    }

    public function revenueCollections(): HasMany
    {
        return $this->hasManyThrough(
            MarketRevenueCollection::class,
            StallAllocation::class
        );
    }

    public function getIsOccupiedAttribute()
    {
        return $this->status === 'occupied';
    }

    public function getCurrentTenant()
    {
        return $this->currentAllocation ? $this->currentAllocation->tenant_name : null;
    }
}