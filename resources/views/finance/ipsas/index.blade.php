@extends('layouts.admin')

@section('page-title', 'IPSAS Compliance')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">⚖️ IPSAS Compliance Dashboard</h1>
            <p class="text-muted">International Public Sector Accounting Standards Compliance</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('finance.ipsas.cash-basis') }}" class="btn btn-primary">
                <i class="fas fa-coins"></i> Cash Basis
            </a>
            <a href="{{ route('finance.ipsas.accrual-basis') }}" class="btn btn-success">
                <i class="fas fa-calendar-alt"></i> Accrual Basis
            </a>
            <a href="{{ route('finance.ipsas.notes-disclosure') }}" class="btn btn-info">
                <i class="fas fa-file-alt"></i> Notes
            </a>
        </div>
    </div>

    <!-- Compliance Status Overview -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Compliance Score</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">92%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Standards Implemented</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">25/27</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-balance-scale fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Reviews</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Last Audit</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Dec 2024</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-search fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Key Financial Metrics (IPSAS Compliant)</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="metric-box">
                                <h4 class="text-success">{{ number_format($complianceData['cash_basis_statements']->total_receipts ?? 0) }}</h4>
                                <p class="text-muted">Total Cash Receipts</p>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="metric-box">
                                <h4 class="text-danger">{{ number_format($complianceData['cash_basis_statements']->total_payments ?? 0) }}</h4>
                                <p class="text-muted">Total Cash Payments</p>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="metric-box">
                                <h4 class="text-info">{{ number_format(($complianceData['cash_basis_statements']->total_receipts ?? 0) - ($complianceData['cash_basis_statements']->total_payments ?? 0)) }}</h4>
                                <p class="text-muted">Net Cash Flow</p>
                            </div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="metric-box">
                                <h4 class="text-primary">{{ number_format($complianceData['accrual_basis_statements']['assets'] ?? 0) }}</h4>
                                <p class="text-muted">Total Assets</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- IPSAS Modules -->
    <div class="row mt-4">
        <!-- Cash Basis Statements -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">💰 Cash Basis Statements</h6>
                </div>
                <div class="card-body">
                    <p class="card-text">Financial statements prepared on cash basis as per IPSAS standards.</p>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.ipsas.cash-basis') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-coins me-2"></i>Cash Receipts & Payments
                        </a>
                        <div class="list-group-item">
                            <small class="text-muted">
                                <strong>Receipts:</strong> KES {{ number_format($complianceData['cash_basis_statements']->total_receipts ?? 0) }}<br>
                                <strong>Payments:</strong> KES {{ number_format($complianceData['cash_basis_statements']->total_payments ?? 0) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accrual Basis Statements -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h6 class="m-0 font-weight-bold">📊 Accrual Basis Statements</h6>
                </div>
                <div class="card-body">
                    <p class="card-text">Complete financial statements on accrual basis.</p>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.ipsas.accrual-basis') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-balance-scale me-2"></i>Financial Position
                        </a>
                        <div class="list-group-item">
                            <small class="text-muted">
                                <strong>Assets:</strong> KES {{ number_format($complianceData['accrual_basis_statements']['assets'] ?? 0) }}<br>
                                <strong>Liabilities:</strong> KES {{ number_format($complianceData['accrual_basis_statements']['liabilities'] ?? 0) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Comparison -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">📈 Budgetary Comparison</h6>
                </div>
                <div class="card-body">
                    <p class="card-text">Comparison of budget vs actual performance.</p>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.ipsas.budgetary-comparison') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-bar me-2"></i>Budget vs Actual
                        </a>
                        <div class="list-group-item">
                            <small class="text-muted">
                                <strong>Budget:</strong> KES {{ number_format($complianceData['budget_execution']->sum('approved_amount') ?? 0) }}<br>
                                <strong>Actual:</strong> KES {{ number_format($complianceData['budget_execution']->sum('actual_amount') ?? 0) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segment Reporting -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">🏢 Segment Reporting</h6>
                </div>
                <div class="card-body">
                    <p class="card-text">Financial information by business segments.</p>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.ipsas.segment-reporting') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-building me-2"></i>By Function
                        </a>
                        <a href="{{ route('finance.ipsas.segment-reporting') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-map-marker-alt me-2"></i>By Location
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes Disclosure -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">
                    <h6 class="m-0 font-weight-bold">📝 Notes Disclosure</h6>
                </div>
                <div class="card-body">
                    <p class="card-text">Comprehensive notes to financial statements.</p>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.ipsas.notes-disclosure') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-alt me-2"></i>Accounting Policies
                        </a>
                        <a href="{{ route('finance.ipsas.notes-disclosure') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-exclamation-triangle me-2"></i>Commitments
                        </a>
                        <a href="{{ route('finance.ipsas.notes-disclosure') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-question-circle me-2"></i>Contingencies
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Compliance Tools -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-dark text-white">
                    <h6 class="m-0 font-weight-bold">🔧 Compliance Tools</h6>
                </div>
                <div class="card-body">
                    <p class="card-text">Tools and utilities for IPSAS compliance.</p>
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-check-circle me-2"></i>Compliance Checker
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-download me-2"></i>Export Reports
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-calendar-check me-2"></i>Audit Schedule
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Compliance Status Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">IPSAS Standards Implementation Status</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>IPSAS Standard</th>
                                    <th>Description</th>
                                    <th>Implementation Status</th>
                                    <th>Compliance Level</th>
                                    <th>Last Review</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>IPSAS 1</td>
                                    <td>Presentation of Financial Statements</td>
                                    <td><span class="badge badge-success">Implemented</span></td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width: 95%">95%</div>
                                        </div>
                                    </td>
                                    <td>{{ date('Y-m-d', strtotime('-30 days')) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Review
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>IPSAS 2</td>
                                    <td>Cash Flow Statements</td>
                                    <td><span class="badge badge-success">Implemented</span></td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width: 90%">90%</div>
                                        </div>
                                    </td>
                                    <td>{{ date('Y-m-d', strtotime('-25 days')) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Review
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>IPSAS 17</td>
                                    <td>Property, Plant and Equipment</td>
                                    <td><span class="badge badge-warning">In Progress</span></td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" style="width: 75%">75%</div>
                                        </div>
                                    </td>
                                    <td>{{ date('Y-m-d', strtotime('-15 days')) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Update
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>IPSAS 23</td>
                                    <td>Revenue from Non-Exchange Transactions</td>
                                    <td><span class="badge badge-success">Implemented</span></td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width: 88%">88%</div>
                                        </div>
                                    </td>
                                    <td>{{ date('Y-m-d', strtotime('-20 days')) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Review
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.metric-box {
    padding: 1rem;
    border-radius: 0.375rem;
    background-color: #f8f9fc;
    margin-bottom: 1rem;
}

.progress {
    height: 20px;
}
</style>
@endsection
