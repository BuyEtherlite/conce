@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Budget Management</h4>
                <div class="page-title-right">
                    <a href="{{ route('finance.budgets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Budget
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Budget Summary -->
    @if($currentBudget)
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie"></i> Current Active Budget: {{ $currentBudget->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h6 class="text-muted">Total Budget</h6>
                            <h4 class="text-primary">${{ number_format($currentBudget->total_budget, 2) }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Allocated</h6>
                            <h4 class="text-info">${{ number_format($currentBudget->total_allocated, 2) }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Spent</h6>
                            <h4 class="text-warning">${{ number_format($currentBudget->total_spent, 2) }}</h4>
                        </div>
                        <div class="col-md-3">
                            <h6 class="text-muted">Remaining</h6>
                            <h4 class="text-success">
                                ${{ number_format($currentBudget->total_budget - $currentBudget->total_spent, 2) }}
                            </h4>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('finance.budgets.show', $currentBudget) }}" class="btn btn-primary btn-sm">
                            View Details
                        </a>
                        <a href="{{ route('finance.budgets.variance.analysis') }}" class="btn btn-info btn-sm">
                            Variance Analysis
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Budgets Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">All Budgets</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Budget Code</th>
                                    <th>Name</th>
                                    <th>Fiscal Year</th>
                                    <th>Period</th>
                                    <th>Total Budget</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($budgets as $budget)
                                <tr>
                                    <td><strong>{{ $budget->code }}</strong></td>
                                    <td>{{ $budget->name }}</td>
                                    <td>{{ $budget->fiscal_year }}</td>
                                    <td>
                                        {{ $budget->start_date->format('M d, Y') }} - 
                                        {{ $budget->end_date->format('M d, Y') }}
                                    </td>
                                    <td>${{ number_format($budget->total_budget, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ 
                                            $budget->status === 'active' ? 'success' : 
                                            ($budget->status === 'approved' ? 'info' : 
                                            ($budget->status === 'draft' ? 'secondary' : 'dark'))
                                        }}">
                                            {{ ucfirst($budget->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $budget->createdBy->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('finance.budgets.show', $budget) }}" 
                                               class="btn btn-sm btn-outline-primary">View</a>

                                            @if($budget->status === 'draft')
                                            <a href="{{ route('finance.budgets.edit', $budget) }}" 
                                               class="btn btn-sm btn-outline-secondary">Edit</a>
                                            @endif

                                            @if($budget->status === 'draft')
                                            <form action="{{ route('finance.budgets.approve', $budget) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    Approve
                                                </button>
                                            </form>
                                            @elseif($budget->status === 'approved')
                                            <form action="{{ route('finance.budgets.activate', $budget) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-info">
                                                    Activate
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No budgets found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $budgets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection