@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Advanced Financial Reports</h1>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-download"></i> Export Options
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="exportAll('pdf')">Export All as PDF</a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportAll('excel')">Export All as Excel</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="scheduleReports()">Schedule Reports</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Categories -->
    <div class="row">
        <!-- Financial Statements -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-line"></i> Financial Statements
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.reports.advanced.statement-of-financial-position') }}" 
                           class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-balance-scale me-2"></i>
                                    Statement of Financial Position
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="{{ route('finance.reports.advanced.statement-of-financial-performance') }}" 
                           class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-chart-bar me-2"></i>
                                    Statement of Financial Performance
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="{{ route('finance.reports.advanced.cash-flow-statement') }}" 
                           class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-water me-2"></i>
                                    Cash Flow Statement
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="{{ route('finance.reports.advanced.trial-balance') }}" 
                           class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-list-ol me-2"></i>
                                    Trial Balance
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget & Performance Reports -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-clipboard-list"></i> Budget & Performance
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.reports.advanced.budget-variance') }}" 
                           class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-chart-area me-2"></i>
                                    Budget Variance Report
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-bullseye me-2"></i>
                                    Performance Indicators
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-tachometer-alt me-2"></i>
                                    Efficiency Ratios
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-target me-2"></i>
                                    Goal Achievement
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receivables & Payables -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-money-bill-wave"></i> Receivables & Payables
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.reports.advanced.ageing-report', ['type' => 'receivables']) }}" 
                           class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-clock me-2"></i>
                                    Receivables Ageing
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="{{ route('finance.reports.advanced.ageing-report', ['type' => 'payables']) }}" 
                           class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-hourglass-half me-2"></i>
                                    Payables Ageing
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-users me-2"></i>
                                    Customer Analysis
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-truck me-2"></i>
                                    Vendor Analysis
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Asset Management -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-boxes"></i> Asset Management
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.reports.advanced.fixed-asset-register') }}" 
                           class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-building me-2"></i>
                                    Fixed Asset Register
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-chart-line me-2"></i>
                                    Depreciation Schedule
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-clipboard-check me-2"></i>
                                    Asset Verification
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Asset Disposals
                                </div>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Report Generator -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Report Generator</h6>
                </div>
                <div class="card-body">
                    <form id="quickReportForm">
                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-control" id="reportType" name="report_type">
                                    <option value="">Select Report Type</option>
                                    <option value="trial-balance">Trial Balance</option>
                                    <option value="financial-position">Financial Position</option>
                                    <option value="financial-performance">Financial Performance</option>
                                    <option value="cash-flow">Cash Flow</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" id="fromDate" name="from_date" value="{{ date('Y-m-01') }}">
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" id="toDate" name="to_date" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-3">
                                <div class="btn-group w-100">
                                    <button type="button" class="btn btn-primary" onclick="generateReport()">
                                        <i class="fas fa-chart-bar"></i> Generate
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="exportReport()">
                                        <i class="fas fa-download"></i> Export
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generateReport() {
    const reportType = document.getElementById('reportType').value;
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;
    
    if (!reportType) {
        alert('Please select a report type');
        return;
    }
    
    const url = `/finance/reports/advanced/${reportType}?from_date=${fromDate}&to_date=${toDate}`;
    window.open(url, '_blank');
}

function exportReport() {
    const reportType = document.getElementById('reportType').value;
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;
    
    if (!reportType) {
        alert('Please select a report type');
        return;
    }
    
    const url = `/finance/reports/advanced/export/${reportType}?from_date=${fromDate}&to_date=${toDate}`;
    window.open(url, '_blank');
}

function exportAll(format) {
    alert(`Exporting all reports as ${format.toUpperCase()}...`);
}

function scheduleReports() {
    alert('Report scheduling feature coming soon!');
}
</script>
@endsection
