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
                            <span>Water Management</span>
                        </h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('water.index') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('water.connections.index') }}">
                            <i class="fas fa-plug"></i> Connections
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('water.meters.index') }}">
                            <i class="fas fa-tachometer-alt"></i> Meters
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('water.billing.index') }}">
                            <i class="fas fa-file-invoice"></i> Billing
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('water.quality.index') }}">
                            <i class="fas fa-flask"></i> Quality Tests
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('water.rates.index') }}">
                            <i class="fas fa-dollar-sign"></i> Rates
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Water Management Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('water.connections.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Connection
                    </a>
                </div>
            </div>

            <!-- Dashboard content -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Connections</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="text-primary">{{ \App\Models\Water\WaterConnection::count() }}</h2>
                            <p class="text-muted">Active Connections</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Meters</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="text-info">{{ \App\Models\Water\WaterMeter::count() }}</h2>
                            <p class="text-muted">Installed Meters</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Quality Tests</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="text-success">{{ \App\Models\Water\WaterQualityTest::count() }}</h2>
                            <p class="text-muted">Tests Conducted</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection