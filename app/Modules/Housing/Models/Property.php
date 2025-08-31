<?php

namespace App\Modules\Housing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    protected $table = 'properties';

    protected $fillable = ['property_code', 'title', 'description', 'property_type', 'address', 'suburb', 'city', 'postal_code', 'province', 'country', 'latitude', 'longitude', 'size_sqm', 'size_hectares', 'erf_number', 'title_deed_number', 'title_deed_date', 'surveyor_general_code', 'category_id', 'zone_id', 'market_value', 'municipal_value', 'rental_amount', 'ownership_type', 'status', 'amenities', 'utilities', 'accessibility_features', 'notes', 'council_id', 'office_id', 'created_by', 'updated_by'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        // Add casts here
    ];
}
