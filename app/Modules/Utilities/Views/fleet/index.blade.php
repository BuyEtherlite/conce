@extends('layouts.app')

@section('title', 'Fleet Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fleet Management Dashboard</h3>
                    <div class="card-tools">
                        <a href="{{ route('utilities.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Utilities
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Stats Overview -->
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h4>{{ $stats['total_vehicles'] }}</h4>
                                    <p>Total Vehicles</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h4>{{ $stats['active_vehicles'] }}</h4>
                                    <p>Active Vehicles</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h4>{{ $stats['in_maintenance'] }}</h4>
                                    <p>In Maintenance</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h4>{{ number_format($stats['fuel_consumption']) }}L</h4>
                                    <p>Monthly Fuel</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h4>${{ number_format($stats['monthly_cost']) }}</h4>
                                    <p>Monthly Cost</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Quick Actions</h5>
                            <div class="btn-group mb-3" role="group">
                                <a href="{{ route('utilities.fleet.vehicles') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-car"></i> Manage Vehicles
                                </a>
                                <a href="{{ route('utilities.fleet.maintenance') }}" class="btn btn-outline-warning">
                                    <i class="fas fa-wrench"></i> Maintenance
                                </a>
                                <a href="{{ route('utilities.fleet.fuel') }}" class="btn btn-outline-info">
                                    <i class="fas fa-gas-pump"></i> Fuel Management
                                </a>
                                <a href="{{ route('utilities.fleet.tracking') }}" class="btn btn-outline-success">
                                    <i class="fas fa-map-marker-alt"></i> Vehicle Tracking
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
