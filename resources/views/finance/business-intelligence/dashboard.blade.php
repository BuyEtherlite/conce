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
                        <a href="{{ route('finance.business-intelligence.index') }}" class="btn btn-secondary">
                            <i class="fas fa-home"></i> Overview
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
                    <!-- Financial Health Score -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3 class="card-title">Financial Health Score</h3>
                                    <h1 class="display-2">{{ number_format($dashboardMetrics['financial_health_score']) }}%</h1>
                                    <p class="card-text">Overall financial performance indicator</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Indicators -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5>ROI</h5>
                                    <h3>{{ number_format($dashboardMetrics['performance_indicators']['roi'], 2) }}%</h3>
                                    <small>Return on Investment</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5>Liquidity Ratio</h5>
                                    <h3>{{ number_format($dashboardMetrics['performance_indicators']['liquidity_ratio'], 2) }}</h3>
                                    <small>Current liquidity position</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5>Debt to Equity</h5>
                                    <h3>{{ number_format($dashboardMetrics['performance_indicators']['debt_to_equity'], 2) }}</h3>
                                    <small>Financial leverage ratio</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body">
                                    <h5>Operating Margin</h5>
                                    <h3>{{ number_format($dashboardMetrics['performance_indicators']['operating_margin'], 2) }}%</h3>
                                    <small>Operational efficiency</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Risk Indicators -->
                    <div class="row mb-4">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Risk Indicators</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <h6>Concentration Risk</h6>
                                            @php
                                                $concentrationRisk = $dashboardMetrics['risk_indicators']['concentration_risk'];
                                                $riskClass = $concentrationRisk === 'Low' ? 'success' : ($concentrationRisk === 'Medium' ? 'warning' : 'danger');
                                            @endphp
                                            <span class="badge bg-{{ $riskClass }}">{{ $concentrationRisk }}</span>
                                        </div>
                                        <div class="col-6">
                                            <h6>Budget Overrun Risk</h6>
                                            @php
                                                $budgetRisk = $dashboardMetrics['risk_indicators']['budget_overrun_risk'];
                                                $budgetRiskClass = $budgetRisk === 'Low' ? 'success' : ($budgetRisk === 'Medium' ? 'warning' : 'danger');
                                            @endphp
                                            <span class="badge bg-{{ $budgetRiskClass }}">{{ $budgetRisk }}</span>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <h6>Cash Flow Risk</h6>
                                            @php
                                                $cashFlowRisk = $dashboardMetrics['risk_indicators']['cash_flow_risk'];
                                                $cashFlowRiskClass = $cashFlowRisk === 'Low' ? 'success' : ($cashFlowRisk === 'Medium' ? 'warning' : 'danger');
                                            @endphp
                                            <span class="badge bg-{{ $cashFlowRiskClass }}">{{ $cashFlowRisk }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Operational Metrics -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Operational Metrics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Invoice Processing Time:</strong>
                                        {{ number_format($dashboardMetrics['operational_metrics']['invoice_processing_time'], 1) }} days
                                    </div>
                                    <div class="mb-3">
                                        <strong>Payment Collection Period:</strong>
                                        {{ number_format($dashboardMetrics['operational_metrics']['payment_collection_period']) }} days
                                    </div>
                                    <div class="mb-3">
                                        <strong>Expense Approval Time:</strong>
                                        {{ number_format($dashboardMetrics['operational_metrics']['expense_approval_time'], 1) }} days
                                    </div>
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
                                            <a href="{{ route('finance.business-intelligence.analytics') }}" class="btn btn-outline-primary btn-block">
                                                <i class="fas fa-chart-line"></i><br>
                                                View Analytics
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('finance.business-intelligence.kpi-reports') }}" class="btn btn-outline-success btn-block">
                                                <i class="fas fa-chart-bar"></i><br>
                                                KPI Reports
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('finance.general-ledger.index') }}" class="btn btn-outline-info btn-block">
                                                <i class="fas fa-book"></i><br>
                                                General Ledger
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('finance.budgets.index') }}" class="btn btn-outline-warning btn-block">
                                                <i class="fas fa-calculator"></i><br>
                                                Budget Analysis
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
@endsection
