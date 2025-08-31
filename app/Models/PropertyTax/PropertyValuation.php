<?php

namespace App\Models\PropertyTax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PropertyValuation extends Model
{
    use HasFactory;

    protected $table = 'property_valuations';

    protected $fillable = [
        'property_id',
        'valuation_date',
        'land_value',
        'building_value',
        'total_value',
        'valuation_method',
        'valuer_name',
        'valuer_license',
        'valuation_details',
        'remarks',
        'created_by'
    ];

    protected $casts = [
        'land_value' => 'decimal:2',
        'building_value' => 'decimal:2',
        'total_value' => 'decimal:2',
        'valuation_date' => 'date'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assessments()
    {
        return $this->hasMany(PropertyTaxAssessment::class, 'valuation_id');
    }
}