<?php

namespace App\Modules\Water\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterQualityTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_location',
        'test_date',
        'ph_level',
        'chlorine_level',
        'turbidity',
        'temperature',
        'bacterial_count',
        'test_results',
        'remarks',
        'tested_by'
    ];

    protected $casts = [
        'test_date' => 'date',
        'ph_level' => 'decimal:2',
        'chlorine_level' => 'decimal:3',
        'turbidity' => 'decimal:2',
        'temperature' => 'decimal:2',
        'bacterial_count' => 'integer'
    ];
}
