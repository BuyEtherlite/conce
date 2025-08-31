<?php

namespace App\Modules\Licensing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LicenseType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'category',
        'application_fee',
        'license_fee',
        'renewal_fee',
        'validity_months',
        'required_documents',
        'conditions',
        'requires_inspection',
        'is_active'
    ];

    protected $casts = [
        'application_fee' => 'decimal:2',
        'license_fee' => 'decimal:2',
        'renewal_fee' => 'decimal:2',
        'validity_months' => 'integer',
        'required_documents' => 'array',
        'requires_inspection' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(LicenseApplication::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(BusinessLicense::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
