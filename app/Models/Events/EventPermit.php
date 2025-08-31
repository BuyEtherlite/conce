<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Council;
use Carbon\Carbon;

class EventPermit extends Model
{
    use HasFactory;

    protected $fillable = [
        'permit_number',
        'event_category_id',
        'event_name',
        'event_description',
        'organizer_name',
        'organizer_email',
        'organizer_phone',
        'organizer_address',
        'contact_person',
        'contact_phone',
        'contact_email',
        'event_date',
        'start_time',
        'end_time',
        'venue',
        'venue_address',
        'expected_attendance',
        'special_requirements',
        'equipment_needed',
        'alcohol_service',
        'food_service',
        'amplified_sound',
        'permit_fee',
        'additional_fees',
        'security_deposit',
        'total_amount',
        'status',
        'submitted_at',
        'approved_at',
        'rejected_at',
        'approved_by',
        'rejected_by',
        'approval_conditions',
        'rejection_reason',
        'fee_paid',
        'payment_due_date',
        'payment_received_at',
        'council_id',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'permit_fee' => 'decimal:2',
        'additional_fees' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'alcohol_service' => 'boolean',
        'food_service' => 'boolean',
        'amplified_sound' => 'boolean',
        'fee_paid' => 'boolean',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'payment_due_date' => 'date',
        'payment_received_at' => 'datetime',
    ];

    public function eventCategory(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class);
    }

    public function council(): BelongsTo
    {
        return $this->belongsTo(Council::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EventPermitDocument::class);
    }

    public function clearances(): HasMany
    {
        return $this->hasMany(EventPermitClearance::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(EventPermitInspection::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(EventPermitFee::class);
    }

    public function communications(): HasMany
    {
        return $this->hasMany(EventPermitCommunication::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'bg-warning',
            'under_review' => 'bg-info',
            'requires_inspection' => 'bg-secondary',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'cancelled' => 'bg-dark',
            'expired' => 'bg-danger',
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getDaysToEventAttribute(): int
    {
        return now()->diffInDays($this->event_date, false);
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->days_to_event >= 0 && $this->days_to_event <= 30;
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->event_date < now()->toDateString() && $this->status === 'approved';
    }

    public function getFormattedEventTimeAttribute(): string
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }

    public function getOutstandingAmountAttribute(): float
    {
        if ($this->fee_paid) {
            return 0;
        }
        
        return $this->total_amount - $this->fees()->where('is_paid', true)->sum('amount');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($permit) {
            if (!$permit->permit_number) {
                $permit->permit_number = 'EP-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
            
            if (!$permit->submitted_at) {
                $permit->submitted_at = now();
            }
            
            // Calculate payment due date (7 days from submission)
            if (!$permit->payment_due_date) {
                $permit->payment_due_date = now()->addDays(7)->toDateString();
            }
        });

        static::updating(function ($permit) {
            // Auto-expire permits after event date
            if ($permit->event_date < now()->toDateString() && $permit->status === 'approved') {
                $permit->status = 'expired';
            }
        });
    }
}
