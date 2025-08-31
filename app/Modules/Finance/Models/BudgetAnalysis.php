<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetAnalysis extends Model
{
    use HasFactory;

    protected $table = 'budget_analysis';

    protected $fillable = [
        'budget_id',
        'budgeted_amount',
        'actual_amount',
        'variance',
        'variance_percentage',
        'analysis_date',
        'analysis_notes'
    ];

    protected $casts = [
        'budgeted_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'variance' => 'decimal:2',
        'variance_percentage' => 'decimal:2',
        'analysis_date' => 'date'
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function calculateVariance()
    {
        $this->variance = $this->actual_amount - $this->budgeted_amount;
        $this->variance_percentage = $this->budgeted_amount > 0 
            ? ($this->variance / $this->budgeted_amount) * 100 
            : 0;
    }

    public function getVarianceStatusAttribute()
    {
        if ($this->variance > 0) {
            return 'over_budget';
        } elseif ($this->variance < 0) {
            return 'under_budget';
        }
        return 'on_budget';
    }
}
<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetAnalysis extends Model
{
    protected $table = 'finance_budget_analysis';
    
    protected $fillable = [
        'budget_id',
        'period',
        'actual_amount',
        'variance',
        'variance_percentage',
        'analysis_date'
    ];

    protected $casts = [
        'actual_amount' => 'decimal:2',
        'variance' => 'decimal:2',
        'variance_percentage' => 'decimal:2',
        'analysis_date' => 'date'
    ];

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }
}
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
