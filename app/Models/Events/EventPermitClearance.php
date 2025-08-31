<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class EventPermitClearance extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_permit_id',
        'clearance_type',
        'status',
        'comments',
        'conditions',
        'cleared_at',
        'cleared_by',
    ];

    protected $casts = [
        'cleared_at' => 'datetime',
    ];

    public function eventPermit(): BelongsTo
    {
        return $this->belongsTo(EventPermit::class);
    }

    public function clearedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cleared_by');
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'not_required' => 'bg-secondary',
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getClearanceTypeDisplayAttribute(): string
    {
        $types = [
            'police' => 'Police Clearance',
            'fire' => 'Fire Safety Clearance',
            'health' => 'Health Department Clearance',
            'environment' => 'Environmental Clearance',
            'noise' => 'Noise Permit',
            'traffic' => 'Traffic Management',
            'safety' => 'Public Safety Clearance',
        ];

        return $types[$this->clearance_type] ?? ucfirst($this->clearance_type);
    }
}
