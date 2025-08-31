<?php

namespace App\Modules\Property\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'zone_multiplier',
        'is_active'
    ];

    protected $casts = [
        'zone_multiplier' => 'decimal:4',
        'is_active' => 'boolean'
    ];

    public function properties()
    {
        return $this->hasMany(Property::class, 'zone_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
