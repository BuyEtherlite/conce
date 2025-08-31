@extends('layouts.admin')

@section('title', 'Business Intelligence Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">🧠 Business Intelligence Dashboard</h3>
                    <div class="btn-group">
                        <a href="{{ route('finance.business-intelligence.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-tachometer-alt"></i> Full Dashboard
                        </a>
                        <a href="{{ route('finance.business-intelligence.analytics') }}" class="btn btn-info">
                            <i class="fas fa-chart-line"></i> Analytics
                        </a>
                        <a href="{{ route('finance.business-intelligence.kpi-reports') }}" class="btn btn-success">
                            <i class="fas fa-chart-bar"></i> KPI Reports
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Key Performance Indicators -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>Total Revenue</h5>
                                            <h3>${{ number_format($kpiData['total_revenue'], 2) }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-dollar-sign fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>Total Expenses</h5>
                                            <h3>${{ number_format($kpiData['total_expenses'], 2) }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-money-bill-wave fa-2x"></i>
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
                                            <h5>Budget Variance</h5>
                                            <h3>{{ number_format($kpiData['budget_variance']['variance_percentage'], 1) }}%</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-percentage fa-2x"></i>
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
                                            <h5>Outstanding Receivables</h5>
                                            <h3>${{ number_format($kpiData['outstanding_receivables'], 2) }}</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Revenue by Month</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="revenueChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Cash Flow Trend</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="cashFlowChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Budget Utilization -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Budget Utilization by Cost Center</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Cost Center</th>
                                                    <th>Budgeted</th>
                                                    <th>Spent</th>
                                                    <th>Utilization</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($kpiData['budget_utilization'] as $utilization)
                                                <tr>
                                                    <td>{{ $utilization['cost_center'] }}</td>
                                                    <td>${{ number_format($utilization['budgeted'], 2) }}</td>
                                                    <td>${{ number_format($utilization['spent'], 2) }}</td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar 
                                                                @if($utilization['utilization'] > 100) bg-danger
                                                                @elseif($utilization['utilization'] > 80) bg-warning
                                                                @else bg-success
                                                                @endif" 
                                                                style="width: {{ min($utilization['utilization'], 100) }}%">
                                                                {{ number_format($utilization['utilization'], 1) }}%
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($utilization['utilization'] > 100)
                                                            <span class="badge bg-danger">Over Budget</span>
                                                        @elseif($utilization['utilization'] > 80)
                                                            <span class="badge bg-warning">Near Limit</span>
                                                        @else
                                                            <span class="badge bg-success">On Track</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Top Expense Categories</h5>
                                </div>
                                <div class="card-body">
                                    @foreach($kpiData['top_expense_categories'] as $category)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>{{ $category->account->account_name ?? 'Unknown' }}</span>
                                        <strong>${{ number_format($category->total_amount, 0) }}</strong>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <a href="{{ route('finance.business-intelligence.trend-analysis') }}" class="btn btn-outline-primary btn-block">
                                                <i class="fas fa-chart-line"></i><br>
                                                Trend Analysis
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('finance.general-ledger.index') }}" class="btn btn-outline-success btn-block">
                                                <i class="fas fa-book"></i><br>
                                                General Ledger
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('finance.budgets.index') }}" class="btn btn-outline-warning btn-block">
                                                <i class="fas fa-calculator"></i><br>
                                                Budget Management
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('finance.reports.index') }}" class="btn btn-outline-info btn-block">
                                                <i class="fas fa-file-alt"></i><br>
                                                Financial Reports
                                            </a>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($kpiData['revenue_by_month']->pluck('month_name')) !!},
        datasets: [{
            label: 'Revenue',
            data: {!! json_encode($kpiData['revenue_by_month']->pluck('revenue')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Cash Flow Chart
const cashFlowCtx = document.getElementById('cashFlowChart').getContext('2d');
const cashFlowChart = new Chart(cashFlowCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($kpiData['cash_flow_trend']->pluck('month')) !!},
        datasets: [{
            label: 'Cash In',
            data: {!! json_encode($kpiData['cash_flow_trend']->pluck('cash_in')) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }, {
            label: 'Cash Out',
            data: {!! json_encode($kpiData['cash_flow_trend']->pluck('cash_out')) !!},
            backgroundColor: 'rgba(255, 99, 132, 0.5)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
@endsection
