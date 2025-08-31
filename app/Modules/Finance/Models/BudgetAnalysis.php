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
