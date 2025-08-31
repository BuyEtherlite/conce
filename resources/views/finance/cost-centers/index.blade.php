@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Cost Center Management</h4>
                <div class="page-title-right">
                    <a href="{{ route('finance.cost-centers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Cost Center
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted">Total Budget Allocation</h5>
                    <h3 class="text-primary">${{ number_format($totalBudgetAllocation, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-muted">Total Expenses</h5>
                    <h3 class="text-warning">${{ number_format($totalExpenses, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Cost Centers Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Cost Centers</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Manager</th>
                                    <th>Budget Allocation</th>
                                    <th>Total Expenses</th>
                                    <th>Utilization</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($costCenters as $costCenter)
                                @php
                                    $totalExpenses = $costCenter->expenses_sum_amount ?? 0;
                                    $utilization = $costCenter->budget_allocation > 0 
                                        ? ($totalExpenses / $costCenter->budget_allocation) * 100 
                                        : 0;
                                @endphp
                                <tr>
                                    <td><strong>{{ $costCenter->cost_center_code }}</strong></td>
                                    <td>
                                        {{ $costCenter->name }}
                                        @if($costCenter->description)
                                        <br><small class="text-muted">{{ Str::limit($costCenter->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $costCenter->manager->name ?? 'Not Assigned' }}</td>
                                    <td>${{ number_format($costCenter->budget_allocation, 2) }}</td>
                                    <td>${{ number_format($totalExpenses, 2) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">{{ number_format($utilization, 1) }}%</span>
                                            <div class="progress" style="width: 100px; height: 6px;">
                                                <div class="progress-bar bg-{{ 
                                                    $utilization > 90 ? 'danger' : 
                                                    ($utilization > 75 ? 'warning' : 'success')
                                                }}" 
                                                     style="width: {{ min($utilization, 100) }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $costCenter->is_active ? 'success' : 'secondary' }}">
                                            {{ $costCenter->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('finance.cost-centers.show', $costCenter) }}" 
                                               class="btn btn-sm btn-outline-primary">View</a>
                                            <a href="{{ route('finance.cost-centers.edit', $costCenter) }}" 
                                               class="btn btn-sm btn-outline-secondary">Edit</a>
                                            <a href="{{ route('finance.cost-centers.performance', $costCenter) }}" 
                                               class="btn btn-sm btn-outline-info">Performance</a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No cost centers found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $costCenters->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Cost Centers')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Cost Centers</h6>
                    <a href="{{ route('cost-centers.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Cost Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Budget Count</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($costCenters as $costCenter)
                                <tr>
                                    <td>{{ $costCenter->code }}</td>
                                    <td>{{ $costCenter->name }}</td>
                                    <td>{{ $costCenter->description }}</td>
                                    <td>
                                        @if($costCenter->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $costCenter->budgets->count() }}</td>
                                    <td>
                                        <a href="{{ route('cost-centers.show', $costCenter) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('cost-centers.edit', $costCenter) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('cost-centers.destroy', $costCenter) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No cost centers found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $costCenters->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
