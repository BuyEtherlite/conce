@extends('layouts.app')

@section('title', 'Survey Services')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Survey Services</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Survey Services</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Projects</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +5.2%
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{ $totalProjects }}">{{ $totalProjects }}</span>
                            </h4>
                            <a href="{{ route('survey.projects.index') }}" class="text-decoration-underline">View All Projects</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-primary rounded fs-3">
                                <i class="bx bx-file text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Active Projects</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +12.1%
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{ $activeProjects }}">{{ $activeProjects }}</span>
                            </h4>
                            <a href="{{ route('survey.projects.index') }}?status=in_progress" class="text-decoration-underline">View Active</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-warning rounded fs-3">
                                <i class="bx bx-time text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Completed Projects</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +8.7%
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{ $completedProjects }}">{{ $completedProjects }}</span>
                            </h4>
                            <a href="{{ route('survey.projects.index') }}?status=completed" class="text-decoration-underline">View Completed</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-success rounded fs-3">
                                <i class="bx bx-check-circle text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Available Equipment</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-muted fs-14 mb-0">{{ $totalEquipment }} Total</h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{ $equipmentCount }}">{{ $equipmentCount }}</span>
                            </h4>
                            <a href="{{ route('survey.equipment.index') }}" class="text-decoration-underline">View Equipment</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded fs-3">
                                <i class="bx bx-wrench text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('survey.projects.create') }}" class="btn btn-primary w-100">
                                <i class="ri-add-line align-middle me-1"></i> New Survey Project
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('survey.equipment.create') }}" class="btn btn-outline-primary w-100">
                                <i class="ri-tools-line align-middle me-1"></i> Add Equipment
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('survey.types.create') }}" class="btn btn-outline-success w-100">
                                <i class="ri-settings-line align-middle me-1"></i> Create Survey Type
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('survey.analytics') }}" class="btn btn-outline-info w-100">
                                <i class="ri-bar-chart-line align-middle me-1"></i> View Analytics
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Projects -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h4 class="card-title mb-0 flex-grow-1">Recent Survey Projects</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('survey.projects.index') }}" class="btn btn-soft-primary btn-sm">
                            View All <i class="ri-arrow-right-line align-middle ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-nowrap table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Project #</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Client</th>
                                    <th scope="col">Surveyor</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Priority</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentProjects as $project)
                                <tr>
                                    <td>
                                        <a href="{{ route('survey.projects.show', $project) }}" class="fw-medium link-primary">
                                            {{ $project->project_number }}
                                        </a>
                                    </td>
                                    <td>{{ $project->title }}</td>
                                    <td>{{ $project->client->name ?? 'N/A' }}</td>
                                    <td>{{ $project->surveyor->name ?? 'Unassigned' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $project->status_color }}">
                                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $project->priority_color }}">
                                            {{ ucfirst($project->priority) }}
                                        </span>
                                    </td>
                                    <td>{{ $project->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle btn btn-sm btn-soft-secondary" data-bs-toggle="dropdown">
                                                <i class="ri-more-fill"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ route('survey.projects.show', $project) }}"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                                                <li><a class="dropdown-item" href="{{ route('survey.projects.edit', $project) }}"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ri-file-list-3-line fs-1 mb-3 d-block"></i>
                                            <p>No survey projects found</p>
                                            <a href="{{ route('survey.projects.create') }}" class="btn btn-primary">Create First Project</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Survey Types Overview -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Survey Types Distribution</h4>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="ri-map-line text-primary me-2"></i>
                                Boundary Surveys
                            </div>
                            <span class="badge bg-primary rounded-pill">
                                {{ $recentProjects->where('survey_type.name', 'Boundary Survey')->count() }}
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="ri-landscape-line text-success me-2"></i>
                                Topographic Surveys
                            </div>
                            <span class="badge bg-success rounded-pill">
                                {{ $recentProjects->where('survey_type.name', 'Topographic Survey')->count() }}
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="ri-building-line text-info me-2"></i>
                                Construction Surveys
                            </div>
                            <span class="badge bg-info rounded-pill">
                                {{ $recentProjects->where('survey_type.name', 'Construction Survey')->count() }}
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="ri-focus-3-line text-warning me-2"></i>
                                GPS Surveys
                            </div>
                            <span class="badge bg-warning rounded-pill">
                                {{ $recentProjects->where('survey_type.name', 'GPS Survey')->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Equipment Status</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-success">{{ $equipmentCount }}</h4>
                                <p class="text-muted mb-0">Available</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $totalEquipment - $equipmentCount }}</h4>
                                <p class="text-muted mb-0">In Use / Maintenance</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $totalEquipment > 0 ? ($equipmentCount / $totalEquipment) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
// Initialize counters
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.counter-value');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const increment = target / 200;
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.ceil(current);
            }
        }, 10);
    });
});
</script>
@endsection
