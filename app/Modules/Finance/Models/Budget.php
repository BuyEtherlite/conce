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
