<?php

namespace App\Modules\Housing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HousingProperty extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'housing_properties';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'property_number',
        'property_type',
        'address',
        'area_id',
        'bedrooms',
        'bathrooms',
        'monthly_rent',
        'deposit_amount',
        'status',
        'condition',
        'description',
        'latitude',
        'longitude',
        'is_available',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'monthly_rent' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'is_available' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the housing area that owns the property.
     */
    public function area()
    {
        return $this->belongsTo(HousingArea::class, 'area_id');
    }

    /**
     * Get the allocations for the property.
     */
    public function allocations()
    {
        return $this->hasMany(HousingAllocation::class, 'property_id');
    }
}
