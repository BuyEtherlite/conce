<?php

namespace App\Modules\Housing\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousingMaintenanceRequest extends Model
{
    use HasFactory;

    protected $table = 'housing_maintenance_requests';

    protected $fillable = [
        'request_number',
        'property_id',
        'tenant_id',
        'request_type',
        'priority',
        'category',
        'description',
        'tenant_comments',
        'reported_date',
        'scheduled_date',
        'status',
        'assigned_to',
        'estimated_cost',
        'actual_cost',
        'work_description',
        'materials_used',
        'completion_date',
        'completion_notes',
        'satisfaction_rating',
        'tenant_feedback',
        'photos',
    ];

    protected $casts = [
        'reported_date' => 'date',
        'scheduled_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'materials_used' => 'json',
        'completion_date' => 'date',
        'photos' => 'json',
    ];

    public function property()
    {
        return $this->belongsTo(HousingProperty::class, 'property_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
