<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventPermitFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_permit_id',
        'fee_type',
        'description',
        'amount',
        'is_paid',
        'due_date',
        'paid_at',
        'payment_method',
        'reference_number',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_paid' => 'boolean',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function eventPermit(): BelongsTo
    {
        return $this->belongsTo(EventPermit::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        if ($this->is_paid) {
            return 'bg-success';
        }
        
        if ($this->due_date && $this->due_date < now()->toDateString()) {
            return 'bg-danger';
        }
        
        return 'bg-warning';
    }

    public function getStatusTextAttribute(): string
    {
        if ($this->is_paid) {
            return 'Paid';
        }
        
        if ($this->due_date && $this->due_date < now()->toDateString()) {
            return 'Overdue';
        }
        
        return 'Pending';
    }
}
