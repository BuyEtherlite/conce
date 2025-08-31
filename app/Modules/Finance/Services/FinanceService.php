<?php

namespace App\Modules\Finance\Services;

use App\Models\Finance\ChartOfAccount;
use App\Models\Finance\GeneralLedger;
use App\Models\Finance\Invoice;
use App\Models\Finance\Payment;
use App\Models\Finance\Budget;
use Illuminate\Support\Collection;

class FinanceService
{
    /**
     * Get financial dashboard data
     */
    public function getDashboardData(): array
    {
        return [
            'total_revenue' => $this->getTotalRevenue(),
            'total_expenses' => $this->getTotalExpenses(),
            'outstanding_invoices' => $this->getOutstandingInvoices(),
            'recent_payments' => $this->getRecentPayments(),
            'budget_variance' => $this->getBudgetVariance(),
            'cash_position' => $this->getCashPosition()
        ];
    }

    /**
     * Get total revenue for current period
     */
    public function getTotalRevenue(): float
    {
        return GeneralLedger::where('account_type', 'revenue')
            ->whereYear('created_at', now()->year)
            ->sum('credit_amount');
    }

    /**
     * Get total expenses for current period
     */
    public function getTotalExpenses(): float
    {
        return GeneralLedger::where('account_type', 'expense')
            ->whereYear('created_at', now()->year)
            ->sum('debit_amount');
    }

    /**
     * Get outstanding invoices
     */
    public function getOutstandingInvoices(): Collection
    {
        return Invoice::where('status', 'pending')
            ->with(['customer'])
            ->orderBy('due_date', 'asc')
            ->limit(10)
            ->get();
    }

    /**
     * Get recent payments
     */
    public function getRecentPayments(): Collection
    {
        return Payment::with(['customer'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get budget variance analysis
     */
    public function getBudgetVariance(): array
    {
        $budgets = Budget::whereYear('fiscal_year', now()->year)->get();
        $variance = [];

        foreach ($budgets as $budget) {
            $actual = GeneralLedger::where('account_id', $budget->account_id)
                ->whereYear('created_at', now()->year)
                ->sum('debit_amount');
                
            $variance[] = [
                'account' => $budget->account->name,
                'budgeted' => $budget->amount,
                'actual' => $actual,
                'variance' => $budget->amount - $actual,
                'variance_percent' => $budget->amount > 0 ? (($budget->amount - $actual) / $budget->amount) * 100 : 0
            ];
        }

        return $variance;
    }

    /**
     * Get current cash position
     */
    public function getCashPosition(): float
    {
        return GeneralLedger::whereHas('account', function ($query) {
            $query->where('account_type', 'asset')
                  ->where('account_name', 'like', '%cash%');
        })->sum('debit_amount') - GeneralLedger::whereHas('account', function ($query) {
            $query->where('account_type', 'asset')
                  ->where('account_name', 'like', '%cash%');
        })->sum('credit_amount');
    }

    /**
     * Generate IPSAS compliant financial reports
     */
    public function generateIpsasReport(string $reportType): array
    {
        switch ($reportType) {
            case 'statement_financial_position':
                return $this->generateStatementOfFinancialPosition();
            case 'statement_financial_performance':
                return $this->generateStatementOfFinancialPerformance();
            case 'cash_flow_statement':
                return $this->generateCashFlowStatement();
            default:
                throw new \InvalidArgumentException("Invalid report type: {$reportType}");
        }
    }

    /**
     * Generate Statement of Financial Position (Balance Sheet)
     */
    protected function generateStatementOfFinancialPosition(): array
    {
        $assets = ChartOfAccount::where('account_type', 'asset')
            ->with(['ledgerEntries'])
            ->get()
            ->map(function ($account) {
                return [
                    'name' => $account->account_name,
                    'balance' => $account->ledgerEntries->sum('debit_amount') - $account->ledgerEntries->sum('credit_amount')
                ];
            });

        $liabilities = ChartOfAccount::where('account_type', 'liability')
            ->with(['ledgerEntries'])
            ->get()
            ->map(function ($account) {
                return [
                    'name' => $account->account_name,
                    'balance' => $account->ledgerEntries->sum('credit_amount') - $account->ledgerEntries->sum('debit_amount')
                ];
            });

        $equity = ChartOfAccount::where('account_type', 'equity')
            ->with(['ledgerEntries'])
            ->get()
            ->map(function ($account) {
                return [
                    'name' => $account->account_name,
                    'balance' => $account->ledgerEntries->sum('credit_amount') - $account->ledgerEntries->sum('debit_amount')
                ];
            });

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'total_assets' => $assets->sum('balance'),
            'total_liabilities' => $liabilities->sum('balance'),
            'total_equity' => $equity->sum('balance')
        ];
    }

    /**
     * Generate Statement of Financial Performance (Income Statement)
     */
    protected function generateStatementOfFinancialPerformance(): array
    {
        $revenue = ChartOfAccount::where('account_type', 'revenue')
            ->with(['ledgerEntries'])
            ->get()
            ->map(function ($account) {
                return [
                    'name' => $account->account_name,
                    'amount' => $account->ledgerEntries->sum('credit_amount') - $account->ledgerEntries->sum('debit_amount')
                ];
            });

        $expenses = ChartOfAccount::where('account_type', 'expense')
            ->with(['ledgerEntries'])
            ->get()
            ->map(function ($account) {
                return [
                    'name' => $account->account_name,
                    'amount' => $account->ledgerEntries->sum('debit_amount') - $account->ledgerEntries->sum('credit_amount')
                ];
            });

        $totalRevenue = $revenue->sum('amount');
        $totalExpenses = $expenses->sum('amount');

        return [
            'revenue' => $revenue,
            'expenses' => $expenses,
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'net_surplus_deficit' => $totalRevenue - $totalExpenses
        ];
    }

    /**
     * Generate Cash Flow Statement
     */
    protected function generateCashFlowStatement(): array
    {
        // This is a simplified version - full implementation would require more detailed cash flow categorization
        $operatingActivities = GeneralLedger::whereHas('account', function ($query) {
            $query->whereIn('account_type', ['revenue', 'expense']);
        })->get();

        $investingActivities = GeneralLedger::whereHas('account', function ($query) {
            $query->where('account_type', 'asset')
                  ->where('account_name', 'like', '%investment%');
        })->get();

        $financingActivities = GeneralLedger::whereHas('account', function ($query) {
            $query->whereIn('account_type', ['liability', 'equity']);
        })->get();

        return [
            'operating_activities' => $operatingActivities->sum('credit_amount') - $operatingActivities->sum('debit_amount'),
            'investing_activities' => $investingActivities->sum('debit_amount') - $investingActivities->sum('credit_amount'),
            'financing_activities' => $financingActivities->sum('credit_amount') - $financingActivities->sum('debit_amount')
        ];
    }
}
