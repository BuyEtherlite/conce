@extends('layouts.app')

@section('title', 'Electricity Connections')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Electricity Connections Management</h3>
                    <div class="card-tools">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newConnectionModal">
                            <i class="fas fa-plus"></i> New Connection
                        </button>
                        <a href="{{ route('utilities.electricity.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Search connections...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-control">
                                <option>All Status</option>
                                <option>Active</option>
                                <option>Pending</option>
                                <option>Disconnected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control">
                                <option>All Zones</option>
                                <option>Zone A</option>
                                <option>Zone B</option>
                                <option>Zone C</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Search</button>
                        </div>
                    </div>

                    <!-- Connections Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Connection ID</th>
                                    <th>Customer Name</th>
                                    <th>Address</th>
                                    <th>Meter Number</th>
                                    <th>Connection Type</th>
                                    <th>Status</th>
                                    <th>Last Reading</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>ELC-001</td>
                                    <td>John Smith</td>
                                    <td>123 Main St</td>
                                    <td>MTR-001</td>
                                    <td>Residential</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>450 kWh</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">View</button>
                                        <button class="btn btn-sm btn-warning">Edit</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>ELC-002</td>
                                    <td>ABC Company</td>
                                    <td>456 Business Ave</td>
                                    <td>MTR-002</td>
                                    <td>Commercial</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>1250 kWh</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">View</button>
                                        <button class="btn btn-sm btn-warning">Edit</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>ELC-003</td>
                                    <td>Jane Doe</td>
                                    <td>789 Oak St</td>
                                    <td>MTR-003</td>
                                    <td>Residential</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>-</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">View</button>
                                        <button class="btn btn-sm btn-success">Approve</button>
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

<!-- New Connection Modal -->
<div class="modal fade" id="newConnectionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Electricity Connection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Customer Name *</label>
                                <input type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Contact Number *</label>
                                <input type="tel" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Property Address *</label>
                        <textarea class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Connection Type *</label>
                                <select class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option>Residential</option>
                                    <option>Commercial</option>
                                    <option>Industrial</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Load Requirement (kW)</label>
                                <input type="number" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Additional Notes</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
