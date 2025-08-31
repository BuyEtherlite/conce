<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_name',
        'description',
        'building',
        'floor',
        'room',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function assets()
    {
        return $this->hasMany(FixedAsset::class, 'location_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFullLocationAttribute()
    {
        $parts = array_filter([$this->building, $this->floor, $this->room]);
        return $this->location_name . ($parts ? ' (' . implode(', ', $parts) . ')' : '');
    }
}
