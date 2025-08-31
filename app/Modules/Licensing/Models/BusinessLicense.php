<?php

namespace App\Modules\Licensing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessLicense extends Model
{
    use SoftDeletes;

    protected $table = 'business_licenses';

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
