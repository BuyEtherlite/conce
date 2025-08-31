<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\Finance\Models\ChartOfAccount;
use App\Modules\Finance\Models\GeneralLedger;
use App\Modules\Finance\Models\BankAccount;
use App\Modules\Finance\Models\ArInvoice;
use App\Modules\Finance\Models\Customer;
use App\Modules\Finance\Models\Budget;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function index()
    {
        // Dashboard statistics
        $stats = [
            'total_assets' => $this->getTotalAssets(),
            'total_liabilities' => $this->getTotalLiabilities(),
            'monthly_revenue' => $this->getMonthlyRevenue(),
            'monthly_expenses' => $this->getMonthlyExpenses(),
            'cash_balance' => $this->getCashBalance(),
            'accounts_receivable' => $this->getAccountsReceivable(),
            'accounts_payable' => $this->getAccountsPayable(),
            'budget_utilization' => $this->getBudgetUtilization(),
        ];

        // Recent transactions
        $recent_transactions = GeneralLedger::with(['account'])
            ->orderBy('transaction_date', 'desc')
            ->limit(10)
            ->get();

        // Outstanding invoices
        $outstanding_invoices = ArInvoice::where('status', '!=', 'paid')
            ->with('customer')
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        // Budget vs actual this month
        $budget_comparison = $this->getBudgetComparison();

        return view('finance.index', compact(
            'stats',
            'recent_transactions',
            'outstanding_invoices',
            'budget_comparison'
        ));
    }

    public function createInvoice()
    {
        return view('finance.create-invoice');
    }

    public function storeInvoice(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'invoice_number' => 'required|unique:finance_invoices',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        // Create invoice logic here
        return redirect()->route('finance.invoices')
            ->with('success', 'Invoice created successfully.');
    }

    public function invoices()
    {
        return view('finance.invoices');
    }

    public function showInvoice($id)
    {
        return view('finance.show-invoice', compact('id'));
    }

    public function customers()
    {
        return view('finance.customers');
    }

    public function payments()
    {
        return view('finance.payments');
    }

    public function receivables()
    {
        return view('finance.receivables');
    }

    private function getTotalAssets()
    {
        return ChartOfAccount::where('account_type', 'asset')
            ->where('is_active', true)
            ->sum('current_balance');
    }

    private function getTotalLiabilities()
    {
        return ChartOfAccount::where('account_type', 'liability')
            ->where('is_active', true)
            ->sum('current_balance');
    }

    private function getMonthlyRevenue()
    {
        $currentMonth = Carbon::now()->format('Y-m');

        return GeneralLedger::join('finance_chart_of_accounts', 'finance_general_ledger.account_code', '=', 'finance_chart_of_accounts.account_code')
            ->where('finance_chart_of_accounts.account_type', 'revenue')
            ->where('finance_chart_of_accounts.deleted_at', null)
            ->whereRaw("DATE_FORMAT(finance_general_ledger.transaction_date, '%Y-%m') = ?", [$currentMonth])
            ->sum('finance_general_ledger.credit_amount') ?? 0;
    }

    private function getMonthlyExpenses()
    {
        $currentMonth = Carbon::now()->format('Y-m');

        return GeneralLedger::whereHas('account', function($query) {
                $query->where('account_type', 'expense');
            })
            ->where('transaction_date', 'like', $currentMonth . '%')
            ->sum('debit_amount') ?? 0;
    }

    private function getCashBalance()
    {
        return BankAccount::where('is_active', true)->sum('current_balance');
    }

    private function getAccountsReceivable()
    {
        return ArInvoice::where('status', '!=', 'paid')->sum('balance_due');
    }

    private function getAccountsPayable()
    {
        return DB::table('ap_bills')
            ->where('status', '!=', 'paid')
            ->sum('balance_due');
    }

    private function getBudgetUtilization()
    {
        $currentBudget = Budget::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$currentBudget) {
            return 0;
        }

        return ($currentBudget->total_spent / $currentBudget->total_budget) * 100;
    }

    private function getBudgetComparison()
    {
        $currentMonth = Carbon::now()->format('Y-m');

        $actual = GeneralLedger::where('transaction_date', 'like', $currentMonth . '%')
            ->whereHas('account', function($query) {
                $query->where('account_type', 'expense');
            })
            ->sum('debit_amount') ?? 0;

        $budget = Budget::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        $monthlyBudget = $budget ? ($budget->total_budget / 12) : 0;

        return [
            'budgeted' => $monthlyBudget,
            'actual' => $actual,
            'variance' => $monthlyBudget - $actual,
            'percentage' => $monthlyBudget > 0 ? ($actual / $monthlyBudget) * 100 : 0
        ];
    }

    public function reports()
    {
        return view('finance.reports.index');
    }

    public function trialBalance()
    {
        $accounts = ChartOfAccount::where('is_active', true)
            ->with(['generalLedgerEntries' => function($query) {
                $query->where('status', 'posted');
            }])
            ->orderBy('account_code')
            ->get();

        $trial_balance = [];
        $total_debits = 0;
        $total_credits = 0;

        foreach ($accounts as $account) {
            $debit_total = $account->generalLedgerEntries
                ->where('transaction_type', 'debit')
                ->sum('amount');

            $credit_total = $account->generalLedgerEntries
                ->where('transaction_type', 'credit')
                ->sum('amount');

            if ($debit_total > 0 || $credit_total > 0) {
                $trial_balance[] = [
                    'account_code' => $account->account_code,
                    'account_name' => $account->account_name,
                    'account_type' => $account->account_type,
                    'debit' => $debit_total,
                    'credit' => $credit_total,
                ];

                $total_debits += $debit_total;
                $total_credits += $credit_total;
            }
        }

        return view('finance.reports.trial-balance', compact(
            'trial_balance',
            'total_debits',
            'total_credits'
        ));
    }

    public function balanceSheet()
    {
        $assets = ChartOfAccount::where('account_type', 'asset')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();

        $liabilities = ChartOfAccount::where('account_type', 'liability')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();

        $equity = ChartOfAccount::where('account_type', 'equity')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();

        $total_assets = $assets->sum('current_balance');
        $total_liabilities = $liabilities->sum('current_balance');
        $total_equity = $equity->sum('current_balance');

        return view('finance.reports.balance-sheet', compact(
            'assets',
            'liabilities',
            'equity',
            'total_assets',
            'total_liabilities',
            'total_equity'
        ));
    }

    public function incomeStatement(Request $request)
    {
        $start_date = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $end_date = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        $revenue_accounts = ChartOfAccount::where('account_type', 'revenue')
            ->where('is_active', true)
            ->with(['generalLedgerEntries' => function($query) use ($start_date, $end_date) {
                $query->whereBetween('transaction_date', [$start_date, $end_date])
                      ->where('status', 'posted');
            }])
            ->get();

        $expense_accounts = ChartOfAccount::where('account_type', 'expense')
            ->where('is_active', true)
            ->with(['generalLedgerEntries' => function($query) use ($start_date, $end_date) {
                $query->whereBetween('transaction_date', [$start_date, $end_date])
                      ->where('status', 'posted');
            }])
            ->get();

        $total_revenue = 0;
        $total_expenses = 0;

        foreach ($revenue_accounts as $account) {
            $account->period_total = $account->generalLedgerEntries
                ->where('transaction_type', 'credit')
                ->sum('amount');
            $total_revenue += $account->period_total;
        }

        foreach ($expense_accounts as $account) {
            $account->period_total = $account->generalLedgerEntries
                ->where('transaction_type', 'debit')
                ->sum('amount');
            $total_expenses += $account->period_total;
        }

        $net_income = $total_revenue - $total_expenses;

        return view('finance.reports.income-statement', compact(
            'revenue_accounts',
            'expense_accounts',
            'total_revenue',
            'total_expenses',
            'net_income',
            'start_date',
            'end_date'
        ));
    }

    public function cashFlow(Request $request)
    {
        $start_date = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $end_date = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Operating Activities
        $operating_receipts = DB::table('ar_receipts')
            ->whereBetween('receipt_date', [$start_date, $end_date])
            ->sum('amount_received');

        $operating_payments = DB::table('ap_payments')
            ->whereBetween('payment_date', [$start_date, $end_date])
            ->sum('payment_amount');

        // Investing Activities (simplified)
        $asset_purchases = GeneralLedger::whereHas('account', function($query) {
                $query->where('account_subtype', 'fixed_asset');
            })
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->where('transaction_type', 'debit')
            ->sum('amount');

        // Financing Activities (simplified)
        $loan_receipts = GeneralLedger::whereHas('account', function($query) {
                $query->where('account_subtype', 'long_term_liability');
            })
            ->whereBetween('transaction_date', [$start_date, $end_date])
            ->where('transaction_type', 'credit')
            ->sum('amount');

        $cash_flow = [
            'operating' => [
                'receipts' => $operating_receipts,
                'payments' => $operating_payments,
                'net' => $operating_receipts - $operating_payments
            ],
            'investing' => [
                'purchases' => $asset_purchases,
                'net' => -$asset_purchases
            ],
            'financing' => [
                'receipts' => $loan_receipts,
                'net' => $loan_receipts
            ]
        ];

        $net_cash_flow = $cash_flow['operating']['net'] +
                        $cash_flow['investing']['net'] +
                        $cash_flow['financing']['net'];

        return view('finance.reports.cash-flow', compact(
            'cash_flow',
            'net_cash_flow',
            'start_date',
            'end_date'
        ));
    }
}