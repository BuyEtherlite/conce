<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RevenueSource extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category',
        'target_amount',
        'collected_amount',
        'is_active',
        'council_id'
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function council()
    {
        return $this->belongsTo(\App\Models\Council::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }

    public function targets()
    {
        return $this->hasMany(RevenueTarget::class);
    }
}
