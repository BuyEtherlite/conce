<?php

namespace App\Models\PropertyTax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PropertyTaxAssessment extends Model
{
    use HasFactory;

    protected $table = 'property_tax_assessments';

    protected $fillable = [
        'assessment_number',
        'valuation_id',
        'tax_year',
        'taxable_value',
        'tax_rate',
        'base_tax_amount',
        'zone_adjustment',
        'exemptions_amount',
        'penalties_amount',
        'interest_amount',
        'total_tax_amount',
        'assessment_date',
        'due_date',
        'status',
        'assessment_notes',
        'assessed_by'
    ];

    protected $casts = [
        'taxable_value' => 'decimal:2',
        'tax_rate' => 'decimal:4',
        'base_tax_amount' => 'decimal:2',
        'zone_adjustment' => 'decimal:2',
        'exemptions_amount' => 'decimal:2',
        'penalties_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'total_tax_amount' => 'decimal:2',
        'assessment_date' => 'date',
        'due_date' => 'date'
    ];

    public function valuation()
    {
        return $this->belongsTo(PropertyValuation::class, 'valuation_id');
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    public function bills()
    {
        return $this->hasMany(PropertyTaxBill::class, 'assessment_id');
    }

    public function appeals()
    {
        return $this->hasMany(PropertyTaxAppeal::class, 'assessment_id');
    }

    public function calculateTotalTax()
    {
        return $this->base_tax_amount + $this->zone_adjustment + $this->penalties_amount + $this->interest_amount - $this->exemptions_amount;
    }
}
