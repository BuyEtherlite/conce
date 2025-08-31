<?php

namespace App\Modules\Licensing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShopPermitType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'base_fee',
        'validity_months',
        'required_documents',
        'requirements',
        'requires_inspection',
        'is_active',
    ];

    protected $casts = [
        'base_fee' => 'decimal:2',
        'required_documents' => 'array',
        'requirements' => 'array',
        'requires_inspection' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(ShopPermitApplication::class);
    }

    public function permits(): HasMany
    {
        return $this->hasMany(ShopPermit::class);
    }

    public function getRequiredDocumentsListAttribute(): string
    {
        return implode(', ', $this->required_documents ?? []);
    }

    public function getRequirementsListAttribute(): string
    {
        return implode('; ', $this->requirements ?? []);
    }
}
