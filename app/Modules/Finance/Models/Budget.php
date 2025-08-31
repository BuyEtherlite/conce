<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $table = 'finance_budgets';

    protected $fillable = [
        'budget_name',
        'financial_year',
        'account_id',
        'budgeted_amount',
        'actual_amount',
        'variance',
        'period',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'budgeted_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'variance' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    public function getUtilizationPercentageAttribute()
    {
        if ($this->budgeted_amount == 0) {
            return 0;
        }

        return ($this->actual_amount / $this->budgeted_amount) * 100;
    }

    public function isActive()
    {
        return $this->status === 'active' && 
               now()->between($this->start_date, $this->end_date);
    }

    public static function getPeriods()
    {
        return [
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'annually' => 'Annually'
        ];
    }
}
<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    use SoftDeletes;

    protected $table = 'finance_budgets';
    
    protected $fillable = [
        'budget_name',
        'financial_year',
        'account_id',
        'budgeted_amount',
        'actual_amount',
        'variance',
        'period',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budgeted_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'variance' => 'decimal:2'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    public function analysis(): HasMany
    {
        return $this->hasMany(BudgetAnalysis::class);
    }

    public function getTotalBudgetAttribute()
    {
        return $this->budgeted_amount;
    }

    public function getTotalSpentAttribute()
    {
        return $this->actual_amount;
    }

    public function getVariancePercentageAttribute()
    {
        if ($this->budgeted_amount > 0) {
            return (($this->actual_amount - $this->budgeted_amount) / $this->budgeted_amount) * 100;
        }
        return 0;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForYear($query, $year)
    {
        return $query->where('financial_year', $year);
    }
}
<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Budget extends Model
{
    use SoftDeletes;

    protected $table = 'finance_budgets';

    protected $fillable = [
        'budget_name',
        'financial_year',
        'account_id',
        'budgeted_amount',
        'actual_amount',
        'variance',
        'period',
        'start_date',
        'end_date',
        'status',
        'description',
        'created_by',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'budgeted_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'variance' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'approved_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relationships
    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    public function analysis(): HasMany
    {
        return $this->hasMany(BudgetAnalysis::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    // Accessors & Mutators
    public function getVariancePercentAttribute()
    {
        if ($this->budgeted_amount > 0) {
            return round(($this->variance / $this->budgeted_amount) * 100, 2);
        }
        return 0;
    }

    public function getUtilizationRateAttribute()
    {
        if ($this->budgeted_amount > 0) {
            return round(($this->actual_amount / $this->budgeted_amount) * 100, 2);
        }
        return 0;
    }

    public function getRemainingBudgetAttribute()
    {
        return $this->budgeted_amount - $this->actual_amount;
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'draft' => 'badge-secondary',
            'pending' => 'badge-warning',
            'approved' => 'badge-success',
            'active' => 'badge-primary',
            'completed' => 'badge-info',
            'cancelled' => 'badge-danger',
            default => 'badge-light'
        };
    }

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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForYear($query, $year)
    {
        return $query->where('financial_year', $year);
    }

    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
    }

    public function scopeOverBudget($query)
    {
        return $query->where('actual_amount', '>', 'budgeted_amount');
    }

    public function scopeUnderBudget($query)
    {
        return $query->where('actual_amount', '<', 'budgeted_amount');
    }

    // Methods
    public function calculateVariance()
    {
        $this->variance = $this->actual_amount - $this->budgeted_amount;
        $this->save();
        
        return $this->variance;
    }

    public function updateActualAmount($amount)
    {
        $this->actual_amount = $amount;
        $this->calculateVariance();
        
        return $this;
    }

    public function approve($userId = null)
    {
        $this->status = 'approved';
        $this->approved_by = $userId ?? auth()->id();
        $this->approved_at = now();
        $this->save();
        
        return $this;
    }

    public function activate()
    {
        // Deactivate other budgets for the same period
        static::where('financial_year', $this->financial_year)
              ->where('id', '!=', $this->id)
              ->update(['status' => 'inactive']);
              
        $this->status = 'active';
        $this->save();
        
        return $this;
    }

    public function isEditable()
    {
        return in_array($this->status, ['draft', 'pending']);
    }

    public function isDeletable()
    {
        return in_array($this->status, ['draft', 'cancelled']);
    }

    public function canBeApproved()
    {
        return $this->status === 'pending';
    }

    public function generateBudgetReport()
    {
        return [
            'budget_name' => $this->budget_name,
            'financial_year' => $this->financial_year,
            'account' => $this->account->name ?? 'Unknown Account',
            'budgeted_amount' => $this->budgeted_amount,
            'actual_amount' => $this->actual_amount,
            'variance' => $this->variance,
            'variance_percent' => $this->variance_percent,
            'utilization_rate' => $this->utilization_rate,
            'remaining_budget' => $this->remaining_budget,
            'status' => $this->status,
            'period' => $this->period,
            'start_date' => $this->start_date->format('Y-m-d'),
            'end_date' => $this->end_date->format('Y-m-d'),
        ];
    }
}
