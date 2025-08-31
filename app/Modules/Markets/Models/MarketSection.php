<?php

namespace App\Modules\Markets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketSection extends Model
{
    protected $fillable = [
        'market_id',
        'name',
        'code',
        'description',
        'section_type',
        'area',
        'stall_count'
    ];

    protected $casts = [
        'area' => 'decimal:2',
    ];

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function stalls(): HasMany
    {
        return $this->hasMany(MarketStall::class);
    }

    public function getAvailableStallsCountAttribute()
    {
        return $this->stalls()->where('status', 'available')->count();
    }

    public function getOccupiedStallsCountAttribute()
    {
        return $this->stalls()->where('status', 'occupied')->count();
    }
}