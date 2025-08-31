@extends('layouts.admin')

@section('title', 'Survey Services')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-map me-2"></i>
                        Survey Services Management
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Quick Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Active Projects</h6>
                                            <h4>{{ $stats['active_projects'] ?? 0 }}</h4>
                                        </div>
                                        <i class="fas fa-project-diagram fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Completed Projects</h6>
                                            <h4>{{ $stats['completed_projects'] ?? 0 }}</h4>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Total Revenue</h6>
                                            <h4>${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h4>
                                        </div>
                                        <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Equipment</h6>
                                            <h4>{{ $stats['equipment_count'] ?? 0 }}</h4>
                                        </div>
                                        <i class="fas fa-tools fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Survey Services</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <a href="{{ route('survey.projects.index') }}" class="btn btn-outline-primary w-100 py-3">
                                                <i class="fas fa-project-diagram fa-2x mb-2 d-block"></i>
                                                <div>Survey Projects</div>
                                                <small class="text-muted">Manage survey projects</small>
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <a href="{{ route('survey.cadastral.index') }}" class="btn btn-outline-success w-100 py-3">
                                                <i class="fas fa-map-marked-alt fa-2x mb-2 d-block"></i>
                                                <div>Cadastral Surveys</div>
                                                <small class="text-muted">Land boundary surveys</small>
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <a href="{{ route('survey.equipment.index') }}" class="btn btn-outline-info w-100 py-3">
                                                <i class="fas fa-tools fa-2x mb-2 d-block"></i>
                                                <div>Equipment</div>
                                                <small class="text-muted">Survey equipment management</small>
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <a href="{{ route('survey.reports.index') }}" class="btn btn-outline-warning w-100 py-3">
                                                <i class="fas fa-file-alt fa-2x mb-2 d-block"></i>
                                                <div>Reports</div>
                                                <small class="text-muted">Survey reports & analytics</small>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Projects -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Recent Survey Projects</h6>
                            <a href="{{ route('survey.projects.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i> New Project
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Project ID</th>
                                            <th>Client</th>
                                            <th>Type</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Progress</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentProjects ?? [] as $project)
                                        <tr>
                                            <td>
                                                <strong>{{ $project->project_number ?? 'SP-' . str_pad($project->id, 4, '0', STR_PAD_LEFT) }}</strong>
                                            </td>
                                            <td>{{ $project->client_name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $project->survey_type ?? 'General' }}</span>
                                            </td>
                                            <td>{{ $project->location ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ 
                                                    $project->status === 'completed' ? 'success' : 
                                                    ($project->status === 'in_progress' ? 'primary' : 'secondary') 
                                                }}">
                                                    {{ ucfirst(str_replace('_', ' ', $project->status ?? 'pending')) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar" role="progressbar" 
                                                         style="width: {{ $project->progress_percentage ?? 0 }}%">
                                                        {{ $project->progress_percentage ?? 0 }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('survey.projects.show', $project->id) }}" class="btn btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('survey.projects.edit', $project->id) }}" class="btn btn-outline-secondary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-map fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No survey projects found. <a href="{{ route('survey.projects.create') }}">Create your first project</a>.</p>
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
        </div>
    </div>
</div>
@endsection

