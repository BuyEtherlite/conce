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
                            <span>Engineering Management</span>
                        </h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('engineering.index') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('engineering.projects.index') }}">
                            <i class="fas fa-project-diagram"></i> Projects
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('engineering.facilities.index') }}">
                            <i class="fas fa-building"></i> Facilities
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('engineering.inspections.index') }}">
                            <i class="fas fa-search"></i> Inspections
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('engineering.infrastructure.index') }}">
                            <i class="fas fa-road"></i> Infrastructure
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('engineering.maintenance.index') }}">
                            <i class="fas fa-wrench"></i> Maintenance
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Engineering Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('engineering.projects.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Project
                    </a>
                </div>
            </div>

            <!-- Dashboard content -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Active Projects</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="text-primary">{{ \App\Models\Engineering\EngineeringProject::count() }}</h2>
                            <p class="text-muted">Ongoing Projects</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Facilities</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="text-info">{{ \App\Models\Facilities\Facility::count() }}</h2>
                            <p class="text-muted">Total Facilities</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Inspections</h5>
                        </div>
                        <div class="card-body">
                            <h2 class="text-success">{{ \App\Models\Engineering\BuildingInspection::count() }}</h2>
                            <p class="text-muted">Completed Inspections</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection