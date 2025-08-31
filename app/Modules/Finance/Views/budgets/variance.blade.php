@extends('layouts.app')

@section('title', 'Budget Variance Analysis')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>📊 Budget Variance Analysis</h1>
                <div>
                    <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Budgets
                    </a>
                </div>
            </div>

            @if(count($budgets) > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Budget vs Actual Analysis</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Budget Period</th>
                                        <th>Account</th>
                                        <th class="text-end">Budgeted Amount</th>
                                        <th class="text-end">Actual Amount</th>
                                        <th class="text-end">Variance</th>
                                        <th class="text-end">Variance %</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($budgets as $budget)
                                        @php
                                            $analysis = $budget->analysis->first();
                                            $budgetedAmount = $budget->budgeted_amount ?? 0;
                                            $actualAmount = $analysis ? $analysis->actual_amount : 0;
                                            $variance = $actualAmount - $budgetedAmount;
                                            $variancePercent = $budgetedAmount > 0 ? ($variance / $budgetedAmount) * 100 : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $budget->budget_period ?? 'N/A' }}</td>
                                            <td>
                                                @if($budget->account)
                                                    {{ $budget->account->account_code }} - {{ $budget->account->account_name }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="text-end">${{ number_format($budgetedAmount, 2) }}</td>
                                            <td class="text-end">${{ number_format($actualAmount, 2) }}</td>
                                            <td class="text-end">
                                                <span class="{{ $variance >= 0 ? 'text-success' : 'text-danger' }}">
                                                    ${{ number_format(abs($variance), 2) }}
                                                    @if($variance >= 0)
                                                        <i class="fas fa-arrow-up"></i>
                                                    @else
                                                        <i class="fas fa-arrow-down"></i>
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="{{ abs($variancePercent) > 10 ? 'fw-bold' : '' }} {{ $variance >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($variancePercent, 1) }}%
                                                </span>
                                            </td>
                                            <td>
                                                @if(abs($variancePercent) <= 5)
                                                    <span class="badge bg-success">On Target</span>
                                                @elseif(abs($variancePercent) <= 15)
                                                    <span class="badge bg-warning">Monitor</span>
                                                @else
                                                    <span class="badge bg-danger">Action Required</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Statistics -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h5>Total Budgets</h5>
                                        <h3>{{ count($budgets) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5>On Target</h5>
                                        <h3>
                                            {{ $budgets->filter(function($budget) {
                                                $analysis = $budget->analysis->first();
                                                $budgetedAmount = $budget->budgeted_amount ?? 0;
                                                $actualAmount = $analysis ? $analysis->actual_amount : 0;
                                                $variance = $actualAmount - $budgetedAmount;
                                                $variancePercent = $budgetedAmount > 0 ? abs($variance / $budgetedAmount) * 100 : 0;
                                                return $variancePercent <= 5;
                                            })->count() }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h5>Monitor</h5>
                                        <h3>
                                            {{ $budgets->filter(function($budget) {
                                                $analysis = $budget->analysis->first();
                                                $budgetedAmount = $budget->budgeted_amount ?? 0;
                                                $actualAmount = $analysis ? $analysis->actual_amount : 0;
                                                $variance = $actualAmount - $budgetedAmount;
                                                $variancePercent = $budgetedAmount > 0 ? abs($variance / $budgetedAmount) * 100 : 0;
                                                return $variancePercent > 5 && $variancePercent <= 15;
                                            })->count() }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h5>Action Required</h5>
                                        <h3>
                                            {{ $budgets->filter(function($budget) {
                                                $analysis = $budget->analysis->first();
                                                $budgetedAmount = $budget->budgeted_amount ?? 0;
                                                $actualAmount = $analysis ? $analysis->actual_amount : 0;
                                                $variance = $actualAmount - $budgetedAmount;
                                                $variancePercent = $budgetedAmount > 0 ? abs($variance / $budgetedAmount) * 100 : 0;
                                                return $variancePercent > 15;
                                            })->count() }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Budget Analysis Available</h5>
                        <p class="text-muted">No budgets with analysis data found. Create budgets and run analysis to see variance reports.</p>
                        <a href="{{ route('finance.budgets.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Budget
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
