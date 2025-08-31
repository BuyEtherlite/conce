<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Finance\Models\GeneralLedger;
use App\Modules\Finance\Models\ChartOfAccount;
use App\Modules\Finance\Models\Budget;
use App\Modules\Finance\Models\Invoice;
use App\Modules\Finance\Models\Payment;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the reports dashboard.
     */
    public function index()
    {
        return view('finance.reports.index');
    }

    /**
     * Generate Income Statement (Profit & Loss).
     */
    public function incomeStatement(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Get revenue accounts
        $revenues = $this->getAccountBalances('revenue', $startDate, $endDate);
        
        // Get expense accounts  
        $expenses = $this->getAccountBalances('expense', $startDate, $endDate);
        
        $totalRevenue = $revenues->sum('balance');
        $totalExpenses = $expenses->sum('balance');
        $netIncome = $totalRevenue - $totalExpenses;

        return view('finance.reports.income-statement', compact(
            'revenues', 'expenses', 'totalRevenue', 'totalExpenses', 
            'netIncome', 'startDate', 'endDate'
        ));
    }

    /**
     * Generate Balance Sheet.
     */
    public function balanceSheet(Request $request)
    {
        $request->validate([
            'as_of_date' => 'required|date',
        ]);

        $asOfDate = $request->as_of_date;

        // Get account balances as of the specified date
        $assets = $this->getAccountBalances('asset', null, $asOfDate);
        $liabilities = $this->getAccountBalances('liability', null, $asOfDate);
        $equity = $this->getAccountBalances('equity', null, $asOfDate);

        $totalAssets = $assets->sum('balance');
        $totalLiabilities = $liabilities->sum('balance');
        $totalEquity = $equity->sum('balance');

        return view('finance.reports.balance-sheet', compact(
            'assets', 'liabilities', 'equity', 'totalAssets', 
            'totalLiabilities', 'totalEquity', 'asOfDate'
        ));
    }

    /**
     * Generate Cash Flow Statement.
     */
    public function cashFlowStatement(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Operating Activities
        $operatingCashFlow = $this->calculateOperatingCashFlow($startDate, $endDate);
        
        // Investing Activities
        $investingCashFlow = $this->calculateInvestingCashFlow($startDate, $endDate);
        
        // Financing Activities
        $financingCashFlow = $this->calculateFinancingCashFlow($startDate, $endDate);

        $netCashFlow = $operatingCashFlow + $investingCashFlow + $financingCashFlow;

        return view('finance.reports.cash-flow-statement', compact(
            'operatingCashFlow', 'investingCashFlow', 'financingCashFlow',
            'netCashFlow', 'startDate', 'endDate'
        ));
    }

    /**
     * Generate Trial Balance.
     */
    public function trialBalance(Request $request)
    {
        $request->validate([
            'as_of_date' => 'required|date',
        ]);

        $asOfDate = $request->as_of_date;

        $trialBalance = GeneralLedger::selectRaw('
                account_code,
                SUM(CASE WHEN transaction_type = "debit" THEN amount ELSE 0 END) as total_debits,
                SUM(CASE WHEN transaction_type = "credit" THEN amount ELSE 0 END) as total_credits
            ')
            ->where('transaction_date', '<=', $asOfDate)
            ->groupBy('account_code')
            ->with('account')
            ->get();

        $totalDebits = $trialBalance->sum('total_debits');
        $totalCredits = $trialBalance->sum('total_credits');

        return view('finance.reports.trial-balance', compact(
            'trialBalance', 'totalDebits', 'totalCredits', 'asOfDate'
        ));
    }

    /**
     * Generate Budget vs Actual Report.
     */
    public function budgetVsActual(Request $request)
    {
        $request->validate([
            'financial_year' => 'required|string',
            'period' => 'nullable|string',
        ]);

        $financialYear = $request->financial_year;
        $period = $request->period;

        $budgets = Budget::with('account')
            ->where('financial_year', $financialYear)
            ->when($period, function ($query) use ($period) {
                return $query->where('period', $period);
            })
            ->get();

        // Calculate variances
        $budgets->each(function ($budget) {
            $budget->variance = $budget->actual_amount - $budget->budgeted_amount;
            $budget->variance_percent = $budget->budgeted_amount > 0 
                ? ($budget->variance / $budget->budgeted_amount) * 100 
                : 0;
        });

        return view('finance.reports.budget-vs-actual', compact(
            'budgets', 'financialYear', 'period'
        ));
    }

    /**
     * Generate Accounts Receivable Aging Report.
     */
    public function accountsReceivableAging(Request $request)
    {
        $asOfDate = $request->get('as_of_date', now()->toDateString());

        $invoices = Invoice::with('customer')
            ->where('status', '!=', 'paid')
            ->where('invoice_date', '<=', $asOfDate)
            ->get()
            ->map(function ($invoice) use ($asOfDate) {
                $daysOutstanding = Carbon::parse($asOfDate)->diffInDays($invoice->due_date, false);
                
                $invoice->days_outstanding = abs($daysOutstanding);
                $invoice->aging_bucket = $this->getAgingBucket($daysOutstanding);
                
                return $invoice;
            })
            ->groupBy('aging_bucket');

        $totals = [
            'current' => $invoices->get('current', collect())->sum('total_amount'),
            '1-30' => $invoices->get('1-30', collect())->sum('total_amount'),
            '31-60' => $invoices->get('31-60', collect())->sum('total_amount'),
            '61-90' => $invoices->get('61-90', collect())->sum('total_amount'),
            'over_90' => $invoices->get('over_90', collect())->sum('total_amount'),
        ];

        $grandTotal = array_sum($totals);

        return view('finance.reports.ar-aging', compact(
            'invoices', 'totals', 'grandTotal', 'asOfDate'
        ));
    }

    /**
     * Generate Financial Summary Report.
     */
    public function financialSummary(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $summary = [
            'total_revenue' => $this->getTotalRevenue($startDate, $endDate),
            'total_expenses' => $this->getTotalExpenses($startDate, $endDate),
            'net_income' => 0, // Will be calculated below
            'total_assets' => $this->getTotalAssets($endDate),
            'total_liabilities' => $this->getTotalLiabilities($endDate),
            'cash_balance' => $this->getCashBalance($endDate),
            'accounts_receivable' => $this->getAccountsReceivable($endDate),
            'accounts_payable' => $this->getAccountsPayable($endDate),
        ];

        $summary['net_income'] = $summary['total_revenue'] - $summary['total_expenses'];

        return view('finance.reports.financial-summary', compact(
            'summary', 'startDate', 'endDate'
        ));
    }

    /**
     * Export report to Excel/PDF.
     */
    public function export(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string',
            'format' => 'required|string|in:excel,pdf',
        ]);

        // Export logic would go here
        
        return response()->json([
            'success' => true,
            'message' => 'Export functionality to be implemented'
        ]);
    }

    /**
     * Get account balances for a specific account type and date range.
     */
    private function getAccountBalances($accountType, $startDate = null, $endDate = null)
    {
        $query = ChartOfAccount::where('account_type', $accountType)
            ->where('is_active', true);

        return $query->get()->map(function ($account) use ($startDate, $endDate) {
            $ledgerQuery = GeneralLedger::where('account_code', $account->account_code);
            
            if ($startDate) {
                $ledgerQuery->where('transaction_date', '>=', $startDate);
            }
            
            if ($endDate) {
                $ledgerQuery->where('transaction_date', '<=', $endDate);
            }

            $debits = $ledgerQuery->where('transaction_type', 'debit')->sum('amount');
            $credits = $ledgerQuery->where('transaction_type', 'credit')->sum('amount');
            
            $account->balance = $debits - $credits;
            
            return $account;
        });
    }

    /**
     * Calculate operating cash flow.
     */
    private function calculateOperatingCashFlow($startDate, $endDate)
    {
        // Simplified calculation - would need more complex logic in real implementation
        return 0;
    }

    /**
     * Calculate investing cash flow.
     */
    private function calculateInvestingCashFlow($startDate, $endDate)
    {
        // Simplified calculation - would need more complex logic in real implementation
        return 0;
    }

    /**
     * Calculate financing cash flow.
     */
    private function calculateFinancingCashFlow($startDate, $endDate)
    {
        // Simplified calculation - would need more complex logic in real implementation
        return 0;
    }

    /**
     * Get aging bucket for invoice.
     */
    private function getAgingBucket($daysOutstanding)
    {
        if ($daysOutstanding >= 0) {
            return 'current';
        } elseif ($daysOutstanding >= -30) {
            return '1-30';
        } elseif ($daysOutstanding >= -60) {
            return '31-60';
        } elseif ($daysOutstanding >= -90) {
            return '61-90';
        } else {
            return 'over_90';
        }
    }

    /**
     * Get total revenue for period.
     */
    private function getTotalRevenue($startDate, $endDate)
    {
        return $this->getAccountBalances('revenue', $startDate, $endDate)->sum('balance');
    }

    /**
     * Get total expenses for period.
     */
    private function getTotalExpenses($startDate, $endDate)
    {
        return $this->getAccountBalances('expense', $startDate, $endDate)->sum('balance');
    }

    /**
     * Get total assets as of date.
     */
    private function getTotalAssets($asOfDate)
    {
        return $this->getAccountBalances('asset', null, $asOfDate)->sum('balance');
    }

    /**
     * Get total liabilities as of date.
     */
    private function getTotalLiabilities($asOfDate)
    {
        return $this->getAccountBalances('liability', null, $asOfDate)->sum('balance');
    }

    /**
     * Get cash balance as of date.
     */
    private function getCashBalance($asOfDate)
    {
        // Would filter for cash/bank accounts specifically
        return 0;
    }

    /**
     * Get accounts receivable balance as of date.
     */
    private function getAccountsReceivable($asOfDate)
    {
        return Invoice::where('status', '!=', 'paid')
            ->where('invoice_date', '<=', $asOfDate)
            ->sum('total_amount');
    }

    /**
     * Get accounts payable balance as of date.
     */
    private function getAccountsPayable($asOfDate)
    {
        // Would need AP Bills model for this calculation
        return 0;
    }
}