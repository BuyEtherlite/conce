<?php

namespace App\Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Council;
use App\Models\User;

class RoadNetwork extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'road_network';

    protected $fillable = [
        'uuid',
        'council_id',
        'road_number',
        'road_name',
        'road_type',
        'surface_type',
        'length_km',
        'width_m',
        'lanes',
        'condition',
        'last_assessment_date',
        'construction_date',
        'last_maintenance_date',
        'next_maintenance_due',
        'maintenance_cost_per_km',
        'start_point',
        'end_point',
        'coordinates',
        'has_streetlights',
        'has_sidewalks',
        'has_drainage',
        'speed_limit',
        'traffic_volume',
        'description',
        'photos',
        'documents',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'last_assessment_date' => 'date',
        'construction_date' => 'date',
        'last_maintenance_date' => 'date',
        'next_maintenance_due' => 'date',
        'length_km' => 'decimal:3',
        'width_m' => 'decimal:2',
        'maintenance_cost_per_km' => 'decimal:2',
        'has_streetlights' => 'boolean',
        'has_sidewalks' => 'boolean',
        'has_drainage' => 'boolean',
        'coordinates' => 'array',
        'photos' => 'array',
        'documents' => 'array',
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

    public function getAgeInYearsAttribute()
    {
        return $this->construction_date ? $this->construction_date->diffInYears(now()) : null;
    }

    public function getIsMaintenanceOverdueAttribute()
    {
        return $this->next_maintenance_due && now() > $this->next_maintenance_due;
    }

    public function getEstimatedMaintenanceCostAttribute()
    {
        return $this->maintenance_cost_per_km ? $this->length_km * $this->maintenance_cost_per_km : null;
    }

    public function getTrafficVolumeColorAttribute()
    {
        return match($this->traffic_volume) {
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'success',
            default => 'secondary'
        };
    }
}