<?php

namespace App\Modules\Utilities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FleetVehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_number',
        'make',
        'model',
        'year',
        'vehicle_type',
        'license_plate',
        'vin_number',
        'mileage',
        'purchase_date',
        'purchase_price',
        'fuel_type',
        'status',
        'assigned_driver',
        'notes'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2'
    ];

    public function maintenanceRecords()
    {
        return $this->hasMany(FleetMaintenanceRecord::class, 'vehicle_id');
    }

    public function fuelRecords()
    {
        return $this->hasMany(FleetFuelRecord::class, 'vehicle_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('vehicle_type', $type);
    }
}
