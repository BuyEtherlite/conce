<?php

namespace App\Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Council;
use App\Models\User;

class TownPlanningApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'council_id',
        'application_number',
        'applicant_name',
        'applicant_email',
        'applicant_phone',
        'applicant_address',
        'property_address',
        'property_erf_number',
        'property_size',
        'zoning_current',
        'zoning_proposed',
        'application_type',
        'proposal_description',
        'status',
        'submission_date',
        'review_deadline',
        'decision_date',
        'decision_reason',
        'conditions',
        'application_fee',
        'fee_paid',
        'fee_paid_date',
        'latitude',
        'longitude',
        'documents',
        'plans',
        'comments',
        'assigned_planner',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'submission_date' => 'date',
        'review_deadline' => 'date',
        'decision_date' => 'date',
        'fee_paid_date' => 'date',
        'property_size' => 'decimal:2',
        'application_fee' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'fee_paid' => 'boolean',
        'conditions' => 'array',
        'documents' => 'array',
        'plans' => 'array',
    ];

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function buildingInspections()
    {
        return $this->hasMany(BuildingInspection::class, 'planning_application_id');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'submitted' => 'primary',
            'under_review' => 'info',
            'public_participation' => 'warning',
            'internal_review' => 'secondary',
            'approved' => 'success',
            'rejected' => 'danger',
            'withdrawn' => 'dark',
            'conditional_approval' => 'warning',
            'appeal' => 'danger',
            default => 'secondary'
        };
    }

    public function getDaysInReviewAttribute()
    {
        return $this->submission_date->diffInDays(now());
    }

    public function getIsOverdueAttribute()
    {
        return $this->review_deadline && now() > $this->review_deadline && !$this->decision_date;
    }
}
