<?php

namespace App\Models\Cemetery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CemeteryMaintenance extends Model
{
    use HasFactory;

    protected $table = 'cemetery_maintenance';

    protected $fillable = [
        'title',
        'description',
        'scheduled_date',
        'completed_date',
        'status',
        'assigned_to',
        'estimated_cost',
        'actual_cost',
        'maintenance_notes',
        'priority',
        'maintenance_type',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
    ];

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'scheduled' => 'info',
            'in_progress' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'danger',
            'urgent' => 'dark',
            default => 'secondary'
        };
    }
}
