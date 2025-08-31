<?php

namespace App\Modules\Utilities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectricityMeterReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'meter_id',
        'reading',
        'consumption',
        'reading_date',
        'reader_name',
        'notes',
        'status'
    ];

    protected $casts = [
        'reading' => 'decimal:2',
        'consumption' => 'decimal:2',
        'reading_date' => 'date'
    ];

    public function meter()
    {
        return $this->belongsTo(ElectricityMeter::class, 'meter_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }
}
