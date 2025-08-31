<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Finance\Models\ProgramReport;
use App\Modules\Finance\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProgramReportController extends Controller
{
    public function index()
    {
        $reports = ProgramReport::with('generatedBy')
                               ->latest()
                               ->paginate(20);

        return view('finance.program-reports.index', compact('reports'));
    }

    public function create()
    {
        $reportTypes = [
            'budget_analysis' => 'Budget Analysis',
            'variance_analysis' => 'Variance Analysis',
            'program_performance' => 'Program Performance',
            'financial_summary' => 'Financial Summary'
        ];

        $programs = Budget::distinct()
                         ->whereNotNull('budget_name')
                         ->pluck('budget_name', 'budget_name');

        return view('finance.program-reports.create', compact('reportTypes', 'programs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_name' => 'required|string|max:255',
            'program_name' => 'required|string|max:255',
            'report_type' => 'required|string|in:budget_analysis,variance_analysis,program_performance,financial_summary',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'description' => 'nullable|string'
        ]);

        try {
            // Generate report data based on type
            $reportData = $this->generateReportData(
                $validated['program_name'],
                $validated['report_type'],
                $validated['period_start'],
                $validated['period_end']
            );

            $report = ProgramReport::create([
                'report_name' => $validated['report_name'],
                'program_name' => $validated['program_name'],
                'report_type' => $validated['report_type'],
                'period_start' => $validated['period_start'],
                'period_end' => $validated['period_end'],
                'total_budget' => $reportData['total_budget'],
                'total_spent' => $reportData['total_spent'],
                'total_remaining' => $reportData['total_remaining'],
                'variance' => $reportData['variance'],
                'status' => 'completed',
                'description' => $validated['description'],
                'data' => $reportData['details'],
                'generated_by' => auth()->id(),
                'generated_at' => now()
            ]);

            return redirect()->route('finance.programreport.show', $report)
                           ->with('success', 'Program report generated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to generate report: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $report = ProgramReport::with('generatedBy')->findOrFail($id);
        $summary = $report->generateSummary();

        return view('finance.program-reports.show', compact('report', 'summary'));
    }

    public function edit($id)
    {
        $report = ProgramReport::findOrFail($id);
        
        $reportTypes = [
            'budget_analysis' => 'Budget Analysis',
            'variance_analysis' => 'Variance Analysis',
            'program_performance' => 'Program Performance',
            'financial_summary' => 'Financial Summary'
        ];

        $programs = Budget::distinct()
                         ->whereNotNull('budget_name')
                         ->pluck('budget_name', 'budget_name');

        return view('finance.program-reports.edit', compact('report', 'reportTypes', 'programs'));
    }

    public function update(Request $request, $id)
    {
        $report = ProgramReport::findOrFail($id);

        $validated = $request->validate([
            'report_name' => 'required|string|max:255',
            'program_name' => 'required|string|max:255',
            'report_type' => 'required|string|in:budget_analysis,variance_analysis,program_performance,financial_summary',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'description' => 'nullable|string'
        ]);

        try {
            // Regenerate report data if period or type changed
            if ($report->program_name !== $validated['program_name'] ||
                $report->report_type !== $validated['report_type'] ||
                $report->period_start !== $validated['period_start'] ||
                $report->period_end !== $validated['period_end']) {
                
                $reportData = $this->generateReportData(
                    $validated['program_name'],
                    $validated['report_type'],
                    $validated['period_start'],
                    $validated['period_end']
                );

                $validated['total_budget'] = $reportData['total_budget'];
                $validated['total_spent'] = $reportData['total_spent'];
                $validated['total_remaining'] = $reportData['total_remaining'];
                $validated['variance'] = $reportData['variance'];
                $validated['data'] = $reportData['details'];
                $validated['generated_at'] = now();
            }

            $report->update($validated);

            return redirect()->route('finance.programreport.show', $report)
                           ->with('success', 'Program report updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to update report: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $report = ProgramReport::findOrFail($id);
            $report->delete();

            return redirect()->route('finance.programreport.index')
                           ->with('success', 'Program report deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to delete report: ' . $e->getMessage());
        }
    }

    /**
     * Generate report data based on program and type
     */
    private function generateReportData($programName, $reportType, $periodStart, $periodEnd)
    {
        $budgets = Budget::where('budget_name', 'like', "%{$programName}%")
                        ->forPeriod($periodStart, $periodEnd)
                        ->get();

        $totalBudget = $budgets->sum('budgeted_amount');
        $totalSpent = $budgets->sum('actual_amount');
        $totalRemaining = $totalBudget - $totalSpent;
        $variance = $totalSpent - $totalBudget;

        $details = match($reportType) {
            'budget_analysis' => $this->generateBudgetAnalysis($budgets),
            'variance_analysis' => $this->generateVarianceAnalysis($budgets),
            'program_performance' => $this->generatePerformanceAnalysis($budgets),
            'financial_summary' => $this->generateFinancialSummary($budgets),
            default => []
        };

        return [
            'total_budget' => $totalBudget,
            'total_spent' => $totalSpent,
            'total_remaining' => $totalRemaining,
            'variance' => $variance,
            'details' => $details
        ];
    }

    private function generateBudgetAnalysis($budgets)
    {
        return $budgets->map(function ($budget) {
            return [
                'budget_name' => $budget->budget_name,
                'budgeted_amount' => $budget->budgeted_amount,
                'actual_amount' => $budget->actual_amount,
                'utilization_rate' => $budget->utilization_rate,
                'status' => $budget->status
            ];
        })->toArray();
    }

    private function generateVarianceAnalysis($budgets)
    {
        return $budgets->map(function ($budget) {
            return [
                'budget_name' => $budget->budget_name,
                'variance' => $budget->variance,
                'variance_percent' => $budget->variance_percent,
                'variance_status' => $budget->variance_status
            ];
        })->toArray();
    }

    private function generatePerformanceAnalysis($budgets)
    {
        $totalBudgets = $budgets->count();
        $onTrack = $budgets->filter(fn($b) => abs($b->variance_percent) <= 5)->count();
        $warning = $budgets->filter(fn($b) => abs($b->variance_percent) > 5 && abs($b->variance_percent) <= 15)->count();
        $critical = $budgets->filter(fn($b) => abs($b->variance_percent) > 15)->count();

        return [
            'total_budgets' => $totalBudgets,
            'on_track' => $onTrack,
            'warning' => $warning,
            'critical' => $critical,
            'performance_score' => $totalBudgets > 0 ? ($onTrack / $totalBudgets) * 100 : 0
        ];
    }

    private function generateFinancialSummary($budgets)
    {
        return [
            'budget_count' => $budgets->count(),
            'average_utilization' => $budgets->avg('utilization_rate'),
            'over_budget_count' => $budgets->filter(fn($b) => $b->actual_amount > $b->budgeted_amount)->count(),
            'under_budget_count' => $budgets->filter(fn($b) => $b->actual_amount < $b->budgeted_amount)->count(),
            'largest_variance' => $budgets->max('variance'),
            'smallest_variance' => $budgets->min('variance')
        ];
    }
}
