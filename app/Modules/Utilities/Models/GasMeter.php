<?php

namespace App\Modules\Utilities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GasMeter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'connection_id',
        'meter_number',
        'meter_type',
        'initial_reading',
        'current_reading',
        'installation_date',
        'last_reading_date',
        'status'
    ];

    protected $casts = [
        'initial_reading' => 'decimal:2',
        'current_reading' => 'decimal:2',
        'installation_date' => 'date',
        'last_reading_date' => 'date'
    ];

    public function connection()
    {
        return $this->belongsTo(GasConnection::class, 'connection_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
