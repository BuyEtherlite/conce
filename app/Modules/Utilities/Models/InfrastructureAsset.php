<?php

namespace App\Modules\Utilities\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InfrastructureAsset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_code',
        'asset_type',
        'asset_name',
        'location',
        'description',
        'installation_date',
        'original_cost',
        'condition',
        'last_inspection_date',
        'next_inspection_date',
        'status'
    ];

    protected $casts = [
        'installation_date' => 'date',
        'original_cost' => 'decimal:2',
        'last_inspection_date' => 'date',
        'next_inspection_date' => 'date'
    ];

    public function maintenanceRequests()
    {
        return $this->hasMany(InfrastructureMaintenanceRequest::class, 'asset_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('asset_type', $type);
    }

    public function scopeByCondition($query, $condition)
    {
        return $query->where('condition', $condition);
    }
}
