@extends('layouts.app')

@section('title', 'Survey Projects')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">📐 Survey Projects</h1>
                    <p class="text-muted">Manage land surveying and mapping projects</p>
                </div>
                <div>
                    <a href="{{ route('survey.projects.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Survey Project
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" name="search" 
                                   value="{{ request('search') }}" placeholder="Project number, title...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Survey Type</label>
                            <select class="form-select" name="survey_type">
                                <option value="">All Types</option>
                                @foreach($surveyTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('survey_type') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Surveyor</label>
                            <select class="form-select" name="surveyor">
                                <option value="">All Surveyors</option>
                                @foreach($surveyors as $surveyor)
                                    <option value="{{ $surveyor->id }}" {{ request('surveyor') == $surveyor->id ? 'selected' : '' }}>
                                        {{ $surveyor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Search
                                </button>
                                <a href="{{ route('survey.projects.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Survey Projects</h5>
                </div>
                <div class="card-body">
                    @if($projects->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Project Details</th>
                                        <th>Client & Location</th>
                                        <th>Survey Type</th>
                                        <th>Surveyor</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Dates</th>
                                        <th>Cost</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                        <tr class="{{ $project->is_overdue ? 'table-warning' : '' }}">
                                            <td>
                                                <div>
                                                    <strong>{{ $project->project_number }}</strong>
                                                    @if($project->is_overdue)
                                                        <span class="badge badge-danger ml-1">Overdue</span>
                                                    @endif
                                                </div>
                                                <div class="text-muted">{{ Str::limit($project->title, 30) }}</div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $project->client->full_name ?? 'N/A' }}</strong>
                                                </div>
                                                <small class="text-muted">{{ Str::limit($project->property_address, 40) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $project->surveyType->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($project->surveyor)
                                                    <div>{{ $project->surveyor->name }}</div>
                                                    <small class="text-muted">{{ $project->surveyor->email }}</small>
                                                @else
                                                    <span class="text-muted">Unassigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $project->priority_badge }}">
                                                    {{ ucfirst($project->priority) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $project->status_badge }}">
                                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>
                                                    <small class="text-muted">Requested:</small><br>
                                                    {{ $project->requested_date->format('M d, Y') }}
                                                </div>
                                                @if($project->scheduled_date)
                                                    <div class="mt-1">
                                                        <small class="text-muted">Scheduled:</small><br>
                                                        {{ $project->scheduled_date->format('M d, Y') }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($project->estimated_cost)
                                                    <div>
                                                        <small class="text-muted">Estimated:</small><br>
                                                        ${{ number_format($project->estimated_cost, 2) }}
                                                    </div>
                                                @endif
                                                @if($project->actual_cost)
                                                    <div class="mt-1">
                                                        <small class="text-muted">Actual:</small><br>
                                                        ${{ number_format($project->actual_cost, 2) }}
                                                    </div>
                                                @endif
                                                <div class="mt-1">
                                                    <small class="text-muted">Fees:</small><br>
                                                    ${{ number_format($project->total_fees, 2) }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('survey.projects.show', $project) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('survey.projects.edit', $project) }}" 
                                                       class="btn btn-sm btn-outline-secondary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <small class="text-muted">
                                    Showing {{ $projects->firstItem() ?? 0 }} to {{ $projects->lastItem() ?? 0 }} 
                                    of {{ $projects->total() }} results
                                </small>
                            </div>
                            {{ $projects->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                            <h5>No Survey Projects Found</h5>
                            <p class="text-muted">No projects match your current filters.</p>
                            <a href="{{ route('survey.projects.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create New Project
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
