@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Engineering Projects</h4>
                <div class="page-title-right">
                    <a href="{{ route('engineering.projects.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create Project
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($projects->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Project #</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Budget</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projects as $project)
                                <tr>
                                    <td><strong>{{ $project->project_number ?? 'PRJ-001' }}</strong></td>
                                    <td>{{ $project->name ?? 'Road Improvement Project' }}</td>
                                    <td>{{ ucfirst($project->type ?? 'infrastructure') }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($project->status ?? 'planning') }}</span>
                                    </td>
                                    <td>{{ $project->start_date ? date('M d, Y', strtotime($project->start_date)) : 'TBD' }}</td>
                                    <td>{{ $project->end_date ? date('M d, Y', strtotime($project->end_date)) : 'TBD' }}</td>
                                    <td>${{ number_format($project->budget ?? 100000, 2) }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('engineering.projects.show', $project->id ?? 1) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            <a href="{{ route('engineering.projects.edit', $project->id ?? 1) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-diagram-3 display-4 text-muted"></i>
                        <h5 class="mt-3">No Projects Found</h5>
                        <p class="text-muted">Create your first engineering project to get started.</p>
                        <a href="{{ route('engineering.projects.create') }}" class="btn btn-primary">Create Project</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
