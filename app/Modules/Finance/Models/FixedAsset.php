<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FixedAsset extends Model
{
    use HasFactory;

    protected $table = 'fixed_assets';

    protected $fillable = [
        'asset_tag',
        'asset_name',
        'description',
        'asset_category',
        'acquisition_date',
        'acquisition_cost',
        'useful_life_years',
        'depreciation_method',
        'accumulated_depreciation',
        'book_value',
        'location',
        'condition',
        'status',
        'council_id'
    ];

    protected $casts = [
        'acquisition_date' => 'date',
        'acquisition_cost' => 'decimal:2',
        'accumulated_depreciation' => 'decimal:2',
        'book_value' => 'decimal:2',
        'useful_life_years' => 'integer'
    ];

    public function council()
    {
        return $this->belongsTo(\App\Models\Council::class);
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Finance\AssetCategory::class, 'asset_category', 'id');
    }

    public function location()
    {
        return $this->belongsTo(\App\Models\Finance\AssetLocation::class, 'location', 'id');
    }

    public function depreciation()
    {
        return $this->hasMany(\App\Models\Finance\AssetDepreciation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function calculateDepreciation()
    {
        if ($this->depreciation_method === 'straight_line' && $this->useful_life_years > 0) {
            return $this->acquisition_cost / $this->useful_life_years;
        }
        return 0;
    }
}
