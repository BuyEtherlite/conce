<?php

namespace App\Models\PropertyTax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyTaxCategory extends Model
{
    use HasFactory;

    protected $table = 'property_tax_categories';

    protected $fillable = [
        'name',
        'code',
        'description',
        'rate_percentage',
        'minimum_amount',
        'maximum_amount',
        'is_active'
    ];

    protected $casts = [
        'rate_percentage' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_amount' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function valuations()
    {
        return $this->hasMany(PropertyValuation::class, 'tax_category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
