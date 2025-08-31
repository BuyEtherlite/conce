<?php

namespace App\Modules\Water\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaterConnection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'connection_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'property_address',
        'connection_type',
        'meter_size',
        'installation_date',
        'deposit_amount',
        'monthly_rate',
        'status',
        'created_by'
    ];

    protected $casts = [
        'installation_date' => 'date',
        'deposit_amount' => 'decimal:2',
        'monthly_rate' => 'decimal:2'
    ];

    public function meters()
    {
        return $this->hasMany(WaterMeter::class, 'connection_id');
    }

    public function bills()
    {
        return $this->hasMany(WaterBill::class, 'connection_id');
    }

    public function readings()
    {
        return $this->hasMany(WaterMeterReading::class, 'connection_id');
    }
}
