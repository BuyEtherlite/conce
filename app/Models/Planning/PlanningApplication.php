<?php

namespace App\Models\Planning;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Council;

class PlanningApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'application_number',
        'applicant_name',
        'applicant_email',
        'applicant_phone',
        'applicant_address',
        'property_address',
        'property_erf_number',
        'application_type',
        'development_description',
        'proposed_use',
        'property_size',
        'building_coverage',
        'floor_area',
        'number_of_units',
        'parking_spaces',
        'estimated_cost',
        'date_submitted',
        'target_decision_date',
        'actual_decision_date',
        'status',
        'conditions',
        'rejection_reason',
        'decision_notes',
        'documents_path',
        'site_plan_path',
        'architectural_plans_path',
        'assigned_officer',
        'council_id',
        'comments',
        'reviewed_by'
    ];

    protected $casts = [
        'date_submitted' => 'date',
        'target_decision_date' => 'date',
        'actual_decision_date' => 'date',
        'conditions' => 'array',
        'property_size' => 'decimal:2',
        'building_coverage' => 'decimal:2',
        'floor_area' => 'decimal:2',
        'estimated_cost' => 'decimal:2',
    ];

    protected $dates = ['deleted_at'];

    public static function generateApplicationNumber()
    {
        $year = now()->format('Y');
        $sequence = self::whereYear('created_at', $year)->count() + 1;
        return 'PLA-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function assignedOfficer()
    {
        return $this->belongsTo(User::class, 'assigned_officer');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'submitted' => 'bg-secondary',
            'under_review' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'conditional_approval' => 'bg-info',
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }

    public function getFormattedEstimatedCostAttribute()
    {
        return $this->estimated_cost ? 'R' . number_format($this->estimated_cost, 2) : 'N/A';
    }
}