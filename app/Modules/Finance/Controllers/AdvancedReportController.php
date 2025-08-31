<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Finance\Models\FinancialReport;
use App\Modules\Finance\Models\Budget;
use App\Modules\Finance\Models\ChartOfAccount;
use App\Modules\Finance\Models\GeneralLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdvancedReportController extends Controller
{
    public function index()
    {
        $reports = FinancialReport::with('generatedBy')
                                 ->latest()
                                 ->paginate(20);

        $reportTypes = [
            'income_statement' => 'Income Statement',
            'balance_sheet' => 'Balance Sheet',
            'cash_flow' => 'Cash Flow Statement',
            'budget_variance' => 'Budget Variance Report',
            'trial_balance' => 'Trial Balance',
            'general_ledger' => 'General Ledger Report',
            'accounts_receivable' => 'Accounts Receivable Report',
            'accounts_payable' => 'Accounts Payable Report'
        ];

        return view('finance/advancedreport.index', compact('reports', 'reportTypes'));
    }

    public function create()
    {
        $reportTypes = [
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

        $accounts = ChartOfAccount::where('is_active', true)->get();

        return view('finance/advancedreport.create', compact('reportTypes', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_name' => 'required|string|max:255',
            'report_type' => 'required|string',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'description' => 'nullable|string',
            'parameters' => 'nullable|array'
        ]);

        try {
            $report = new FinancialReport($validated);
            $report->status = 'generating';
            $report->generated_by = auth()->id();
            $report->generated_at = now();
            $report->save();

            // Generate report data based on type
            $this->generateReportData($report);

            return redirect()->route('finance.advancedreport.show', $report)
                           ->with('success', 'Advanced report generated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to generate report: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $report = FinancialReport::with('generatedBy')->findOrFail($id);
        $summary = $report->generateSummary();

        return view('finance/advancedreport.show', compact('report', 'summary'));
    }

    public function edit($id)
    {
        $report = FinancialReport::findOrFail($id);
        
        $reportTypes = [
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

        $accounts = ChartOfAccount::where('is_active', true)->get();

        return view('finance/advancedreport.edit', compact('report', 'reportTypes', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        $report = FinancialReport::findOrFail($id);

        $validated = $request->validate([
            'report_name' => 'required|string|max:255',
            'report_type' => 'required|string',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'description' => 'nullable|string',
            'parameters' => 'nullable|array'
        ]);

        try {
            $report->update($validated);
            
            // Regenerate if period or type changed
            if ($report->wasChanged(['report_type', 'period_start', 'period_end', 'parameters'])) {
                $report->status = 'generating';
                $report->generated_at = now();
                $report->save();
                
                $this->generateReportData($report);
            }

            return redirect()->route('finance.advancedreport.show', $report)
                           ->with('success', 'Advanced report updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to update report: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $report = FinancialReport::findOrFail($id);
            $report->delete();

            return redirect()->route('finance.advancedreport.index')
                           ->with('success', 'Advanced report deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to delete report: ' . $e->getMessage());
        }
    }

    public function export($id, $format = 'pdf')
    {
        $report = FinancialReport::findOrFail($id);

        switch ($format) {
            case 'excel':
                return $this->exportToExcel($report);
            case 'csv':
                return $this->exportToCsv($report);
            case 'pdf':
            default:
                return $this->exportToPdf($report);
        }
    }

    public function approve($id)
    {
        try {
            $report = FinancialReport::findOrFail($id);
            $report->approve();

            return redirect()->route('finance.advancedreport.show', $report)
                           ->with('success', 'Report approved successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to approve report: ' . $e->getMessage());
        }
    }

    /**
     * Generate report data based on report type
     */
    private function generateReportData(FinancialReport $report)
    {
        try {
            $data = match($report->report_type) {
                'income_statement' => $this->generateIncomeStatement($report),
                'balance_sheet' => $this->generateBalanceSheet($report),
                'cash_flow' => $this->generateCashFlowStatement($report),
                'budget_variance' => $this->generateBudgetVarianceReport($report),
                'trial_balance' => $this->generateTrialBalance($report),
                'general_ledger' => $this->generateGeneralLedgerReport($report),
                'accounts_receivable' => $this->generateARReport($report),
                'accounts_payable' => $this->generateAPReport($report),
                default => $this->generateCustomReport($report)
            };

            $report->update([
                'data' => $data['details'],
                'summary' => $data['summary'],
                'total_revenue' => $data['total_revenue'] ?? 0,
                'total_expenses' => $data['total_expenses'] ?? 0,
                'status' => 'completed'
            ]);

            $report->calculateFinancials();
        } catch (\Exception $e) {
            $report->update([
                'status' => 'failed',
                'summary' => ['error' => $e->getMessage()]
            ]);
        }
    }

    private function generateIncomeStatement($report)
    {
        // Revenue accounts (4000-4999)
        $revenue = ChartOfAccount::whereBetween('account_code', ['4000', '4999'])
                                ->with(['ledgerEntries' => function($q) use ($report) {
                                    $q->whereBetween('transaction_date', [$report->period_start, $report->period_end]);
                                }])
                                ->get();

        // Expense accounts (5000-9999)
        $expenses = ChartOfAccount::whereBetween('account_code', ['5000', '9999'])
                                 ->with(['ledgerEntries' => function($q) use ($report) {
                                     $q->whereBetween('transaction_date', [$report->period_start, $report->period_end]);
                                 }])
                                 ->get();

        $totalRevenue = $revenue->sum(function($account) {
            return $account->ledgerEntries->sum('credit') - $account->ledgerEntries->sum('debit');
        });

        $totalExpenses = $expenses->sum(function($account) {
            return $account->ledgerEntries->sum('debit') - $account->ledgerEntries->sum('credit');
        });

        return [
            'details' => [
                'revenue' => $revenue->map(function($account) {
                    return [
                        'account_name' => $account->account_name,
                        'account_code' => $account->account_code,
                        'amount' => $account->ledgerEntries->sum('credit') - $account->ledgerEntries->sum('debit')
                    ];
                }),
                'expenses' => $expenses->map(function($account) {
                    return [
                        'account_name' => $account->account_name,
                        'account_code' => $account->account_code,
                        'amount' => $account->ledgerEntries->sum('debit') - $account->ledgerEntries->sum('credit')
                    ];
                })
            ],
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_expenses' => $totalExpenses,
                'net_income' => $totalRevenue - $totalExpenses,
                'gross_margin' => $totalRevenue > 0 ? (($totalRevenue - $totalExpenses) / $totalRevenue) * 100 : 0
            ],
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses
        ];
    }

    private function generateBalanceSheet($report)
    {
        // Assets (1000-1999)
        $assets = ChartOfAccount::whereBetween('account_code', ['1000', '1999'])
                               ->with(['ledgerEntries' => function($q) use ($report) {
                                   $q->where('transaction_date', '<=', $report->period_end);
                               }])
                               ->get();

        // Liabilities (2000-2999)
        $liabilities = ChartOfAccount::whereBetween('account_code', ['2000', '2999'])
                                    ->with(['ledgerEntries' => function($q) use ($report) {
                                        $q->where('transaction_date', '<=', $report->period_end);
                                    }])
                                    ->get();

        // Equity (3000-3999)
        $equity = ChartOfAccount::whereBetween('account_code', ['3000', '3999'])
                               ->with(['ledgerEntries' => function($q) use ($report) {
                                   $q->where('transaction_date', '<=', $report->period_end);
                               }])
                               ->get();

        $totalAssets = $assets->sum(function($account) {
            return $account->ledgerEntries->sum('debit') - $account->ledgerEntries->sum('credit');
        });

        $totalLiabilities = $liabilities->sum(function($account) {
            return $account->ledgerEntries->sum('credit') - $account->ledgerEntries->sum('debit');
        });

        $totalEquity = $equity->sum(function($account) {
            return $account->ledgerEntries->sum('credit') - $account->ledgerEntries->sum('debit');
        });

        return [
            'details' => [
                'assets' => $assets->map(function($account) {
                    return [
                        'account_name' => $account->account_name,
                        'account_code' => $account->account_code,
                        'amount' => $account->ledgerEntries->sum('debit') - $account->ledgerEntries->sum('credit')
                    ];
                }),
                'liabilities' => $liabilities->map(function($account) {
                    return [
                        'account_name' => $account->account_name,
                        'account_code' => $account->account_code,
                        'amount' => $account->ledgerEntries->sum('credit') - $account->ledgerEntries->sum('debit')
                    ];
                }),
                'equity' => $equity->map(function($account) {
                    return [
                        'account_name' => $account->account_name,
                        'account_code' => $account->account_code,
                        'amount' => $account->ledgerEntries->sum('credit') - $account->ledgerEntries->sum('debit')
                    ];
                })
            ],
            'summary' => [
                'total_assets' => $totalAssets,
                'total_liabilities' => $totalLiabilities,
                'total_equity' => $totalEquity,
                'balance_check' => $totalAssets - ($totalLiabilities + $totalEquity)
            ],
            'total_revenue' => 0,
            'total_expenses' => 0
        ];
    }

    private function generateBudgetVarianceReport($report)
    {
        $budgets = Budget::forPeriod($report->period_start, $report->period_end)->get();

        $details = $budgets->map(function($budget) {
            return [
                'budget_name' => $budget->budget_name,
                'budgeted_amount' => $budget->budgeted_amount,
                'actual_amount' => $budget->actual_amount,
                'variance' => $budget->variance,
                'variance_percent' => $budget->variance_percent,
                'status' => $budget->variance_status
            ];
        });

        return [
            'details' => $details,
            'summary' => [
                'total_budgeted' => $budgets->sum('budgeted_amount'),
                'total_actual' => $budgets->sum('actual_amount'),
                'total_variance' => $budgets->sum('variance'),
                'over_budget_count' => $budgets->where('variance', '>', 0)->count(),
                'under_budget_count' => $budgets->where('variance', '<', 0)->count()
            ],
            'total_revenue' => $budgets->sum('budgeted_amount'),
            'total_expenses' => $budgets->sum('actual_amount')
        ];
    }

    private function generateTrialBalance($report)
    {
        $accounts = ChartOfAccount::with(['ledgerEntries' => function($q) use ($report) {
            $q->whereBetween('transaction_date', [$report->period_start, $report->period_end]);
        }])->get();

        $details = $accounts->map(function($account) {
            $debitTotal = $account->ledgerEntries->sum('debit');
            $creditTotal = $account->ledgerEntries->sum('credit');
            
            return [
                'account_code' => $account->account_code,
                'account_name' => $account->account_name,
                'debit' => $debitTotal,
                'credit' => $creditTotal,
                'balance' => $debitTotal - $creditTotal
            ];
        });

        $totalDebits = $details->sum('debit');
        $totalCredits = $details->sum('credit');

        return [
            'details' => $details,
            'summary' => [
                'total_debits' => $totalDebits,
                'total_credits' => $totalCredits,
                'balance_difference' => $totalDebits - $totalCredits,
                'is_balanced' => abs($totalDebits - $totalCredits) < 0.01
            ],
            'total_revenue' => $totalCredits,
            'total_expenses' => $totalDebits
        ];
    }

    private function generateCashFlowStatement($report)
    {
        // Simplified cash flow calculation
        return [
            'details' => ['message' => 'Cash flow statement generation not yet implemented'],
            'summary' => ['status' => 'pending_implementation'],
            'total_revenue' => 0,
            'total_expenses' => 0
        ];
    }

    private function generateGeneralLedgerReport($report)
    {
        // Simplified general ledger
        return [
            'details' => ['message' => 'General ledger report generation not yet implemented'],
            'summary' => ['status' => 'pending_implementation'],
            'total_revenue' => 0,
            'total_expenses' => 0
        ];
    }

    private function generateARReport($report)
    {
        // Accounts receivable report
        return [
            'details' => ['message' => 'AR report generation not yet implemented'],
            'summary' => ['status' => 'pending_implementation'],
            'total_revenue' => 0,
            'total_expenses' => 0
        ];
    }

    private function generateAPReport($report)
    {
        // Accounts payable report
        return [
            'details' => ['message' => 'AP report generation not yet implemented'],
            'summary' => ['status' => 'pending_implementation'],
            'total_revenue' => 0,
            'total_expenses' => 0
        ];
    }

    private function generateCustomReport($report)
    {
        return [
            'details' => ['message' => 'Custom report generation not yet implemented'],
            'summary' => ['status' => 'pending_implementation'],
            'total_revenue' => 0,
            'total_expenses' => 0
        ];
    }

    private function exportToPdf($report)
    {
        // PDF export logic would go here
        return response()->json(['message' => 'PDF export not yet implemented']);
    }

    private function exportToExcel($report)
    {
        // Excel export logic would go here
        return response()->json(['message' => 'Excel export not yet implemented']);
    }

    private function exportToCsv($report)
    {
        // CSV export logic would go here
        return response()->json(['message' => 'CSV export not yet implemented']);
    }
}
