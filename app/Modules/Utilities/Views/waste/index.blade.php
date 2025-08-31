@extends('layouts.app')

@section('title', 'Waste Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Waste Management Dashboard</h3>
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
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h4>{{ number_format($stats['customers']) }}</h4>
                                    <p>Total Customers</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h4>{{ $stats['collection_routes'] }}</h4>
                                    <p>Collection Routes</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h4>{{ $stats['recycling_rate'] }}%</h4>
                                    <p>Recycling Rate</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h4>{{ $stats['pending_requests'] }}</h4>
                                    <p>Pending Requests</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Quick Actions</h5>
                            <div class="btn-group mb-3" role="group">
                                <a href="{{ route('utilities.waste.collection') }}" class="btn btn-outline-success">
                                    <i class="fas fa-truck"></i> Collection Management
                                </a>
                                <a href="{{ route('utilities.waste.routes') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-route"></i> Manage Routes
                                </a>
                                <a href="{{ route('utilities.waste.recycling') }}" class="btn btn-outline-info">
                                    <i class="fas fa-recycle"></i> Recycling
                                </a>
                                <a href="{{ route('utilities.waste.billing') }}" class="btn btn-outline-warning">
                                    <i class="fas fa-file-invoice"></i> Billing
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
