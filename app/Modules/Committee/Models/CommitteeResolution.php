<?php

namespace App\Modules\Committee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommitteeResolution extends Model
{
    use SoftDeletes;

    protected $table = 'committee_resolutions';

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
