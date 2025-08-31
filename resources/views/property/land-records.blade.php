@extends('layouts.admin')

@section('page-title', 'Land Records')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>🗺️ Land Records</h4>
        <button class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>New Record
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Total Records</h6>
                            <h3 class="mb-0">1,234</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-file-alt fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Registered</h6>
                            <h3 class="mb-0">980</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Pending</h6>
                            <h3 class="mb-0">154</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted">Disputed</h6>
                            <h3 class="mb-0">100</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                        </div>
                    </div>
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
                    <input type="text" class="form-control" placeholder="Search by property ID or owner...">
                </div>
                <div class="col-md-3">
                    <select class="form-control">
                        <option value="">All Status</option>
                        <option value="registered">Registered</option>
                        <option value="pending">Pending</option>
                        <option value="disputed">Disputed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control">
                        <option value="">All Property Types</option>
                        <option value="residential">Residential</option>
                        <option value="commercial">Commercial</option>
                        <option value="agricultural">Agricultural</option>
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

    <!-- Land Records Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Land Records</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Property ID</th>
                            <th>Owner Name</th>
                            <th>Location</th>
                            <th>Property Type</th>
                            <th>Size (sqm)</th>
                            <th>Status</th>
                            <th>Registration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>LR-2024-001</td>
                            <td>John Smith</td>
                            <td>123 Main Street, City Centre</td>
                            <td>Residential</td>
                            <td>500</td>
                            <td><span class="badge bg-success">Registered</span></td>
                            <td>2024-01-15</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>LR-2024-002</td>
                            <td>Mary Johnson</td>
                            <td>456 Oak Avenue, Suburbs</td>
                            <td>Commercial</td>
                            <td>1,200</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td>2024-01-16</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>LR-2024-003</td>
                            <td>Robert Wilson</td>
                            <td>789 Farm Road, Rural Area</td>
                            <td>Agricultural</td>
                            <td>5,000</td>
                            <td><span class="badge bg-danger">Disputed</span></td>
                            <td>2024-01-17</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>LR-2024-004</td>
                            <td>Sarah Davis</td>
                            <td>321 Industrial Park, Zone A</td>
                            <td>Industrial</td>
                            <td>2,500</td>
                            <td><span class="badge bg-success">Registered</span></td>
                            <td>2024-01-18</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active">
                        <a class="page-link" href="#">1</a>
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
@endsection
