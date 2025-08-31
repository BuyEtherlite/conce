<?php

namespace App\Models\Facilities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'facility_name',
        'facility_type',
        'address',
        'description',
        'capacity',
        'amenities',
        'hourly_rate',
        'daily_rate',
        'opening_time',
        'closing_time',
        'operating_days',
        'manager_name',
        'manager_contact',
        'active'
    ];

    protected $casts = [
        'amenities' => 'array',
        'operating_days' => 'array',
        'hourly_rate' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'active' => 'boolean',
        'opening_time' => 'datetime:H:i',
        'closing_time' => 'datetime:H:i'
    ];

    public function bookings()
    {
        return $this->hasMany(FacilityBooking::class);
    }

    public function maintenance()
    {
        return $this->hasMany(FacilityMaintenance::class);
    }

    public function availability()
    {
        return $this->hasMany(FacilityAvailability::class);
    }

    public function gateTakings()
    {
        return $this->hasMany(GateTaking::class);
    }

    public function documents()
    {
        return $this->hasMany(FacilityDocument::class);
    }

    public function sportsDetails()
    {
        return $this->hasOne(SportsFacility::class);
    }

    public function swimmingPoolDetails()
    {
        return $this->hasOne(SwimmingPool::class);
    }
}
