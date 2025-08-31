@extends('layouts.app')

@section('title', 'Health Records')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Health Records</h1>
                <p class="page-subtitle">Manage health records and medical documentation</p>
            </div>
        </div>
    </div>

    <!-- Health Records Overview Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Records</div>
                            <div class="h4 mb-0 font-weight-bold">1,247</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-medical fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">This Month</div>
                            <div class="h4 mb-0 font-weight-bold">89</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-info text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Pending Review</div>
                            <div class="h4 mb-0 font-weight-bold">24</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-warning text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Urgent Cases</div>
                            <div class="h4 mb-0 font-weight-bold">6</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Health Records Management -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-user-md fa-3x text-primary mb-3"></i>
                    <h5>Patient Records</h5>
                    <p class="text-muted">Manage patient medical records and history</p>
                    <a href="#" class="btn btn-primary">Manage</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-vial fa-3x text-success mb-3"></i>
                    <h5>Lab Results</h5>
                    <p class="text-muted">Track and manage laboratory test results</p>
                    <a href="#" class="btn btn-success">Manage</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-pills fa-3x text-info mb-3"></i>
                    <h5>Prescriptions</h5>
                    <p class="text-muted">Monitor medication prescriptions and dosages</p>
                    <a href="#" class="btn btn-info">Manage</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Health Records -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Health Records</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Patient ID</th>
                                    <th>Name</th>
                                    <th>Record Type</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>P001234</td>
                                    <td>John Doe</td>
                                    <td>Medical Checkup</td>
                                    <td>2025-01-08</td>
                                    <td><span class="badge bg-success">Complete</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View</button>
                                        <button class="btn btn-sm btn-outline-secondary">Edit</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>P001235</td>
                                    <td>Jane Smith</td>
                                    <td>Lab Results</td>
                                    <td>2025-01-07</td>
                                    <td><span class="badge bg-warning">Pending Review</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View</button>
                                        <button class="btn btn-sm btn-outline-secondary">Edit</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>P001236</td>
                                    <td>Mike Johnson</td>
                                    <td>Vaccination Record</td>
                                    <td>2025-01-06</td>
                                    <td><span class="badge bg-success">Complete</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View</button>
                                        <button class="btn btn-sm btn-outline-secondary">Edit</button>
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
@endsection
