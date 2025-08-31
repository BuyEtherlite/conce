@extends('layouts.app')

@section('page-title', 'Financial Reports')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📊 Financial Reports</h4>
        <div>
            <a href="{{ route('finance.reports.advanced.index') }}" class="btn btn-primary">
                <i class="fas fa-chart-line me-1"></i>Advanced Reports
            </a>
            <a href="{{ route('finance.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Finance
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Financial Statements -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0"><i class="fas fa-balance-scale me-2"></i>Financial Statements</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.reports.balance-sheet') }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-chart-bar me-2"></i>Balance Sheet</span>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="{{ route('finance.reports.trial-balance') }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-scale me-2"></i>Trial Balance</span>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="{{ route('finance.reports.income-statement') }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-chart-line me-2"></i>Income Statement</span>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="{{ route('finance.reports.cash-flow') }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-water me-2"></i>Cash Flow Statement</span>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Reports -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h6 class="m-0"><i class="fas fa-chart-pie me-2"></i>Management Reports</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.reports.budget-variance') }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-calculator me-2"></i>Budget vs Actual</span>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="{{ route('finance.reports.aging') }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-clock me-2"></i>Aging Report</span>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="{{ route('finance.reports.revenue') }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-money-bill-wave me-2"></i>Revenue Analysis</span>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="{{ route('finance.reports.expense') }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-receipt me-2"></i>Expense Analysis</span>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tax & Compliance -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark">
                    <h6 class="m-0"><i class="fas fa-gavel me-2"></i>Tax & Compliance</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.tax-management.index') }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-percentage me-2"></i>Tax Reports</span>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="{{ route('finance.ipsas.index') }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-shield-alt me-2"></i>IPSAS Compliance</span>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="{{ route('finance.reports.audit-trail') }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-search me-2"></i>Audit Trail</span>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0"><i class="fas fa-tachometer-alt me-2"></i>Quick Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 text-center border-end">
                            <h5 class="text-success">${{ number_format(450000, 2) }}</h5>
                            <small class="text-muted">Monthly Revenue</small>
                        </div>
                        <div class="col-6 text-center">
                            <h5 class="text-danger">${{ number_format(280000, 2) }}</h5>
                            <small class="text-muted">Monthly Expenses</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6 text-center border-end">
                            <h5 class="text-primary">${{ number_format(1250000, 2) }}</h5>
                            <small class="text-muted">Total Assets</small>
                        </div>
                        <div class="col-6 text-center">
                            <h5 class="text-warning">${{ number_format(850000, 2) }}</h5>
                            <small class="text-muted">Total Liabilities</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
