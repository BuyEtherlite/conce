<?php

namespace App\Modules\Water\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterMeterReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'connection_id',
        'meter_id',
        'reading',
        'consumption',
        'reading_date',
        'reader_notes',
        'read_by',
        'status'
    ];

    protected $casts = [
        'reading' => 'decimal:2',
        'consumption' => 'decimal:2',
        'reading_date' => 'date'
    ];

    public function connection()
    {
        return $this->belongsTo(WaterConnection::class, 'connection_id');
    }

    public function meter()
    {
        return $this->belongsTo(WaterMeter::class, 'meter_id');
    }
}