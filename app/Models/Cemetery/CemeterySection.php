<?php

namespace App\Models\Cemetery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CemeterySection extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_name',
        'description',
        'total_plots',
        'occupied_plots',
        'available_plots',
        'reserved_plots',
        'base_price',
        'is_active',
    ];

    protected $casts = [
        'total_plots' => 'integer',
        'occupied_plots' => 'integer',
        'available_plots' => 'integer',
        'reserved_plots' => 'integer',
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function plots()
    {
        return $this->hasMany(CemeteryPlot::class, 'section', 'section_name');
    }
}
