@extends('layouts.app')

@section('title', 'Electricity Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Electricity Management Dashboard</h3>
                    <div class="card-tools">
                        <a href="{{ route('utilities.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Utilities
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Stats Overview -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h4>{{ number_format($stats['active_connections']) }}</h4>
                                    <p>Active Connections</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h4>{{ number_format($stats['total_consumption']) }} kWh</h4>
                                    <p>Monthly Consumption</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h4>{{ $stats['outages'] }}</h4>
                                    <p>Active Outages</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h4>{{ $stats['pending_connections'] }}</h4>
                                    <p>Pending Connections</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="btn-group-vertical w-100">
                                        <a href="{{ route('utilities.electricity.connections') }}" class="btn btn-outline-primary mb-2">
                                            <i class="fas fa-plug"></i> Manage Connections
                                        </a>
                                        <a href="{{ route('utilities.electricity.meters') }}" class="btn btn-outline-info mb-2">
                                            <i class="fas fa-tachometer-alt"></i> Meter Readings
                                        </a>
                                        <a href="{{ route('utilities.electricity.billing') }}" class="btn btn-outline-success mb-2">
                                            <i class="fas fa-file-invoice-dollar"></i> Billing & Invoices
                                        </a>
                                        <a href="{{ route('utilities.electricity.outages') }}" class="btn btn-outline-danger">
                                            <i class="fas fa-exclamation-triangle"></i> Outage Management
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Recent Activities</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>New connection request</span>
                                            <small class="text-muted">2 hours ago</small>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Outage reported in Zone A</span>
                                            <small class="text-muted">4 hours ago</small>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Meter reading completed</span>
                                            <small class="text-muted">1 day ago</small>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Monthly billing generated</span>
                                            <small class="text-muted">2 days ago</small>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
