<?php

namespace App\Modules\Housing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StandAllocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'allocation_number',
        'stand_id',
        'applicant_name',
        'applicant_id_number',
        'applicant_contact',
        'applicant_email',
        'applicant_address',
        'intended_use',
        'business_type',
        'allocation_amount',
        'deposit_paid',
        'balance_due',
        'payment_plan',
        'installment_months',
        'monthly_installment',
        'allocation_date',
        'due_date',
        'completion_date',
        'status',
        'conditions',
        'required_documents',
        'submitted_documents',
        'approved_by',
        'approval_date',
        'rejection_reason'
    ];

    protected $casts = [
        'allocation_amount' => 'decimal:2',
        'deposit_paid' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'monthly_installment' => 'decimal:2',
        'allocation_date' => 'date',
        'due_date' => 'date',
        'completion_date' => 'date',
        'approval_date' => 'date',
        'required_documents' => 'array',
        'submitted_documents' => 'array'
    ];

    public function stand(): BelongsTo
    {
        return $this->belongsTo(HousingStand::class, 'stand_id');
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'allocated' => 'Allocated',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];

        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    public function getPaymentPlanDisplayAttribute()
    {
        $plans = [
            'full_payment' => 'Full Payment',
            'installments' => 'Installments',
            'lease' => 'Lease'
        ];

        return $plans[$this->payment_plan] ?? ucfirst($this->payment_plan);
    }

    public function getPaymentProgressAttribute()
    {
        if ($this->allocation_amount == 0) {
            return 0;
        }

        return round(($this->deposit_paid / $this->allocation_amount) * 100, 2);
    }

    public function getIsFullyPaidAttribute()
    {
        return $this->balance_due <= 0;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($allocation) {
            if (empty($allocation->allocation_number)) {
                $allocation->allocation_number = 'SA-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
