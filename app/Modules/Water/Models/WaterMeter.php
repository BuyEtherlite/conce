<?php

namespace App\Modules\Water\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaterMeter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'meter_number',
        'connection_id',
        'meter_type',
        'meter_size',
        'manufacturer',
        'model',
        'installation_date',
        'last_reading_date',
        'last_reading',
        'current_reading',
        'status',
        'remarks',
        'location_description',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'installation_date' => 'date',
        'last_reading_date' => 'date',
        'last_reading' => 'decimal:3',
        'current_reading' => 'decimal:3',
        'meter_size' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    public function connection()
    {
        return $this->belongsTo(WaterConnection::class, 'connection_id');
    }

    public function readings()
    {
        return $this->hasMany(WaterMeterReading::class, 'meter_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('meter_type', $type);
    }

    public function getConsumptionAttribute()
    {
        return $this->current_reading - $this->last_reading;
    }
}