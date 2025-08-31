<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetAnalysis extends Model
{
    use SoftDeletes;

    protected $table = 'finance_budget_analysis';

    protected $fillable = [
        'budget_id',
        'period',
        'actual_amount',
        'variance',
        'variance_percent',
        'notes',
        'analyzed_by',
        'analyzed_at'
    ];

    protected $casts = [
        'actual_amount' => 'decimal:2',
        'variance' => 'decimal:2',
        'variance_percent' => 'decimal:2',
        'analyzed_at' => 'datetime',
    ];

    protected $dates = [
        'analyzed_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relationships
    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function analyzedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'analyzed_by');
    }

    // Accessors
    public function getVarianceStatusAttribute()
    {
        $variancePercent = abs($this->variance_percent);
        
        if ($variancePercent <= 5) {
            return 'on-track';
        } elseif ($variancePercent <= 15) {
            return 'warning';
        } else {
            return 'critical';
        }
    }

    public function getVarianceStatusClassAttribute()
    {
        return match($this->variance_status) {
            'on-track' => 'text-success',
            'warning' => 'text-warning',
            'critical' => 'text-danger',
            default => 'text-muted'
        };
    }

    // Scopes
    public function scopeForPeriod($query, $period)
    {
        return $query->where('period', $period);
    }

    public function scopeOverBudget($query)
    {
        return $query->where('variance', '>', 0);
    }

    public function scopeUnderBudget($query)
    {
        return $query->where('variance', '<', 0);
    }

    public function scopeCriticalVariance($query, $threshold = 15)
    {
        return $query->where('variance_percent', '>', $threshold)
                    ->orWhere('variance_percent', '<', -$threshold);
    }
}