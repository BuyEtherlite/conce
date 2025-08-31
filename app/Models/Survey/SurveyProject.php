<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;
use App\Models\User;
use App\Models\Council;
use App\Models\Department;

class SurveyProject extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_number',
        'title',
        'description',
        'survey_type_id',
        'client_id',
        'surveyor_id',
        'council_id',
        'department_id',
        'property_address',
        'property_coordinates',
        'property_area',
        'property_boundaries',
        'priority',
        'status',
        'requested_date',
        'scheduled_date',
        'completed_date',
        'estimated_cost',
        'actual_cost',
        'special_requirements',
        'weather_conditions',
        'terrain_info',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'scheduled_date' => 'date',
        'completed_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'property_area' => 'decimal:2',
        'weather_conditions' => 'json',
        'terrain_info' => 'json',
    ];

    public function surveyType()
    {
        return $this->belongsTo(SurveyType::class);
    }

    public function client()
    {
        return $this->belongsTo(Customer::class, 'client_id');
    }

    public function surveyor()
    {
        return $this->belongsTo(User::class, 'surveyor_id');
    }

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function measurements()
    {
        return $this->hasMany(SurveyMeasurement::class);
    }

    public function documents()
    {
        return $this->hasMany(SurveyDocument::class);
    }

    public function reports()
    {
        return $this->hasMany(SurveyReport::class);
    }

    public function boundaries()
    {
        return $this->hasMany(SurveyBoundary::class);
    }

    public function fees()
    {
        return $this->hasMany(SurveyFee::class);
    }

    public function inspections()
    {
        return $this->hasMany(SurveyInspection::class);
    }

    public function communications()
    {
        return $this->hasMany(SurveyCommunication::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'info',
            'in_progress' => 'primary',
            'survey_complete' => 'success',
            'mapping_complete' => 'success',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
            'urgent' => 'danger',
            default => 'secondary',
        };
    }

    public function getTotalFeesAttribute()
    {
        return $this->fees()->sum('total_amount');
    }

    public function getPaidFeesAttribute()
    {
        return $this->fees()->where('status', 'paid')->sum('total_amount');
    }

    public function getOutstandingFeesAttribute()
    {
        return $this->total_fees - $this->paid_fees;
    }
}