<?php

namespace App\Modules\Parking\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParkingSpace extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'council_id',
        'zone_id',
        'space_number',
        'space_type',
        'status',
        'hourly_rate',
        'daily_rate',
        'monthly_rate',
        'location_description',
        'latitude',
        'longitude',
        'size_length',
        'size_width',
        'accessibility_features',
        'restrictions',
        'notes'
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'monthly_rate' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'size_length' => 'decimal:2',
        'size_width' => 'decimal:2',
        'accessibility_features' => 'array',
        'restrictions' => 'array'
    ];

    public function zone()
    {
        return $this->belongsTo(ParkingZone::class);
    }

    public function permits()
    {
        return $this->hasMany(ParkingPermit::class);
    }

    public function violations()
    {
        return $this->hasMany(ParkingViolation::class);
    }
}