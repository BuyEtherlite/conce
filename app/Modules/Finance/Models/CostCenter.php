<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CostCenter extends Model
{
    use SoftDeletes;

    protected $table = 'cost_centers';

    protected $fillable = [
        'code',
        'name',
        'description',
        'parent_id',
        'manager_id',
        'department_id',
        'budget_allocated',
        'budget_used',
        'budget_remaining',
        'type',
        'status',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'budget_allocated' => 'decimal:2',
        'budget_used' => 'decimal:2',
        'budget_remaining' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'manager_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Department::class, 'department_id');
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class, 'cost_center_id');
    }

    // Accessors
    public function getBudgetUtilizationAttribute()
    {
        if ($this->budget_allocated > 0) {
            return round(($this->budget_used / $this->budget_allocated) * 100, 2);
        }
        return 0;
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'active' => 'badge-success',
            'inactive' => 'badge-secondary',
            'over_budget' => 'badge-danger',
            'under_budget' => 'badge-info',
            default => 'badge-light'
        };
    }

    public function getIsOverBudgetAttribute()
    {
        return $this->budget_used > $this->budget_allocated;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeChildren($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeOverBudget($query)
    {
        return $query->whereRaw('budget_used > budget_allocated');
    }

    // Methods
    public function updateBudgetUsage()
    {
        $this->budget_used = $this->budgets()->sum('actual_amount');
        $this->budget_remaining = $this->budget_allocated - $this->budget_used;
        
        // Update status based on budget usage
        if ($this->budget_used > $this->budget_allocated) {
            $this->status = 'over_budget';
        } elseif ($this->budget_used < ($this->budget_allocated * 0.8)) {
            $this->status = 'under_budget';
        } else {
            $this->status = 'active';
        }
        
        $this->save();
        
        return $this;
    }

    public function allocateBudget($amount, $description = null)
    {
        $this->budget_allocated = $amount;
        $this->budget_remaining = $amount - $this->budget_used;
        $this->save();
        
        return $this;
    }

    public function generateCode()
    {
        $year = date('Y');
        $prefix = 'CC';
        
        $lastCenter = static::whereRaw('code LIKE ?', ["{$prefix}{$year}%"])
                           ->orderBy('code', 'desc')
                           ->first();

        if ($lastCenter) {
            $lastNumber = intval(substr($lastCenter->code, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
