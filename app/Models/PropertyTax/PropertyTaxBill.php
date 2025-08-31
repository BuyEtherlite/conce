<?php

namespace App\Models\PropertyTax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyTaxBill extends Model
{
    use HasFactory;

    protected $table = 'property_tax_bills';

    protected $fillable = [
        'bill_number',
        'assessment_id',
        'bill_date',
        'due_date',
        'principal_amount',
        'interest_amount',
        'penalty_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'outstanding_amount',
        'status',
        'payment_due_date',
        'payment_schedule',
        'bill_notes'
    ];

    protected $casts = [
        'bill_date' => 'date',
        'due_date' => 'date',
        'payment_due_date' => 'date',
        'principal_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'outstanding_amount' => 'decimal:2',
        'payment_schedule' => 'array'
    ];

    public function assessment()
    {
        return $this->belongsTo(PropertyTaxAssessment::class, 'assessment_id');
    }

    public function payments()
    {
        return $this->hasMany(PropertyTaxPayment::class, 'bill_id');
    }

    public function complianceActions()
    {
        return $this->hasMany(PropertyTaxComplianceAction::class, 'bill_id');
    }

    public function scopeOverdue($query)
    {
        return $query->where('payment_due_date', '<', now())
                    ->where('status', '!=', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('status', '!=', 'paid');
    }

    public function getIsOverdueAttribute()
    {
        return $this->payment_due_date < now() && $this->status !== 'paid';
    }
}
