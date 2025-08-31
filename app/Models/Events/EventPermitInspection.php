<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class EventPermitInspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_permit_id',
        'inspection_type',
        'scheduled_date',
        'scheduled_time',
        'status',
        'findings',
        'recommendations',
        'passed',
        'notes',
        'inspector_id',
        'scheduled_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime:H:i',
        'passed' => 'boolean',
    ];

    public function eventPermit(): BelongsTo
    {
        return $this->belongsTo(EventPermit::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function scheduledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'scheduled' => 'bg-warning',
            'completed' => 'bg-success',
            'failed' => 'bg-danger',
            'cancelled' => 'bg-secondary',
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getFormattedScheduleAttribute(): string
    {
        return $this->scheduled_date->format('d M Y') . ' at ' . $this->scheduled_time->format('H:i');
    }
}
