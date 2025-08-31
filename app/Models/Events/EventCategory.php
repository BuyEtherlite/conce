<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Council;

class EventCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'base_fee',
        'requirements',
        'requires_police_clearance',
        'requires_fire_clearance',
        'requires_health_clearance',
        'requires_noise_permit',
        'max_capacity',
        'processing_days',
        'is_active',
        'council_id',
    ];

    protected $casts = [
        'base_fee' => 'decimal:2',
        'requirements' => 'array',
        'requires_police_clearance' => 'boolean',
        'requires_fire_clearance' => 'boolean',
        'requires_health_clearance' => 'boolean',
        'requires_noise_permit' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function council(): BelongsTo
    {
        return $this->belongsTo(Council::class);
    }

    public function eventPermits(): HasMany
    {
        return $this->hasMany(EventPermit::class);
    }

    public function getRequiredClearancesAttribute(): array
    {
        $clearances = [];
        
        if ($this->requires_police_clearance) {
            $clearances[] = 'police';
        }
        if ($this->requires_fire_clearance) {
            $clearances[] = 'fire';
        }
        if ($this->requires_health_clearance) {
            $clearances[] = 'health';
        }
        if ($this->requires_noise_permit) {
            $clearances[] = 'noise';
        }

        return $clearances;
    }

    public function getRequirementsListAttribute(): string
    {
        return implode('; ', $this->requirements ?? []);
    }
}
