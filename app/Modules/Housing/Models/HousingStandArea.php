<?php

namespace App\Modules\Housing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HousingStandArea extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'sector_type',
        'location',
        'total_area_hectares',
        'total_stands',
        'allocated_stands',
        'available_stands',
        'stand_size_min_sqm',
        'stand_size_max_sqm',
        'price_per_sqm',
        'utilities_available',
        'amenities',
        'development_status',
        'zoning_approval',
        'development_start_date',
        'development_completion_date',
        'special_conditions',
        'is_active'
    ];

    protected $casts = [
        'total_area_hectares' => 'decimal:4',
        'stand_size_min_sqm' => 'decimal:2',
        'stand_size_max_sqm' => 'decimal:2',
        'price_per_sqm' => 'decimal:2',
        'utilities_available' => 'array',
        'amenities' => 'array',
        'development_start_date' => 'date',
        'development_completion_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function stands(): HasMany
    {
        return $this->hasMany(HousingStand::class, 'stand_area_id');
    }

    public function availableStands(): HasMany
    {
        return $this->hasMany(HousingStand::class, 'stand_area_id')->where('status', 'available');
    }

    public function allocatedStands(): HasMany
    {
        return $this->hasMany(HousingStand::class, 'stand_area_id')->where('status', 'allocated');
    }

    public function getSectorTypeDisplayAttribute()
    {
        $types = [
            'residential' => 'Residential',
            'commercial' => 'Commercial',
            'industrial' => 'Industrial',
            'mixed_use' => 'Mixed Use',
            'recreational' => 'Recreational'
        ];

        return $types[$this->sector_type] ?? ucfirst($this->sector_type);
    }

    public function getDevelopmentStatusDisplayAttribute()
    {
        $statuses = [
            'planned' => 'Planned',
            'under_development' => 'Under Development',
            'developed' => 'Developed',
            'occupied' => 'Occupied'
        ];

        return $statuses[$this->development_status] ?? ucfirst($this->development_status);
    }

    public function getOccupancyRateAttribute()
    {
        if ($this->total_stands == 0) {
            return 0;
        }

        return round(($this->allocated_stands / $this->total_stands) * 100, 2);
    }
}
