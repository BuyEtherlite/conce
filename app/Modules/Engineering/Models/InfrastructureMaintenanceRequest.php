<?php

namespace App\Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Council;
use App\Models\User;
use App\Models\AssetRegister;

class InfrastructureMaintenanceRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'council_id',
        'asset_id',
        'request_number',
        'title',
        'description',
        'type',
        'category',
        'priority',
        'status',
        'location',
        'latitude',
        'longitude',
        'reported_by',
        'reporter_contact',
        'reported_date',
        'required_by_date',
        'assigned_to',
        'assigned_date',
        'started_date',
        'completed_date',
        'estimated_cost',
        'actual_cost',
        'estimated_hours',
        'actual_hours',
        'work_performed',
        'materials_used',
        'photos_before',
        'photos_after',
        'documents',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'materials_used' => 'array',
        'photos_before' => 'array',
        'photos_after' => 'array',
        'documents' => 'array',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'reported_date' => 'date',
        'required_by_date' => 'date',
        'assigned_date' => 'date',
        'started_date' => 'date',
        'completed_date' => 'date'
    ];

    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    public function asset()
    {
        return $this->belongsTo(AssetRegister::class, 'asset_id');
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
        return $this->hasMany(EngineeringWorkOrder::class, 'maintenance_request_id');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'open' => 'danger',
            'assigned' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            'cancelled' => 'dark',
            'on_hold' => 'secondary',
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

    public function getDaysOpenAttribute()
    {
        return $this->reported_date->diffInDays(now());
    }

    public function getIsOverdueAttribute()
    {
        return $this->required_by_date && now() > $this->required_by_date && !$this->completed_date;
    }

    public function getResponseTimeAttribute()
    {
        return $this->assigned_date ? $this->reported_date->diffInDays($this->assigned_date) : null;
    }

    public function getResolutionTimeAttribute()
    {
        return $this->completed_date ? $this->reported_date->diffInDays($this->completed_date) : null;
    }
}