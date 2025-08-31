<?php

namespace App\Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Council;
use App\Models\Department;
use App\Models\User;

class AssetRegister extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'asset_register';

    protected $fillable = [
        'uuid',
        'council_id',
        'department_id',
        'asset_number',
        'name',
        'description',
        'category',
        'type',
        'location',
        'latitude',
        'longitude',
        'acquisition_date',
        'acquisition_cost',
        'current_value',
        'depreciation_rate',
        'useful_life_years',
        'condition',
        'last_inspection_date',
        'next_inspection_due',
        'last_maintenance_date',
        'next_maintenance_due',
        'warranty_provider',
        'warranty_expiry',
        'supplier',
        'manufacturer',
        'model',
        'serial_number',
        'status',
        'disposal_reason',
        'disposal_date',
        'disposal_value',
        'documents',
        'photos',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'acquisition_date' => 'date',
        'last_inspection_date' => 'date',
        'next_inspection_due' => 'date',
        'last_maintenance_date' => 'date',
        'next_maintenance_due' => 'date',
        'warranty_expiry' => 'date',
        'disposal_date' => 'date',
        'acquisition_cost' => 'decimal:2',
        'current_value' => 'decimal:2',
        'depreciation_rate' => 'decimal:2',
        'disposal_value' => 'decimal:2',
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

    public function maintenanceRequests()
    {
        return $this->hasMany(InfrastructureMaintenanceRequest::class, 'asset_id');
    }

    public function getConditionColorAttribute()
    {
        return match($this->condition) {
            'excellent' => 'success',
            'good' => 'primary',
            'fair' => 'warning',
            'poor' => 'danger',
            'critical' => 'dark',
            default => 'secondary'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'success',
            'inactive' => 'secondary',
            'under_maintenance' => 'warning',
            'disposed' => 'dark',
            'stolen' => 'danger',
            'damaged' => 'danger',
            default => 'secondary'
        };
    }

    public function getDepreciatedValueAttribute()
    {
        if (!$this->acquisition_cost || !$this->depreciation_rate || !$this->acquisition_date) {
            return $this->current_value ?? $this->acquisition_cost;
        }

        $yearsElapsed = $this->acquisition_date->diffInYears(now());
        $totalDepreciation = ($this->acquisition_cost * $this->depreciation_rate / 100) * $yearsElapsed;
        
        return max(0, $this->acquisition_cost - $totalDepreciation);
    }

    public function getIsMaintenanceOverdueAttribute()
    {
        return $this->next_maintenance_due && now() > $this->next_maintenance_due;
    }

    public function getIsInspectionOverdueAttribute()
    {
        return $this->next_inspection_due && now() > $this->next_inspection_due;
    }

    public function getAgeInYearsAttribute()
    {
        return $this->acquisition_date ? $this->acquisition_date->diffInYears(now()) : null;
    }
}