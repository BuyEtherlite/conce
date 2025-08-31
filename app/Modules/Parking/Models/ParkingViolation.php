<?php

namespace App\Modules\Parking\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ParkingViolation extends Model
{
    protected $fillable = [
        'violation_number',
        'zone_id',
        'space_id',
        'vehicle_registration',
        'vehicle_make',
        'vehicle_model',
        'vehicle_color',
        'violation_type',
        'violation_description',
        'location',
        'fine_amount',
        'violation_datetime',
        'noticed_datetime',
        'issued_by',
        'evidence',
        'status',
        'due_date',
        'amount_paid',
        'payment_date',
        'payment_reference',
        'notes'
    ];

    protected $casts = [
        'location' => 'array',
        'evidence' => 'array',
        'fine_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'violation_datetime' => 'datetime',
        'noticed_datetime' => 'datetime',
        'payment_date' => 'datetime',
        'due_date' => 'date'
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(ParkingZone::class, 'zone_id');
    }

    public function space(): BelongsTo
    {
        return $this->belongsTo(ParkingSpace::class, 'space_id');
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(ViolationPayment::class, 'violation_id');
    }

    public function dispute(): HasOne
    {
        return $this->hasOne(ViolationDispute::class, 'violation_id');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'paid')
                    ->where('status', '!=', 'cancelled')
                    ->where('due_date', '<', now());
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['issued', 'overdue']);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isOverdue(): bool
    {
        return !$this->isPaid() && $this->due_date < now();
    }

    public function getRemainingBalance(): float
    {
        return $this->fine_amount - $this->amount_paid;
    }

    public function getViolationTypeLabel(): string
    {
        $labels = [
            'overtime_parking' => 'Overtime Parking',
            'no_permit' => 'No Permit',
            'invalid_permit' => 'Invalid Permit',
            'disabled_space' => 'Disabled Space Violation',
            'loading_zone' => 'Loading Zone Violation',
            'fire_hydrant' => 'Fire Hydrant Violation',
            'no_parking_zone' => 'No Parking Zone',
            'expired_meter' => 'Expired Meter',
            'blocking_driveway' => 'Blocking Driveway',
            'double_parking' => 'Double Parking',
            'wrong_direction' => 'Wrong Direction Parking'
        ];

        return $labels[$this->violation_type] ?? $this->violation_type;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($violation) {
            if (!$violation->violation_number) {
                $violation->violation_number = 'VN-' . date('Y') . '-' . str_pad(
                    static::whereYear('created_at', date('Y'))->count() + 1,
                    6,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }
}