<?php

namespace App\Modules\Housing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HousingStand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stand_area_id',
        'stand_number',
        'stand_code',
        'size_sqm',
        'price_total',
        'price_per_sqm',
        'coordinates',
        'facing_direction',
        'corner_stand',
        'slope_grade',
        'utilities_connected',
        'access_road_type',
        'road_frontage_meters',
        'status',
        'special_features',
        'restrictions',
        'is_premium'
    ];

    protected $casts = [
        'size_sqm' => 'decimal:2',
        'price_total' => 'decimal:2',
        'price_per_sqm' => 'decimal:2',
        'road_frontage_meters' => 'decimal:2',
        'utilities_connected' => 'array',
        'is_premium' => 'boolean'
    ];

    public function standArea(): BelongsTo
    {
        return $this->belongsTo(HousingStandArea::class, 'stand_area_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(StandAllocation::class, 'stand_id');
    }

    public function currentAllocation(): HasOne
    {
        return $this->hasOne(StandAllocation::class, 'stand_id')
            ->whereIn('status', ['approved', 'allocated'])
            ->latest();
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'available' => 'Available',
            'allocated' => 'Allocated',
            'reserved' => 'Reserved',
            'under_development' => 'Under Development'
        ];

        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    public function getFullStandCodeAttribute()
    {
        return $this->standArea->code . '-' . $this->stand_number;
    }

    public function getCurrentOwnerAttribute()
    {
        return $this->currentAllocation ? $this->currentAllocation->applicant_name : null;
    }

    public function getIsAvailableAttribute()
    {
        return $this->status === 'available';
    }
}
