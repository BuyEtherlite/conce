<?php

namespace App\Modules\Utilities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;

class ElectricityConnection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'connection_number',
        'customer_id',
        'property_address',
        'connection_type',
        'load_capacity',
        'status',
        'connection_date',
        'monthly_charge',
        'notes',
    ];

    protected $casts = [
        'connection_date' => 'date',
        'load_capacity' => 'decimal:2',
        'monthly_charge' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}