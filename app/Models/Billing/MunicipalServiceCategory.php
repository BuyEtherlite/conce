<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Council;

class MunicipalServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'code',
        'is_active',
        'council_id'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function services()
    {
        return $this->hasMany(MunicipalService::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
