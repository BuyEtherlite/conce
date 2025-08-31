<?php

namespace App\Modules\Housing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HousingArea extends Model
{
    use SoftDeletes;

    protected $table = 'housing_areas';

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
