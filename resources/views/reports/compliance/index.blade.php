@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clipboard-check fa-fw"></i> Budget Compliance Report
        </h1>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Reports
        </a>
    </div>

    <!-- Year Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Year Filter</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.compliance') }}">
                <div class="row">
                    <div class="col-md-3">
                        <select name="year" class="form-control">
                            @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('reports.compliance') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Revenue Summary -->
    <div class="row mb-4">
        <div class="col-lg-3 mb-3">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Budgeted Revenue
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        ${{ number_format($totalBudgetedRevenue, 2) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 mb-3">
            <div class="card border-left-success shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Actual Revenue
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        ${{ number_format($actualRevenue, 2) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 mb-3">
            <div class="card border-left-{{ $revenueVariance >= 0 ? 'success' : 'danger' }} shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-{{ $revenueVariance >= 0 ? 'success' : 'danger' }} text-uppercase mb-1">
                        Variance
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        ${{ number_format($revenueVariance, 2) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 mb-3">
            <div class="card border-left-{{ $revenueVariancePercentage >= 0 ? 'success' : 'danger' }} shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-{{ $revenueVariancePercentage >= 0 ? 'success' : 'danger' }} text-uppercase mb-1">
                        Variance %
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($revenueVariancePercentage, 1) }}%
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Budget Compliance Details -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Budget vs Actual Breakdown</h6>
        </div>
        <div class="card-body">
            @if(count($budgetCompliance) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Account</th>
                                <th>Budgeted Amount</th>
                                <th>Actual Expense</th>
                                <th>Variance</th>
                                <th>Variance %</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($budgetCompliance as $item)
                            <tr>
                                <td>{{ $item['account_name'] }}</td>
                                <td>${{ number_format($item['budgeted_amount'], 2) }}</td>
                                <td>${{ number_format($item['actual_expense'], 2) }}</td>
                                <td class="text-{{ $item['variance'] >= 0 ? 'success' : 'danger' }}">
                                    ${{ number_format($item['variance'], 2) }}
                                </td>
                                <td class="text-{{ $item['variance_percentage'] >= 0 ? 'success' : 'danger' }}">
                                    {{ number_format($item['variance_percentage'], 1) }}%
                                </td>
                                <td>
                                    <span class="badge badge-{{ $item['variance'] >= 0 ? 'success' : 'danger' }}">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-muted">No budget data available for {{ $year }}.</p>
            @endif
        </div>
    </div>
</div>
@endsection
