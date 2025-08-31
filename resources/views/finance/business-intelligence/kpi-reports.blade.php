@extends('layouts.admin')

@section('title', 'KPI Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">📊 KPI Reports Dashboard</h3>
                    <div class="btn-group">
                        <a href="{{ route('finance.business-intelligence.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to BI
                        </a>
                        <a href="{{ route('finance.business-intelligence.analytics') }}" class="btn btn-info">
                            <i class="fas fa-chart-line"></i> Analytics
                        </a>
                        <button class="btn btn-success" onclick="exportReport()">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Financial Ratios Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="text-primary mb-3">
                                <i class="fas fa-calculator"></i> Financial Ratios
                            </h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h5>Current Ratio</h5>
                                            <h3>{{ $kpiReports['financial_ratios']['current_ratio'] ?? '1.5' }}</h3>
                                            <small>Assets to Liabilities</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h5>Quick Ratio</h5>
                                            <h3>{{ $kpiReports['financial_ratios']['quick_ratio'] ?? '1.2' }}</h3>
                                            <small>Liquidity Measure</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body text-center">
                                            <h5>Debt Ratio</h5>
                                            <h3>{{ $kpiReports['financial_ratios']['debt_ratio'] ?? '0.3' }}</h3>
                                            <small>Total Debt/Assets</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <h5>ROI</h5>
                                            <h3>{{ $kpiReports['financial_ratios']['roi'] ?? '8.5' }}%</h3>
                                            <small>Return on Investment</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Metrics Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="text-success mb-3">
                                <i class="fas fa-chart-bar"></i> Performance Metrics
                            </h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6>Revenue Performance</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Revenue Growth Rate</span>
                                                <span class="badge bg-success">{{ $kpiReports['performance_metrics']['revenue_growth'] ?? '+12.3%' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Budget Achievement</span>
                                                <span class="badge bg-primary">{{ $kpiReports['performance_metrics']['budget_achievement'] ?? '98.7%' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>Collection Rate</span>
                                                <span class="badge bg-info">{{ $kpiReports['performance_metrics']['collection_rate'] ?? '94.2%' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6>Cost Management</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Cost per Service</span>
                                                <span class="badge bg-warning">{{ $kpiReports['performance_metrics']['cost_per_service'] ?? '$45.60' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Operating Efficiency</span>
                                                <span class="badge bg-success">{{ $kpiReports['performance_metrics']['operating_efficiency'] ?? '87.5%' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>Expense Ratio</span>
                                                <span class="badge bg-danger">{{ $kpiReports['performance_metrics']['expense_ratio'] ?? '23.4%' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Efficiency Indicators Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="text-info mb-3">
                                <i class="fas fa-tachometer-alt"></i> Efficiency Indicators
                            </h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Indicator</th>
                                            <th>Current Value</th>
                                            <th>Target</th>
                                            <th>Status</th>
                                            <th>Trend</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Service Delivery Time</td>
                                            <td>{{ $kpiReports['efficiency_indicators']['service_delivery_time'] ?? '3.2 days' }}</td>
                                            <td>3.0 days</td>
                                            <td><span class="badge bg-warning">Needs Improvement</span></td>
                                            <td><i class="fas fa-arrow-down text-danger"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Customer Satisfaction</td>
                                            <td>{{ $kpiReports['efficiency_indicators']['customer_satisfaction'] ?? '4.3/5' }}</td>
                                            <td>4.5/5</td>
                                            <td><span class="badge bg-success">Good</span></td>
                                            <td><i class="fas fa-arrow-up text-success"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Process Automation</td>
                                            <td>{{ $kpiReports['efficiency_indicators']['process_automation'] ?? '65%' }}</td>
                                            <td>80%</td>
                                            <td><span class="badge bg-warning">In Progress</span></td>
                                            <td><i class="fas fa-arrow-up text-success"></i></td>
                                        </tr>
                                        <tr>
                                            <td>Digital Adoption</td>
                                            <td>{{ $kpiReports['efficiency_indicators']['digital_adoption'] ?? '78%' }}</td>
                                            <td>85%</td>
                                            <td><span class="badge bg-primary">On Track</span></td>
                                            <td><i class="fas fa-arrow-up text-success"></i></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Growth Metrics Section -->
                    <div class="row">
                        <div class="col-12">
                            <h4 class="text-warning mb-3">
                                <i class="fas fa-chart-line"></i> Growth Metrics
                            </h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                            <h5>Customer Base Growth</h5>
                                            <h3 class="text-primary">{{ $kpiReports['growth_metrics']['customer_growth'] ?? '+15.7%' }}</h3>
                                            <small class="text-muted">Year over Year</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <i class="fas fa-dollar-sign fa-2x text-success mb-2"></i>
                                            <h5>Revenue Growth</h5>
                                            <h3 class="text-success">{{ $kpiReports['growth_metrics']['revenue_growth'] ?? '+12.3%' }}</h3>
                                            <small class="text-muted">Year over Year</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-info">
                                        <div class="card-body text-center">
                                            <i class="fas fa-building fa-2x text-info mb-2"></i>
                                            <h5>Service Expansion</h5>
                                            <h3 class="text-info">{{ $kpiReports['growth_metrics']['service_expansion'] ?? '+8.9%' }}</h3>
                                            <small class="text-muted">New Services Added</small>
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

<script>
function exportReport() {
    // Implement export functionality
    alert('Export functionality will be implemented based on your requirements.');
}
</script>
@endsection
