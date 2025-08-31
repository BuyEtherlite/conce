@extends('layouts.admin')

@section('title', 'Financial Analytics')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">📊 Financial Analytics</h3>
                    <div class="btn-group">
                        <a href="{{ route('finance.business-intelligence.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                        <button class="btn btn-primary" onclick="exportAnalytics()">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Revenue Analysis -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="m-0"><i class="fas fa-chart-line"></i> Revenue Analysis</h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($analyticsData['revenue_analysis']))
                                        <div class="row">
                                            <div class="col-md-6">
                                                <canvas id="revenueChart"></canvas>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Revenue Metrics</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <tbody>
                                                            @if(isset($analyticsData['revenue_analysis']['total']))
                                                            <tr>
                                                                <td>Total Revenue</td>
                                                                <td class="text-right">${{ number_format($analyticsData['revenue_analysis']['total'], 2) }}</td>
                                                            </tr>
                                                            @endif
                                                            @if(isset($analyticsData['revenue_analysis']['growth_rate']))
                                                            <tr>
                                                                <td>Growth Rate</td>
                                                                <td class="text-right">{{ $analyticsData['revenue_analysis']['growth_rate'] }}%</td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted">No revenue analysis data available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expense Analysis -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="m-0"><i class="fas fa-chart-pie"></i> Expense Analysis</h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($analyticsData['expense_analysis']))
                                        <div class="row">
                                            <div class="col-md-6">
                                                <canvas id="expenseChart"></canvas>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Expense Breakdown</h6>
                                                @if(isset($analyticsData['expense_analysis']['by_category']))
                                                    <div class="table-responsive">
                                                        <table class="table table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th>Category</th>
                                                                    <th class="text-right">Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($analyticsData['expense_analysis']['by_category'] as $category)
                                                                <tr>
                                                                    <td>{{ $category['name'] ?? 'Unknown' }}</td>
                                                                    <td class="text-right">${{ number_format($category['amount'] ?? 0, 2) }}</td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted">No expense analysis data available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profitability Analysis -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="m-0"><i class="fas fa-calculator"></i> Profitability Analysis</h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($analyticsData['profitability_analysis']))
                                        <div class="row">
                                            @if(isset($analyticsData['profitability_analysis']['gross_profit']))
                                            <div class="col-md-3">
                                                <div class="stat-card text-center">
                                                    <h4 class="text-success">${{ number_format($analyticsData['profitability_analysis']['gross_profit'], 2) }}</h4>
                                                    <p class="text-muted">Gross Profit</p>
                                                </div>
                                            </div>
                                            @endif
                                            @if(isset($analyticsData['profitability_analysis']['net_profit']))
                                            <div class="col-md-3">
                                                <div class="stat-card text-center">
                                                    <h4 class="text-primary">${{ number_format($analyticsData['profitability_analysis']['net_profit'], 2) }}</h4>
                                                    <p class="text-muted">Net Profit</p>
                                                </div>
                                            </div>
                                            @endif
                                            @if(isset($analyticsData['profitability_analysis']['profit_margin']))
                                            <div class="col-md-3">
                                                <div class="stat-card text-center">
                                                    <h4 class="text-warning">{{ $analyticsData['profitability_analysis']['profit_margin'] }}%</h4>
                                                    <p class="text-muted">Profit Margin</p>
                                                </div>
                                            </div>
                                            @endif
                                            @if(isset($analyticsData['profitability_analysis']['roi']))
                                            <div class="col-md-3">
                                                <div class="stat-card text-center">
                                                    <h4 class="text-info">{{ $analyticsData['profitability_analysis']['roi'] }}%</h4>
                                                    <p class="text-muted">ROI</p>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-muted">No profitability analysis data available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Variance Analysis -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="m-0"><i class="fas fa-chart-bar"></i> Variance Analysis</h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($analyticsData['variance_analysis']))
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Account</th>
                                                        <th class="text-right">Budget</th>
                                                        <th class="text-right">Actual</th>
                                                        <th class="text-right">Variance</th>
                                                        <th class="text-right">Variance %</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(is_array($analyticsData['variance_analysis']))
                                                        @foreach($analyticsData['variance_analysis'] as $variance)
                                                        <tr>
                                                            <td>{{ $variance['account'] ?? 'Unknown' }}</td>
                                                            <td class="text-right">${{ number_format($variance['budget'] ?? 0, 2) }}</td>
                                                            <td class="text-right">${{ number_format($variance['actual'] ?? 0, 2) }}</td>
                                                            <td class="text-right {{ ($variance['variance'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                                                ${{ number_format($variance['variance'] ?? 0, 2) }}
                                                            </td>
                                                            <td class="text-right {{ ($variance['variance_percent'] ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                                                                {{ number_format($variance['variance_percent'] ?? 0, 1) }}%
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">No variance analysis data available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    padding: 20px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 15px;
}
</style>

<script>
function exportAnalytics() {
    // Export functionality
    window.print();
}

// Charts can be added here using Chart.js or similar library
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts if data is available
    console.log('Analytics page loaded');
});
</script>
@endsection
