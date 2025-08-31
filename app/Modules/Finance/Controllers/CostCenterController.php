<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Finance\Models\CostCenter;
use App\Modules\Finance\Models\Budget;
use Illuminate\Http\Request;

class CostCenterController extends Controller
{
    public function index()
    {
        $costCenters = CostCenter::with(['parent', 'manager', 'department'])
                                ->withCount('children')
                                ->paginate(20);

        $summary = [
            'total_centers' => CostCenter::count(),
            'active_centers' => CostCenter::active()->count(),
            'over_budget_centers' => CostCenter::overBudget()->count(),
            'total_budget_allocated' => CostCenter::sum('budget_allocated')
        ];

        return view('finance.cost-centers.index', compact('costCenters', 'summary'));
    }

    public function create()
    {
        $parentCenters = CostCenter::whereNull('parent_id')
                                  ->where('is_active', true)
                                  ->get();
        
        $managers = \App\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'manager');
        })->get();

        $departments = \App\Models\Department::where('is_active', true)->get();

        $types = [
            'department' => 'Department',
            'project' => 'Project',
            'product' => 'Product',
            'service' => 'Service',
            'location' => 'Location'
        ];

        return view('finance.cost-centers.create', compact('parentCenters', 'managers', 'departments', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:cost_centers,id',
            'manager_id' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'budget_allocated' => 'required|numeric|min:0',
            'type' => 'required|string|in:department,project,product,service,location',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        try {
            $costCenter = new CostCenter($validated);
            $costCenter->code = $costCenter->generateCode();
            $costCenter->budget_used = 0;
            $costCenter->budget_remaining = $validated['budget_allocated'];
            $costCenter->status = 'active';
            $costCenter->is_active = true;
            $costCenter->save();

            return redirect()->route('finance.costcenter.show', $costCenter)
                           ->with('success', 'Cost center created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to create cost center: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $costCenter = CostCenter::with(['parent', 'children', 'manager', 'department', 'budgets'])
                               ->findOrFail($id);

        $budgetSummary = [
            'total_budgets' => $costCenter->budgets()->count(),
            'active_budgets' => $costCenter->budgets()->active()->count(),
            'total_allocated' => $costCenter->budgets()->sum('budgeted_amount'),
            'total_spent' => $costCenter->budgets()->sum('actual_amount')
        ];

        return view('finance.cost-centers.show', compact('costCenter', 'budgetSummary'));
    }

    public function edit($id)
    {
        $costCenter = CostCenter::findOrFail($id);
        
        $parentCenters = CostCenter::whereNull('parent_id')
                                  ->where('id', '!=', $id)
                                  ->where('is_active', true)
                                  ->get();
        
        $managers = \App\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'manager');
        })->get();

        $departments = \App\Models\Department::where('is_active', true)->get();

        $types = [
            'department' => 'Department',
            'project' => 'Project',
            'product' => 'Product',
            'service' => 'Service',
            'location' => 'Location'
        ];

        return view('finance.cost-centers.edit', compact('costCenter', 'parentCenters', 'managers', 'departments', 'types'));
    }

    public function update(Request $request, $id)
    {
        $costCenter = CostCenter::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:cost_centers,id',
            'manager_id' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'budget_allocated' => 'required|numeric|min:0',
            'type' => 'required|string|in:department,project,product,service,location',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        try {
            // Update budget remaining if allocation changed
            if ($costCenter->budget_allocated !== $validated['budget_allocated']) {
                $validated['budget_remaining'] = $validated['budget_allocated'] - $costCenter->budget_used;
            }

            $costCenter->update($validated);

            return redirect()->route('finance.costcenter.show', $costCenter)
                           ->with('success', 'Cost center updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to update cost center: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $costCenter = CostCenter::findOrFail($id);
            
            // Check if has children
            if ($costCenter->children()->exists()) {
                return redirect()->back()
                               ->with('error', 'Cannot delete cost center with child centers');
            }

            // Check if has budgets
            if ($costCenter->budgets()->exists()) {
                return redirect()->back()
                               ->with('error', 'Cannot delete cost center with associated budgets');
            }

            $costCenter->delete();

            return redirect()->route('finance.costcenter.index')
                           ->with('success', 'Cost center deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to delete cost center: ' . $e->getMessage());
        }
    }

    public function allocateBudget(Request $request, $id)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500'
        ]);

        try {
            $costCenter = CostCenter::findOrFail($id);
            $costCenter->allocateBudget($validated['amount'], $validated['description']);

            return redirect()->route('finance.costcenter.show', $costCenter)
                           ->with('success', 'Budget allocated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to allocate budget: ' . $e->getMessage());
        }
    }

    public function updateBudgetUsage($id)
    {
        try {
            $costCenter = CostCenter::findOrFail($id);
            $costCenter->updateBudgetUsage();

            return redirect()->route('finance.costcenter.show', $costCenter)
                           ->with('success', 'Budget usage updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to update budget usage: ' . $e->getMessage());
        }
    }

    public function hierarchy()
    {
        $rootCenters = CostCenter::with(['children', 'manager'])
                                ->whereNull('parent_id')
                                ->get();

        return view('finance/costcenter.hierarchy', compact('rootCenters'));
    }

    public function reports($id)
    {
        $costCenter = CostCenter::with(['budgets', 'children'])->findOrFail($id);
        
        $reportData = [
            'budget_performance' => $this->getBudgetPerformanceData($costCenter),
            'monthly_usage' => $this->getMonthlyUsageData($costCenter),
            'comparison' => $this->getComparisonData($costCenter)
        ];

        return view('finance/costcenter.reports', compact('costCenter', 'reportData'));
    }

    private function getBudgetPerformanceData($costCenter)
    {
        return [
            'allocated' => $costCenter->budget_allocated,
            'used' => $costCenter->budget_used,
            'remaining' => $costCenter->budget_remaining,
            'utilization_rate' => $costCenter->budget_utilization,
            'status' => $costCenter->status
        ];
    }

    private function getMonthlyUsageData($costCenter)
    {
        $budgets = $costCenter->budgets()
                             ->selectRaw('MONTH(start_date) as month, SUM(actual_amount) as total')
                             ->groupBy('month')
                             ->get();

        return $budgets->pluck('total', 'month')->toArray();
    }

    private function getComparisonData($costCenter)
    {
        $siblings = CostCenter::where('parent_id', $costCenter->parent_id)
                             ->where('id', '!=', $costCenter->id)
                             ->get(['name', 'budget_allocated', 'budget_used', 'budget_utilization']);

        return $siblings->toArray();
    }
}
