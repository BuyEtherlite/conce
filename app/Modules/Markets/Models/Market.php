<?php

namespace App\Modules\Markets\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Market extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'location',
        'description',
        'manager_name',
        'manager_contact',
        'operational_hours',
        'total_stalls',
        'occupied_stalls',
        'daily_fee',
        'monthly_fee',
        'is_active',
        'council_id'
    ];

    protected $casts = [
        'daily_fee' => 'decimal:2',
        'monthly_fee' => 'decimal:2',
        'is_active' => 'boolean',
        'total_stalls' => 'integer',
        'occupied_stalls' => 'integer'
    ];

    public function council()
    {
        return $this->belongsTo(\App\Models\Council::class);
    }

    public function stalls()
    {
        return $this->hasMany(MarketStall::class);
    }

    public function allocations()
    {
        return $this->hasMany(StallAllocation::class);
    }

    public function sections()
    {
        return $this->hasMany(MarketSection::class);
    }
}