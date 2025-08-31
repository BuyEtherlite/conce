<?php

namespace App\Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Council;
use App\Models\Department;
use App\Models\User;

class EngineeringProject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'council_id',
        'department_id',
        'project_number',
        'name',
        'description',
        'type',
        'category',
        'priority',
        'status',
        'estimated_cost',
        'actual_cost',
        'approved_budget',
        'planned_start_date',
        'planned_completion_date',
        'actual_start_date',
        'actual_completion_date',
        'project_manager',
        'contractor',
        'consultant',
        'location_description',
        'latitude',
        'longitude',
        'documents',
        'photos',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'planned_start_date' => 'date',
        'planned_completion_date' => 'date',
        'actual_start_date' => 'date',
        'actual_completion_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'approved_budget' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'documents' => 'array',
        'photos' => 'array',
    ];

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function workOrders()
    {
        return $this->hasMany(EngineeringWorkOrder::class, 'project_id');
    }

    public function getProgressPercentageAttribute()
    {
        if (!$this->planned_start_date || !$this->planned_completion_date) {
            return 0;
        }

        $totalDays = $this->planned_start_date->diffInDays($this->planned_completion_date);
        if ($totalDays == 0) return 100;

        $today = now();
        if ($today < $this->planned_start_date) return 0;
        if ($today > $this->planned_completion_date) return 100;

        $elapsedDays = $this->planned_start_date->diffInDays($today);
        return min(100, ($elapsedDays / $totalDays) * 100);
    }

    public function getBudgetVarianceAttribute()
    {
        if (!$this->approved_budget || !$this->actual_cost) {
            return null;
        }

        return $this->actual_cost - $this->approved_budget;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'planning' => 'info',
            'design' => 'warning',
            'approval' => 'secondary',
            'tendering' => 'primary',
            'construction' => 'warning',
            'completed' => 'success',
            'suspended' => 'danger',
            'cancelled' => 'dark',
            default => 'secondary'
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'critical' => 'danger',
            'high' => 'warning',
            'medium' => 'info',
            'low' => 'secondary',
            default => 'secondary'
        };
    }
}