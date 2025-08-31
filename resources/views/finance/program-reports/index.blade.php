@extends('layouts.admin')

@section('title', 'Program Based Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">📊 Program Based Reports</h3>
                    <div class="btn-group">
                        <a href="{{ route('finance.program-reports.program-performance') }}" class="btn btn-primary">
                            <i class="fas fa-chart-line"></i> Program Performance
                        </a>
                        <a href="{{ route('finance.program-reports.budget-execution') }}" class="btn btn-info">
                            <i class="fas fa-calculator"></i> Budget Execution
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>{{ number_format($programSummary['total_programs']) }}</h3>
                                            <p class="mb-0">Total Programs</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-project-diagram fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>${{ number_format($programSummary['total_budget_allocated'], 2) }}</h3>
                                            <p class="mb-0">Total Budget Allocated</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-dollar-sign fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>${{ number_format($programSummary['total_expenditure'], 2) }}</h3>
                                            <p class="mb-0">Total Expenditure</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-money-bill-wave fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>{{ number_format($programSummary['variance_percentage'], 1) }}%</h3>
                                            <p class="mb-0">Budget Variance</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-percentage fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Access Reports -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-chart-line"></i> Performance Reports
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <a href="{{ route('finance.program-reports.program-performance') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-tachometer-alt me-2"></i>Program Performance Analysis
                                        </a>
                                        <a href="{{ route('finance.program-reports.outcome-analysis') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-bullseye me-2"></i>Outcome Analysis
                                        </a>
                                        <a href="{{ route('finance.program-reports.cost-center-analysis') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-sitemap me-2"></i>Cost Center Analysis
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-calculator"></i> Budget Reports
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <a href="{{ route('finance.program-reports.budget-execution') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-chart-bar me-2"></i>Budget Execution Report
                                        </a>
                                        <a href="{{ route('finance.budgets.variance') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-balance-scale me-2"></i>Budget Variance Analysis
                                        </a>
                                        <a href="{{ route('finance.budgets.index') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-money-check-alt me-2"></i>Budget Management
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-cogs"></i> Operational Reports
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <a href="{{ route('finance.cost-centers.index') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-building me-2"></i>Cost Center Management
                                        </a>
                                        <a href="{{ route('finance.reports.index') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-file-alt me-2"></i>Financial Reports
                                        </a>
                                        <a href="{{ route('finance.general-ledger.index') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-book me-2"></i>General Ledger
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Programs -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Recent Programs</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Program Code</th>
                                                    <th>Program Name</th>
                                                    <th>Budget Allocated</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($recentPrograms as $program)
                                                <tr>
                                                    <td>{{ $program->code }}</td>
                                                    <td>{{ $program->name }}</td>
                                                    <td>${{ number_format($program->budgets->sum('budgeted_amount'), 2) }}</td>
                                                    <td>
                                                        <span class="badge bg-success">Active</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('finance.cost-centers.show', $program) }}" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No programs found.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
