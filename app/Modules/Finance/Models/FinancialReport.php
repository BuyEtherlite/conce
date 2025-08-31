<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialReport extends Model
{
    use SoftDeletes;

    protected $table = 'financial_reports';

    protected $fillable = [
        'report_name',
        'report_type',
        'period_start',
        'period_end',
        'status',
        'data',
        'summary',
        'total_revenue',
        'total_expenses',
        'net_income',
        'generated_by',
        'generated_at',
        'approved_by',
        'approved_at',
        'description',
        'parameters'
    ];

    protected $dates = [
        'period_start',
        'period_end',
        'generated_at',
        'approved_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'generated_at' => 'datetime',
        'approved_at' => 'datetime',
        'total_revenue' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'net_income' => 'decimal:2',
        'data' => 'array',
        'summary' => 'array',
        'parameters' => 'array'
    ];

    // Relationships
    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'generated_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    // Accessors
    public function getReportTypeDisplayAttribute()
    {
        $types = [
            'income_statement' => 'Income Statement',
            'balance_sheet' => 'Balance Sheet',
            'cash_flow' => 'Cash Flow Statement',
            'budget_variance' => 'Budget Variance Report',
            'trial_balance' => 'Trial Balance',
            'general_ledger' => 'General Ledger Report',
            'accounts_receivable' => 'Accounts Receivable Report',
            'accounts_payable' => 'Accounts Payable Report',
            'custom' => 'Custom Report'
        ];

        return $types[$this->report_type] ?? ucfirst(str_replace('_', ' ', $this->report_type));
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'draft' => 'badge-secondary',
            'generating' => 'badge-warning',
            'completed' => 'badge-success',
            'approved' => 'badge-primary',
            'failed' => 'badge-danger',
            default => 'badge-light'
        };
    }

    public function getProfitMarginAttribute()
    {
        if ($this->total_revenue > 0) {
            return round(($this->net_income / $this->total_revenue) * 100, 2);
        }
        return 0;
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('report_type', $type);
    }

    public function scopeForPeriod($query, $start, $end)
    {
        return $query->whereBetween('period_start', [$start, $end])
                    ->orWhereBetween('period_end', [$start, $end]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Methods
    public function approve($userId = null)
    {
        $this->status = 'approved';
        $this->approved_by = $userId ?? auth()->id();
        $this->approved_at = now();
        $this->save();

        return $this;
    }

    public function calculateFinancials()
    {
        $this->net_income = $this->total_revenue - $this->total_expenses;
        $this->save();

        return $this;
    }

    public function generateSummary()
    {
        return [
            'report_name' => $this->report_name,
            'report_type' => $this->report_type_display,
            'period' => $this->period_start->format('Y-m-d') . ' to ' . $this->period_end->format('Y-m-d'),
            'total_revenue' => $this->total_revenue,
            'total_expenses' => $this->total_expenses,
            'net_income' => $this->net_income,
            'profit_margin' => $this->profit_margin . '%',
            'status' => $this->status,
            'generated_at' => $this->generated_at?->format('Y-m-d H:i:s')
        ];
    }
}
