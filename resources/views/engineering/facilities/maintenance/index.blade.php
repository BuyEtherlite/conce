@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Facilities Maintenance</h4>
                <div class="page-title-right">
                    <button class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Schedule Maintenance
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Facility</th>
                                    <th>Maintenance Type</th>
                                    <th>Scheduled Date</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Therapy Pool</strong></td>
                                    <td>Filter Replacement</td>
                                    <td>{{ date('Y-m-d') }}</td>
                                    <td><span class="badge bg-danger">High</span></td>
                                    <td><span class="badge bg-warning">In Progress</span></td>
                                    <td>Maintenance Team A</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary">View</button>
                                            <button type="button" class="btn btn-sm btn-outline-success">Complete</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Basketball Court</strong></td>
                                    <td>Floor Polishing</td>
                                    <td>{{ date('Y-m-d', strtotime('+2 days')) }}</td>
                                    <td><span class="badge bg-warning">Medium</span></td>
                                    <td><span class="badge bg-info">Scheduled</span></td>
                                    <td>Maintenance Team B</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary">View</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Football Field</strong></td>
                                    <td>Grass Cutting</td>
                                    <td>{{ date('Y-m-d', strtotime('+1 week')) }}</td>
                                    <td><span class="badge bg-success">Low</span></td>
                                    <td><span class="badge bg-info">Scheduled</span></td>
                                    <td>Groundskeeping Team</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary">View</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Main Community Hall</strong></td>
                                    <td>Sound System Check</td>
                                    <td>{{ date('Y-m-d', strtotime('-1 day')) }}</td>
                                    <td><span class="badge bg-warning">Medium</span></td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>Technical Team</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary">View</button>
                                            <button type="button" class="btn btn-sm btn-outline-info">Report</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Summary -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning">1</h5>
                    <p class="card-text">In Progress</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-info">2</h5>
                    <p class="card-text">Scheduled</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success">1</h5>
                    <p class="card-text">Completed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-danger">1</h5>
                    <p class="card-text">High Priority</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
