<?php

namespace App\Models\Cemetery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CemeteryReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'plot_id',
        'reserved_by_name',
        'reserved_by_phone',
        'reserved_by_email',
        'reservation_date',
        'expiry_date',
        'reservation_fee',
        'payment_status',
        'status',
        'notes',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'expiry_date' => 'date',
        'reservation_fee' => 'decimal:2',
        'payment_status' => 'boolean',
    ];

    public function plot()
    {
        return $this->belongsTo(CemeteryPlot::class, 'plot_id');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'success',
            'expired' => 'danger',
            'cancelled' => 'secondary',
            'converted' => 'info',
            default => 'warning'
        };
    }
}
