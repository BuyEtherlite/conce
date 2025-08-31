<?php

namespace App\Models\Cemetery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CemeteryPlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'plot_number',
        'section',
        'size',
        'price',
        'status',
        'description',
        'gps_latitude',
        'gps_longitude',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'gps_latitude' => 'decimal:8',
        'gps_longitude' => 'decimal:8',
    ];

    public function burialRecords()
    {
        return $this->hasMany(BurialRecord::class, 'plot_id');
    }

    public function reservations()
    {
        return $this->hasMany(CemeteryReservation::class, 'plot_id');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'available' => 'success',
            'reserved' => 'warning',
            'occupied' => 'danger',
            default => 'secondary'
        };
    }
}
