@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Side Navigation -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            <span>Utilities Management</span>
                        </h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('utilities.index') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('utilities.electricity.index') }}">
                            <i class="fas fa-bolt"></i> Electricity
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('utilities.gas.index') }}">
                            <i class="fas fa-fire"></i> Gas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('utilities.waste.index') }}">
                            <i class="fas fa-trash"></i> Waste Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('utilities.fleet.index') }}">
                            <i class="fas fa-truck"></i> Fleet Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('utilities.infrastructure.index') }}">
                            <i class="fas fa-tools"></i> Infrastructure
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Utilities Management Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group">
                        <button class="btn btn-outline-secondary">
                            <i class="fas fa-chart-line"></i> Reports
                        </button>
                    </div>
                </div>
            </div>

            <!-- Dashboard content -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Electricity</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="text-primary">{{ \App\Models\Utilities\ElectricityConnection::count() }}</h2>
                            <p class="text-muted">Active Connections</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Fleet Vehicles</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="text-info">{{ \App\Models\Utilities\FleetVehicle::count() }}</h2>
                            <p class="text-muted">Total Vehicles</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Infrastructure</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="text-success">{{ \App\Models\Utilities\InfrastructureAsset::count() }}</h2>
                            <p class="text-muted">Assets Managed</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection