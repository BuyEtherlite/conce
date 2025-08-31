@extends('layouts.admin')

@section('page-title', 'Property Valuations')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💰 Property Valuations</h4>
        <button class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>New Valuation
        </button>
    </div>

    <!-- Valuation Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-home fa-2x text-primary mb-2"></i>
                    <h5>Total Properties</h5>
                    <h3 class="text-primary">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-2x text-success mb-2"></i>
                    <h5>Total Value</h5>
                    <h3 class="text-success">R 0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-check fa-2x text-warning mb-2"></i>
                    <h5>This Month</h5>
                    <h3 class="text-warning">0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-clipboard-check fa-2x text-info mb-2"></i>
                    <h5>Completed</h5>
                    <h3 class="text-info">0</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Search & Filter</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label for="property-search" class="form-label">Property Search</label>
                    <input type="text" class="form-control" id="property-search" placeholder="Search by address...">
                </div>
                <div class="col-md-3">
                    <label for="valuation-type" class="form-label">Valuation Type</label>
                    <select class="form-control" id="valuation-type">
                        <option value="">All Types</option>
                        <option value="market">Market Valuation</option>
                        <option value="municipal">Municipal Valuation</option>
                        <option value="insurance">Insurance Valuation</option>
                        <option value="mortgage">Mortgage Valuation</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status-filter" class="form-label">Status</label>
                    <select class="form-control" id="status-filter">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date-range" class="form-label">Date Range</label>
                    <select class="form-control" id="date-range">
                        <option value="">All Dates</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Valuations Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Property Valuations</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Property ID</th>
                            <th>Address</th>
                            <th>Valuation Type</th>
                            <th>Current Value</th>
                            <th>Previous Value</th>
                            <th>Change</th>
                            <th>Date</th>
                            <th>Valuer</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>PROP001</strong></td>
                            <td>123 Main Street, Central</td>
                            <td><span class="badge bg-primary">Market</span></td>
                            <td><strong>R 850,000</strong></td>
                            <td>R 800,000</td>
                            <td><span class="text-success">+R 50,000 (+6.3%)</span></td>
                            <td>{{ date('Y-m-d') }}</td>
                            <td>John Valuer</td>
                            <td><span class="badge bg-success">Completed</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-secondary" title="Print Report">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>PROP002</strong></td>
                            <td>456 Oak Avenue, Suburb</td>
                            <td><span class="badge bg-info">Municipal</span></td>
                            <td><strong>R 650,000</strong></td>
                            <td>R 620,000</td>
                            <td><span class="text-success">+R 30,000 (+4.8%)</span></td>
                            <td>{{ date('Y-m-d', strtotime('-2 days')) }}</td>
                            <td>Sarah Assessor</td>
                            <td><span class="badge bg-warning">In Progress</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-secondary" title="Print Report">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>PROP003</strong></td>
                            <td>789 Pine Road, Industrial</td>
                            <td><span class="badge bg-warning">Insurance</span></td>
                            <td><strong>R 1,200,000</strong></td>
                            <td>R 1,150,000</td>
                            <td><span class="text-success">+R 50,000 (+4.3%)</span></td>
                            <td>{{ date('Y-m-d', strtotime('-5 days')) }}</td>
                            <td>Mike Inspector</td>
                            <td><span class="badge bg-secondary">Pending</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-secondary" title="Print Report">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Valuations pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <span class="page-link">Previous</span>
                    </li>
                    <li class="page-item active">
                        <span class="page-link">1</span>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-body i.fa-2x {
    opacity: 0.8;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.badge {
    font-size: 0.75em;
}

.text-success {
    color: #28a745 !important;
}

.text-danger {
    color: #dc3545 !important;
}
</style>
@endsection
