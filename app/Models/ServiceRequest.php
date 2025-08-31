<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceRequest extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_SUBMITTED = 'submitted';
    const STATUS_ACKNOWLEDGED = 'acknowledged';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_ON_HOLD = 'on_hold';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CLOSED = 'closed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'request_number',
        'customer_id',
        'service_type_id',
        'department_id',
        'title',
        'description',
        'location_address',
        'latitude',
        'longitude',
        'ward_number',
        'priority',
        'status',
        'contact_phone',
        'contact_email',
        'is_emergency',
        'source',
        'requested_date',
        'expected_completion_date',
        'actual_completion_date',
        'assigned_to',
        'assigned_team_id',
        'estimated_cost',
        'actual_cost',
        'resolution_notes',
        'satisfaction_rating',
        'satisfaction_feedback'
    ];

    protected $casts = [
        'is_emergency' => 'boolean',
        'requested_date' => 'datetime',
        'expected_completion_date' => 'datetime',
        'actual_completion_date' => 'datetime',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'satisfaction_rating' => 'integer'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedTeam()
    {
        return $this->belongsTo(ServiceTeam::class, 'assigned_team_id');
    }

    public function attachments()
    {
        return $this->hasMany(ServiceRequestAttachment::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(ServiceRequestStatusHistory::class);
    }

    public function updates()
    {
        return $this->hasMany(ServiceRequestUpdate::class);
    }

    public function escalations()
    {
        return $this->hasMany(ServiceRequestEscalation::class);
    }

    public function scopeOpen($query)
    {
        return $query->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_CLOSED, self::STATUS_CANCELLED]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('expected_completion_date', '<', now())
                    ->whereNotIn('status', [self::STATUS_COMPLETED, self::STATUS_CLOSED, self::STATUS_CANCELLED]);
    }

    public function getPriorityBadgeColorAttribute()
    {
        return [
            'low' => 'secondary',
            'medium' => 'info',
            'high' => 'warning',
            'urgent' => 'orange',
            'emergency' => 'danger'
        ][$this->priority] ?? 'secondary';
    }

    public function getStatusBadgeColorAttribute()
    {
        return [
            'submitted' => 'warning',
            'acknowledged' => 'info',
            'assigned' => 'primary',
            'in_progress' => 'primary',
            'on_hold' => 'secondary',
            'completed' => 'success',
            'closed' => 'dark',
            'cancelled' => 'danger'
        ][$this->status] ?? 'secondary';
    }
}