<?php

namespace App\Modules\Water\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaterRate extends Model
{
    use SoftDeletes;

    protected $table = 'water_rates';

    protected $fillable = [
        // Add fillable fields here
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        // Add casts here
    ];
}
