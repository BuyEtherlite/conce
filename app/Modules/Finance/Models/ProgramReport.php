<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramReport extends Model
{
    use SoftDeletes;

    protected $table = 'program_reports';

    protected $fillable = [
        'report_name',
        'program_name',
        'report_type',
        'period_start',
        'period_end',
        'total_budget',
        'total_spent',
        'total_remaining',
        'variance',
        'status',
        'description',
        'data',
        'generated_by',
        'generated_at'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_budget' => 'decimal:2',
        'total_spent' => 'decimal:2',
        'total_remaining' => 'decimal:2',
        'variance' => 'decimal:2',
        'data' => 'array',
        'generated_at' => 'datetime'
    ];

    // Relationships
    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'generated_by');
    }

    // Accessors
    public function getVariancePercentAttribute()
    {
        if ($this->total_budget > 0) {
            return round(($this->variance / $this->total_budget) * 100, 2);
        }
        return 0;
    }

    public function getUtilizationRateAttribute()
    {
        if ($this->total_budget > 0) {
            return round(($this->total_spent / $this->total_budget) * 100, 2);
        }
        return 0;
    }

    // Scopes
    public function scopeForPeriod($query, $start, $end)
    {
        return $query->whereBetween('period_start', [$start, $end])
                    ->orWhereBetween('period_end', [$start, $end]);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('report_type', $type);
    }

    // Methods
    public function generateSummary()
    {
        return [
            'total_budget' => $this->total_budget,
            'total_spent' => $this->total_spent,
            'total_remaining' => $this->total_remaining,
            'variance' => $this->variance,
            'variance_percent' => $this->variance_percent,
            'utilization_rate' => $this->utilization_rate,
            'status' => $this->status
        ];
    }
}