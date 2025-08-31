<?php

namespace App\Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Council;
use App\Models\User;

class BuildingInspection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'council_id',
        'planning_application_id',
        'inspection_number',
        'property_address',
        'property_owner',
        'contact_person',
        'contact_phone',
        'contact_email',
        'inspection_type',
        'inspection_purpose',
        'scheduled_date',
        'scheduled_time',
        'conducted_date',
        'conducted_time',
        'inspector_name',
        'status',
        'findings',
        'deficiencies',
        'recommendations',
        'conditions',
        'certificate_issued',
        'certificate_number',
        'certificate_expiry',
        'inspection_fee',
        'fee_paid',
        'photos',
        'documents',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'conducted_date' => 'date',
        'certificate_expiry' => 'date',
        'scheduled_time' => 'time',
        'conducted_time' => 'time',
        'inspection_fee' => 'decimal:2',
        'certificate_issued' => 'boolean',
        'fee_paid' => 'boolean',
        'deficiencies' => 'array',
        'photos' => 'array',
        'documents' => 'array',
    ];

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function planningApplication()
    {
        return $this->belongsTo(TownPlanningApplication::class, 'planning_application_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'scheduled' => 'primary',
            'in_progress' => 'info',
            'passed' => 'success',
            'failed' => 'danger',
            'conditional' => 'warning',
            'cancelled' => 'dark',
            'rescheduled' => 'secondary',
            default => 'secondary'
        };
    }

    public function getIsOverdueAttribute()
    {
        return $this->scheduled_date && now() > $this->scheduled_date && !$this->conducted_date;
    }

    public function getDaysUntilScheduledAttribute()
    {
        return $this->scheduled_date ? now()->diffInDays($this->scheduled_date, false) : null;
    }
}