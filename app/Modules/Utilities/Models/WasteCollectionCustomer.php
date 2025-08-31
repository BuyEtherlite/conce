<?php

namespace App\Modules\Utilities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WasteCollectionCustomer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_number',
        'customer_name',
        'property_address',
        'service_type',
        'collection_frequency',
        'route_id',
        'service_start_date',
        'status',
        'monthly_fee',
        'special_instructions'
    ];

    protected $casts = [
        'service_start_date' => 'date',
        'monthly_fee' => 'decimal:2'
    ];

    public function route()
    {
        return $this->belongsTo(WasteCollectionRoute::class, 'route_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByServiceType($query, $type)
    {
        return $query->where('service_type', $type);
    }
}
