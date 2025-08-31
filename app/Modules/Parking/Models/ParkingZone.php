<?php

namespace App\Modules\Parking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingZone extends Model
{
    protected $fillable = [
        'zone_code',
        'zone_name',
        'description',
        'boundaries',
        'hourly_rate',
        'max_duration_minutes',
        'operating_hours',
        'restricted_days',
        'zone_type',
        'is_active'
    ];

    protected $casts = [
        'boundaries' => 'array',
        'operating_hours' => 'array',
        'restricted_days' => 'array',
        'hourly_rate' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function spaces(): HasMany
    {
        return $this->hasMany(ParkingSpace::class, 'zone_id');
    }

    public function violations(): HasMany
    {
        return $this->hasMany(ParkingViolation::class, 'zone_id');
    }

    public function permits(): HasMany
    {
        return $this->hasMany(ParkingPermit::class, 'zone_id');
    }

    public function meters(): HasMany
    {
        return $this->hasMany(ParkingMeter::class, 'zone_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ParkingSession::class, 'zone_id');
    }

    public function revenueCollections(): HasMany
    {
        return $this->hasMany(ParkingRevenueCollection::class, 'zone_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isOperatingNow(): bool
    {
        $now = now();
        $dayOfWeek = strtolower($now->format('l'));

        if (!isset($this->operating_hours[$dayOfWeek])) {
            return false;
        }

        $hours = $this->operating_hours[$dayOfWeek];
        $currentTime = $now->format('H:i');

        return $currentTime >= $hours['start'] && $currentTime <= $hours['end'];
    }

    public function calculateParkingFee(int $durationMinutes): float
    {
        $hours = ceil($durationMinutes / 60);
        return $hours * $this->hourly_rate;
    }
}