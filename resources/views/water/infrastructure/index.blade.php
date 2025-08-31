@extends('layouts.admin')

@section('page-title', 'Water Infrastructure')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>🏗️ Water Infrastructure</h4>
        <button class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>New Infrastructure
        </button>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-primary">45</h3>
                    <small class="text-muted">Active Pipelines</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info">12</h3>
                    <small class="text-muted">Pump Stations</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-warning">8</h3>
                    <small class="text-muted">Reservoirs</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">95%</h3>
                    <small class="text-muted">System Health</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Infrastructure Assets</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Asset ID</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Last Maintenance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>INF-001</td>
                            <td>Main Pipeline</td>
                            <td>Central District</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>2024-01-15</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View</button>
                                <button class="btn btn-sm btn-outline-secondary">Maintain</button>
                            </td>
                        </tr>
                        <tr>
                            <td>INF-002</td>
                            <td>Pump Station</td>
                            <td>North Ward</td>
                            <td><span class="badge bg-warning">Maintenance Required</span></td>
                            <td>2023-12-20</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View</button>
                                <button class="btn btn-sm btn-outline-warning">Schedule</button>
                            </td>
                        </tr>
                        <tr>
                            <td>INF-003</td>
                            <td>Reservoir</td>
                            <td>South Hill</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>2024-01-10</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View</button>
                                <button class="btn btn-sm btn-outline-secondary">Maintain</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
