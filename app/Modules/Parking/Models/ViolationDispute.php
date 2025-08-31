<?php

namespace App\Modules\Parking\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViolationDispute extends Model
{
    protected $fillable = [
        'violation_id',
        'dispute_reason',
        'dispute_description',
        'supporting_evidence',
        'status',
        'submitted_by',
        'reviewed_by',
        'resolution',
        'resolved_at'
    ];

    protected $casts = [
        'supporting_evidence' => 'array',
        'resolved_at' => 'datetime'
    ];

    public function violation(): BelongsTo
    {
        return $this->belongsTo(ParkingViolation::class, 'violation_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeResolved($query)
    {
        return $query->whereIn('status', ['approved', 'rejected']);
    }

    public function getDisputeReasonLabel(): string
    {
        $labels = [
            'vehicle_not_present' => 'Vehicle Was Not Present',
            'valid_permit' => 'Valid Permit Available',
            'meter_malfunction' => 'Parking Meter Malfunction',
            'incorrect_location' => 'Incorrect Location',
            'emergency_situation' => 'Emergency Situation',
            'other' => 'Other'
        ];

        return $labels[$this->dispute_reason] ?? $this->dispute_reason;
    }
}