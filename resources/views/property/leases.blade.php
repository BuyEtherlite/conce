@extends('layouts.admin')

@section('page-title', 'Property Leases')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📄 Property Leases</h4>
        <button class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>New Lease
        </button>
    </div>

    <!-- Lease Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-file-contract fa-2x text-primary mb-2"></i>
                    <h5>Active Leases</h5>
                    <h3 class="text-primary">25</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                    <h5>Expiring Soon</h5>
                    <h3 class="text-warning">5</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-times fa-2x text-danger mb-2"></i>
                    <h5>Expired</h5>
                    <h3 class="text-danger">2</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-2x text-success mb-2"></i>
                    <h5>Total Value</h5>
                    <h3 class="text-success">R 2.5M</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Search & Filter</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Search by property or tenant...">
                </div>
                <div class="col-md-3">
                    <select class="form-control">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="expired">Expired</option>
                        <option value="terminated">Terminated</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control">
                        <option value="">All Property Types</option>
                        <option value="residential">Residential</option>
                        <option value="commercial">Commercial</option>
                        <option value="industrial">Industrial</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-primary">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Leases Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Property Leases</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Lease ID</th>
                            <th>Property</th>
                            <th>Tenant</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Monthly Rent</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>LS001</strong></td>
                            <td>123 Main Street, CBD</td>
                            <td>John Smith</td>
                            <td>2024-01-01</td>
                            <td>2025-12-31</td>
                            <td>R 15,000</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-secondary" title="Generate Report">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>LS002</strong></td>
                            <td>456 Oak Avenue, Suburbs</td>
                            <td>Sarah Johnson</td>
                            <td>2023-06-01</td>
                            <td>2024-05-31</td>
                            <td>R 12,500</td>
                            <td><span class="badge bg-warning">Expiring Soon</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-secondary" title="Generate Report">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>LS003</strong></td>
                            <td>789 Industrial Park, Zone 3</td>
                            <td>ABC Manufacturing Ltd</td>
                            <td>2022-01-01</td>
                            <td>2024-12-31</td>
                            <td>R 25,000</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-secondary" title="Generate Report">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>LS004</strong></td>
                            <td>321 Commercial Plaza, Downtown</td>
                            <td>XYZ Retail Store</td>
                            <td>2023-03-01</td>
                            <td>2024-02-29</td>
                            <td>R 18,000</td>
                            <td><span class="badge bg-danger">Expired</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-secondary" title="Generate Report">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>LS005</strong></td>
                            <td>654 Residential Complex, North</td>
                            <td>Michael Brown</td>
                            <td>2024-01-15</td>
                            <td>2026-01-14</td>
                            <td>R 11,000</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-secondary" title="Generate Report">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-file-upload fa-2x text-info mb-2"></i>
                    <h6>Bulk Upload</h6>
                    <button class="btn btn-info btn-sm">Upload Leases</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-download fa-2x text-success mb-2"></i>
                    <h6>Export Data</h6>
                    <button class="btn btn-success btn-sm">Export CSV</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-check fa-2x text-warning mb-2"></i>
                    <h6>Renewal Reminders</h6>
                    <button class="btn btn-warning btn-sm">Set Reminders</button>
                </div>
            </div>
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

.module-card {
    transition: transform 0.2s ease-in-out;
    cursor: pointer;
}

.module-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>
@endsection
