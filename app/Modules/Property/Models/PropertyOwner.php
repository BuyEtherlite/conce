<?php

namespace App\Modules\Property\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyOwner extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'owner_type',
        'first_name',
        'last_name',
        'company_name',
        'id_number',
        'registration_number',
        'email',
        'phone',
        'address',
        'ownership_percentage',
        'ownership_start_date',
        'ownership_end_date',
        'is_primary',
        'is_active'
    ];

    protected $casts = [
        'ownership_percentage' => 'decimal:2',
        'ownership_start_date' => 'date',
        'ownership_end_date' => 'date',
        'is_primary' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function getFullNameAttribute()
    {
        if ($this->owner_type === 'individual') {
            return $this->first_name . ' ' . $this->last_name;
        }
        return $this->company_name;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
