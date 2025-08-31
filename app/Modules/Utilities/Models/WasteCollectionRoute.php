<?php

namespace App\Modules\Utilities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteCollectionRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_name',
        'route_code',
        'driver_name',
        'vehicle_number',
        'collection_days',
        'start_time',
        'route_description',
        'status'
    ];

    protected $casts = [
        'collection_days' => 'array',
        'start_time' => 'datetime'
    ];

    public function customers()
    {
        return $this->hasMany(WasteCollectionCustomer::class, 'route_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
