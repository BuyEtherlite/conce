<?php

namespace App\Http\Controllers\Finance;

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
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

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
            ->sum('current_balance') ?? 0;
    }

    private function getTotalLiabilities()
    {
        return ChartOfAccount::where('account_type', 'liability')
            ->where('is_active', true)
            ->sum('current_balance') ?? 0;
    }

    private function getMonthlyRevenue()
    {
        $currentMonth = Carbon::now()->format('Y-m');

        return GeneralLedger::whereHas('account', function($query) {
                $query->where('account_type', 'revenue');
            })
            ->where('transaction_date', 'like', $currentMonth . '%')
            ->sum('credit_amount') ?? 0;
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
        return BankAccount::where('is_active', true)->sum('current_balance') ?? 0;
    }

    private function getAccountsReceivable()
    {
        return ArInvoice::where('status', '!=', 'paid')->sum('balance_due') ?? 0;
    }

    private function getAccountsPayable()
    {
        return DB::table('ap_bills')
            ->where('status', '!=', 'paid')
            ->sum('balance_due') ?? 0;
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
}
