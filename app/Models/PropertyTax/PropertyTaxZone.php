<?php

namespace App\Models\PropertyTax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyTaxZone extends Model
{
    use HasFactory;

    protected $table = 'property_tax_zones';

    protected $fillable = [
        'zone_code',
        'zone_name', 
        'description',
        'zone_multiplier',
        'zone_boundaries',
        'is_active'
    ];

    protected $casts = [
        'zone_multiplier' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function assessments()
    {
        return $this->hasMany(PropertyTaxAssessment::class, 'zone_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
