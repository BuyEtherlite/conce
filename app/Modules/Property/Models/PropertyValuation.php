<?php

namespace App\Modules\Property\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PropertyValuation extends Model
{
    use HasFactory;

    protected $fillable = [
        'valuation_number',
        'property_id',
        'valuation_date',
        'land_value',
        'building_value',
        'total_value',
        'valuation_method',
        'valuer_name',
        'valuer_license',
        'valuer_company',
        'effective_date',
        'expiry_date',
        'status',
        'remarks',
        'supporting_documents',
        'created_by'
    ];

    protected $casts = [
        'valuation_date' => 'date',
        'land_value' => 'decimal:2',
        'building_value' => 'decimal:2',
        'total_value' => 'decimal:2',
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'supporting_documents' => 'array'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCurrent($query)
    {
        return $query->where('effective_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($valuation) {
            if (empty($valuation->valuation_number)) {
                $valuation->valuation_number = 'VAL' . date('Y') . str_pad(self::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
