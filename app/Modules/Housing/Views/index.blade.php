@extends('layouts.admin')

@section('title', 'Housing Management Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Side Navigation -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            <span>Housing Management</span>
                        </h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('housing.index') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('housing.properties.index') }}">
                            <i class="fas fa-home"></i> Properties
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('housing.applications.index') }}">
                            <i class="fas fa-file-alt"></i> Applications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('housing.waiting-list.index') }}">
                            <i class="fas fa-list"></i> Waiting List
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('housing.allocations.index') }}">
                            <i class="fas fa-key"></i> Allocations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('housing.tenants.index') }}">
                            <i class="fas fa-users"></i> Tenants
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Housing Management Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('housing.applications.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Application
                    </a>
                </div>
            </div>

            <!-- Dashboard content here -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Properties</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="text-primary">{{ \App\Models\Housing\HousingProperty::count() }}</h2>
                            <p class="text-muted">Total Properties</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Applications</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="text-info">{{ \App\Models\Housing\HousingApplication::count() }}</h2>
                            <p class="text-muted">Pending Applications</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Tenants</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="text-success">{{ \App\Models\Housing\Tenant::count() }}</h2>
                            <p class="text-muted">Active Tenants</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Allocations -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Allocations</h5>
                </div>
                <div class="card-body">
                    @if($recentAllocations->count() > 0)
                        @foreach($recentAllocations as $allocation)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <strong>{{ $allocation->application->applicant_name }}</strong><br>
                                    <small class="text-muted">{{ $allocation->property->property_number }}</small>
                                </div>
                                <span class="badge badge-{{ $allocation->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($allocation->status) }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No recent allocations</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stand Management Quick Access -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Stands Management</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-map-marked-alt fa-2x text-primary mb-2"></i>
                                    <h6>Housing Areas</h6>
                                    <p class="text-muted">Manage different housing areas</p>
                                    <a href="{{ route('housing.areas.index') }}" class="btn btn-primary btn-sm">View Areas</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-th-large fa-2x text-success mb-2"></i>
                                    <h6>Individual Stands</h6>
                                    <p class="text-muted">Manage individual stands</p>
                                    <a href="{{ route('housing.stands.index') }}" class="btn btn-success btn-sm">View Stands</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-handshake fa-2x text-info mb-2"></i>
                                    <h6>Stand Allocations</h6>
                                    <p class="text-muted">Manage stand allocations</p>
                                    <a href="{{ route('housing.stand-allocations.index') }}" class="btn btn-info btn-sm">View Allocations</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-plus fa-2x text-warning mb-2"></i>
                                    <h6>Quick Actions</h6>
                                    <p class="text-muted">Create new area or allocation</p>
                                    <a href="{{ route('housing.areas.create') }}" class="btn btn-warning btn-sm">Add Area</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</main>
    </div>
</div>
@endsection