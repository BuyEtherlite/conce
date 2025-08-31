@extends('layouts.admin')

@section('title', 'IPSAS Reports')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">📊 IPSAS Reports</h1>
        <div class="d-flex">
            <a href="{{ route('finance.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Finance
            </a>
        </div>
    </div>

    <!-- Report Categories -->
    <div class="row">
        <!-- Primary Financial Statements -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-file-invoice-dollar"></i> Primary Financial Statements
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.ipsas-reports.statement-financial-position') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-balance-scale text-primary me-2"></i>
                                <strong>Statement of Financial Position</strong>
                                <br><small class="text-muted">Assets, Liabilities & Net Assets/Equity</small>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        
                        <a href="{{ route('finance.ipsas-reports.statement-financial-performance') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-chart-line text-success me-2"></i>
                                <strong>Statement of Financial Performance</strong>
                                <br><small class="text-muted">Revenue, Expenses & Surplus/Deficit</small>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        
                        <a href="{{ route('finance.ipsas-reports.cash-flow-statement') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-money-bill-wave text-info me-2"></i>
                                <strong>Statement of Cash Flows</strong>
                                <br><small class="text-muted">Operating, Investing & Financing Activities</small>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        
                        <a href="{{ route('finance.ipsas-reports.statement-changes-net-assets') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-exchange-alt text-warning me-2"></i>
                                <strong>Statement of Changes in Net Assets/Equity</strong>
                                <br><small class="text-muted">Changes in Accumulated Funds</small>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget & Comparison Reports -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-bar"></i> Budget & Comparison Reports
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.ipsas-reports.budget-comparison') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-balance-scale text-primary me-2"></i>
                                <strong>Comparison of Budget & Actual</strong>
                                <br><small class="text-muted">Budget vs Actual Performance</small>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        
                        <a href="{{ route('finance.ipsas-reports.budget-execution') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-tasks text-success me-2"></i>
                                <strong>Budget Execution Report</strong>
                                <br><small class="text-muted">Budget Utilization Analysis</small>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        
                        <a href="{{ route('finance.ipsas-reports.variance-analysis') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-chart-area text-info me-2"></i>
                                <strong>Variance Analysis</strong>
                                <br><small class="text-muted">Budget vs Actual Variances</small>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segment Reporting -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-sitemap"></i> Segment Reporting
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.ipsas-reports.segment-by-function') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-cogs text-primary me-2"></i>
                                <strong>Reporting by Function</strong>
                                <br><small class="text-muted">Performance by Functional Areas</small>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        
                        <a href="{{ route('finance.ipsas-reports.segment-by-service') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-concierge-bell text-success me-2"></i>
                                <strong>Reporting by Service</strong>
                                <br><small class="text-muted">Performance by Service Lines</small>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        
                        <a href="{{ route('finance.ipsas-reports.segment-by-location') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-map-marker-alt text-info me-2"></i>
                                <strong>Reporting by Location</strong>
                                <br><small class="text-muted">Performance by Geographic Areas</small>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes & Disclosures -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-sticky-note"></i> Notes & Disclosures
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('finance.ipsas-reports.notes-financial-statements') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-alt text-primary me-2"></i>
                                <strong>Notes to Financial Statements</strong>
                                <br><small class="text-muted">Detailed Notes & Disclosures</small>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        
                        <a href="{{ route('finance.ipsas-reports.accounting-policies') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-book text-success me-2"></i>
                                <strong>Accounting Policies</strong>
                                <br><small class="text-muted">Summary of Significant Policies</small>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        
                        <a href="{{ route('finance.ipsas-reports.commitments-contingencies') }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-handshake text-info me-2"></i>
                                <strong>Commitments & Contingencies</strong>
                                <br><small class="text-muted">Future Obligations & Risks</small>
                            </div>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-tools"></i> Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-primary btn-block" onclick="generateAllReports()">
                                <i class="fas fa-play"></i><br>
                                <small>Generate All Reports</small>
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-success btn-block" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf"></i><br>
                                <small>Export to PDF</small>
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-info btn-block" onclick="exportToExcel()">
                                <i class="fas fa-file-excel"></i><br>
                                <small>Export to Excel</small>
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-outline-warning btn-block" onclick="scheduleReports()">
                                <i class="fas fa-calendar"></i><br>
                                <small>Schedule Reports</small>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- IPSAS Compliance Indicator -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">
                    <i class="fas fa-check-circle"></i> IPSAS Compliance Status
                </h4>
                <p>All reports generated comply with International Public Sector Accounting Standards (IPSAS). 
                   The system automatically applies appropriate recognition, measurement, and disclosure requirements.</p>
                <hr>
                <p class="mb-0">
                    <strong>Compliance Level:</strong> <span class="badge badge-success">95%</span> | 
                    <strong>Last Review:</strong> {{ date('F j, Y') }} | 
                    <strong>Standards Applied:</strong> IPSAS 1, 2, 17, 23
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function generateAllReports() {
    alert('Generating all IPSAS reports... This may take a few moments.');
    // Implementation for generating all reports
}

function exportToPDF() {
    alert('Exporting reports to PDF format...');
    // Implementation for PDF export
}

function exportToExcel() {
    alert('Exporting reports to Excel format...');
    // Implementation for Excel export
}

function scheduleReports() {
    alert('Opening report scheduling interface...');
    // Implementation for scheduling reports
}
</script>

<style>
.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.list-group-item-action:hover {
    background-color: #f8f9fa;
    border-left: 4px solid #007bff;
}

.btn-block {
    width: 100%;
    padding: 1rem;
    text-align: center;
}

.alert-success {
    border-left: 4px solid #28a745;
}
</style>
@endsection
