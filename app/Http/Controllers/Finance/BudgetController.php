<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Finance\Models\Budget;
use App\Modules\Finance\Models\BudgetAnalysis;
use App\Modules\Finance\Models\ChartOfAccount;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::with(['account'])->get();
        $currentBudget = Budget::where('status', 'active')->first();
        
        return view('finance.budgets.index', compact('budgets', 'currentBudget'));
    }

    public function create()
    {
        $accounts = ChartOfAccount::all();
        return view('finance.budgets.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'budget_name' => 'required|string|max:255',
            'financial_year' => 'required|string',
            'account_id' => 'required|exists:finance_chart_of_accounts,id',
            'budgeted_amount' => 'required|numeric|min:0',
            'period' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Budget::create($request->all());
        
        return redirect()->route('finance.budgets.index')
            ->with('success', 'Budget created successfully');
    }

    public function show(Budget $budget)
    {
        return view('finance.budgets.show', compact('budget'));
    }

    public function edit(Budget $budget)
    {
        $accounts = ChartOfAccount::all();
        return view('finance.budgets.edit', compact('budget', 'accounts'));
    }

    public function update(Request $request, Budget $budget)
    {
        $request->validate([
            'budget_name' => 'required|string|max:255',
            'financial_year' => 'required|string',
            'account_id' => 'required|exists:finance_chart_of_accounts,id',
            'budgeted_amount' => 'required|numeric|min:0',
            'period' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $budget->update($request->all());
        
        return redirect()->route('finance.budgets.index')
            ->with('success', 'Budget updated successfully');
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();
        
        return redirect()->route('finance.budgets.index')
            ->with('success', 'Budget deleted successfully');
    }

    public function variance()
    {
        $budgets = Budget::with(['account', 'analysis'])->get();
        return view('finance.budgets.variance', compact('budgets'));
    }

    public function approve(Budget $budget)
    {
        $budget->update(['status' => 'approved']);
        
        return redirect()->route('finance.budgets.index')
            ->with('success', 'Budget approved successfully');
    }

    public function activate(Budget $budget)
    {
        // Deactivate other budgets first
        Budget::where('status', 'active')->update(['status' => 'inactive']);
        
        // Activate this budget
        $budget->update(['status' => 'active']);
        
        return redirect()->route('finance.budgets.index')
            ->with('success', 'Budget activated successfully');
    }

    public function varianceAnalysis()
    {
        $budgets = Budget::with(['account', 'analysis'])->get();
        
        return view('finance.budgets.variance', compact('budgets'));
    }
}
