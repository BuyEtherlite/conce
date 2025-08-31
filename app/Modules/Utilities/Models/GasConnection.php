<?php

namespace App\Modules\Utilities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;

class GasConnection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'council_id',
        'customer_id',
        'connection_number',
        'meter_number',
        'connection_type',
        'pipe_size',
        'pressure_rating',
        'installation_date',
        'last_inspection_date',
        'next_inspection_due',
        'connection_status',
        'monthly_consumption_limit',
        'safety_certificate_number',
        'safety_certificate_expiry',
        'installation_cost',
        'connection_fee',
        'deposit_amount',
        'address',
        'latitude',
        'longitude',
        'notes'
    ];

    protected $casts = [
        'installation_date' => 'date',
        'last_inspection_date' => 'date',
        'next_inspection_due' => 'date',
        'safety_certificate_expiry' => 'date',
        'installation_cost' => 'decimal:2',
        'connection_fee' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function meters()
    {
        return $this->hasMany(GasMeter::class);
    }
}