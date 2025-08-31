<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class EventPermitCommunication extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_permit_id',
        'type',
        'subject',
        'message',
        'direction',
        'sent_at',
        'is_read',
        'sent_by',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    public function eventPermit(): BelongsTo
    {
        return $this->belongsTo(EventPermit::class);
    }

    public function sentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function getTypeDisplayAttribute(): string
    {
        $types = [
            'email' => 'Email',
            'sms' => 'SMS',
            'letter' => 'Letter',
            'meeting' => 'Meeting',
            'phone' => 'Phone Call',
        ];

        return $types[$this->type] ?? ucfirst($this->type);
    }

    public function getDirectionBadgeAttribute(): string
    {
        return $this->direction === 'outgoing' ? 'bg-primary' : 'bg-success';
    }
}
