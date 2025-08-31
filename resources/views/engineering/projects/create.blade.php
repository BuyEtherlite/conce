@extends('layouts.app')

@section('title', 'Create Engineering Project')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Create New Engineering Project</h1>
                    <p class="text-muted">Add a new infrastructure or construction project</p>
                </div>
                <a href="{{ route('engineering.projects.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back to Projects
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('engineering.projects.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="council_id" class="form-label">Council</label>
                                    <select class="form-select" id="council_id" name="council_id" required>
                                        <option value="">Select Council</option>
                                        @foreach($councils as $council)
                                            <option value="{{ $council->id }}">{{ $council->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="department_id" class="form-label">Department</label>
                                    <select class="form-select" id="department_id" name="department_id" required>
                                        <option value="1">Engineering Department</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Project Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Project Type</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="infrastructure">Infrastructure</option>
                                        <option value="roads">Roads</option>
                                        <option value="bridges">Bridges</option>
                                        <option value="water">Water</option>
                                        <option value="sewer">Sewer</option>
                                        <option value="buildings">Buildings</option>
                                        <option value="parks">Parks</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="new_construction">New Construction</option>
                                        <option value="maintenance">Maintenance</option>
                                        <option value="repair">Repair</option>
                                        <option value="upgrade">Upgrade</option>
                                        <option value="demolition">Demolition</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priority</label>
                                    <select class="form-select" id="priority" name="priority" required>
                                        <option value="">Select Priority</option>
                                        <option value="critical">Critical</option>
                                        <option value="high">High</option>
                                        <option value="medium">Medium</option>
                                        <option value="low">Low</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estimated_cost" class="form-label">Estimated Cost</label>
                                    <input type="number" class="form-control" id="estimated_cost" name="estimated_cost" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="project_manager" class="form-label">Project Manager</label>
                                    <input type="text" class="form-control" id="project_manager" name="project_manager">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="planned_start_date" class="form-label">Planned Start Date</label>
                                    <input type="date" class="form-control" id="planned_start_date" name="planned_start_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="planned_completion_date" class="form-label">Planned Completion Date</label>
                                    <input type="date" class="form-control" id="planned_completion_date" name="planned_completion_date">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location_description" class="form-label">Location Description</label>
                            <textarea class="form-control" id="location_description" name="location_description" rows="2"></textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Create Project</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
