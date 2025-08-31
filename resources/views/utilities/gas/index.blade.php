@extends('layouts.app')

@section('title', 'Gas Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gas Management Dashboard</h3>
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
                                    <h4>{{ number_format($stats['monthly_consumption']) }}</h4>
                                    <p>Monthly Consumption (m³)</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h4>{{ $stats['safety_inspections'] }}</h4>
                                    <p>Safety Inspections</p>
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
                        <div class="col-md-12">
                            <h5>Quick Actions</h5>
                            <div class="btn-group mb-3" role="group">
                                <a href="{{ route('utilities.gas.connections') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-plug"></i> Manage Connections
                                </a>
                                <a href="{{ route('utilities.gas.meters') }}" class="btn btn-outline-info">
                                    <i class="fas fa-tachometer-alt"></i> Gas Meters
                                </a>
                                <a href="{{ route('utilities.gas.billing') }}" class="btn btn-outline-success">
                                    <i class="fas fa-file-invoice-dollar"></i> Billing
                                </a>
                                <a href="{{ route('utilities.gas.safety') }}" class="btn btn-outline-danger">
                                    <i class="fas fa-shield-alt"></i> Safety Inspections
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
