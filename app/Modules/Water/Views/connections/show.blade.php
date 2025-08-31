@extends('layouts.admin')

@section('page-title', 'Water Connection Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💧 Connection Details - WC-{{ str_pad($id, 3, '0', STR_PAD_LEFT) }}</h4>
        <div>
            <a href="{{ route('water.connections.edit', $id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>Edit
            </a>
            <a href="{{ route('water.connections.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Connection Information</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Connection ID:</dt>
                        <dd class="col-sm-8">WC-{{ str_pad($id, 3, '0', STR_PAD_LEFT) }}</dd>

                        <dt class="col-sm-4">Customer Name:</dt>
                        <dd class="col-sm-8">John Smith</dd>

                        <dt class="col-sm-4">Connection Type:</dt>
                        <dd class="col-sm-8"><span class="badge bg-info">Residential</span></dd>

                        <dt class="col-sm-4">Property Address:</dt>
                        <dd class="col-sm-8">123 Main Street, Downtown Area</dd>

                        <dt class="col-sm-4">Meter Number:</dt>
                        <dd class="col-sm-8">M-001234</dd>

                        <dt class="col-sm-4">Connection Date:</dt>
                        <dd class="col-sm-8">January 15, 2024</dd>

                        <dt class="col-sm-4">Status:</dt>
                        <dd class="col-sm-8"><span class="badge bg-success">Active</span></dd>

                        <dt class="col-sm-4">Contact Phone:</dt>
                        <dd class="col-sm-8">+1 234 567 8900</dd>

                        <dt class="col-sm-4">Contact Email:</dt>
                        <dd class="col-sm-8">john.smith@email.com</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary">View Meter Readings</a>
                        <a href="#" class="btn btn-outline-info">View Billing History</a>
                        <a href="#" class="btn btn-outline-warning">Generate Report</a>
                        <a href="#" class="btn btn-outline-danger">Suspend Connection</a>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <small class="text-muted">2024-01-25</small><br>
                            Meter reading recorded: 1,250 gallons
                        </li>
                        <li class="mb-2">
                            <small class="text-muted">2024-01-20</small><br>
                            Bill generated: $45.50
                        </li>
                        <li class="mb-2">
                            <small class="text-muted">2024-01-15</small><br>
                            Connection activated
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
