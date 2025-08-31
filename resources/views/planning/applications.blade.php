@extends('layouts.admin')

@section('page-title', 'Planning Applications')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i class="bi bi-file-earmark-plus me-2"></i>Planning Applications</h4>
        <div>
            <a href="{{ route('planning.applications.create') }}" class="btn btn-primary">
                <i class="bi bi-plus me-1"></i>New Application
            </a>
            <a href="{{ route('planning.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Planning
            </a>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('planning.applications.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">All Statuses</option>
                                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="conditional_approval" {{ request('status') == 'conditional_approval' ? 'selected' : '' }}>Conditional Approval</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="application_type">Application Type</label>
                            <select name="application_type" id="application_type" class="form-control">
                                <option value="">All Types</option>
                                <option value="residential" {{ request('application_type') == 'residential' ? 'selected' : '' }}>Residential</option>
                                <option value="commercial" {{ request('application_type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="industrial" {{ request('application_type') == 'industrial' ? 'selected' : '' }}>Industrial</option>
                                <option value="mixed_use" {{ request('application_type') == 'mixed_use' ? 'selected' : '' }}>Mixed Use</option>
                                <option value="other" {{ request('application_type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="Application #, Applicant name..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-block">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                                <a href="{{ route('planning.applications.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Clear
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Planning Applications 
                @if($applications->total() > 0)
                    <span class="badge badge-primary ml-2">{{ $applications->total() }}</span>
                @endif
            </h6>
        </div>
        <div class="card-body">
            @if($applications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Application #</th>
                                <th>Applicant</th>
                                <th>Property Address</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Date Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                            <tr>
                                <td>
                                    <strong>{{ $application->application_number }}</strong>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $application->applicant_name }}</strong>
                                        <small class="d-block text-muted">{{ $application->applicant_email }}</small>
                                    </div>
                                </td>
                                <td>
                                    {{ $application->property_address }}
                                    @if($application->property_erf_number)
                                        <small class="d-block text-muted">ERF: {{ $application->property_erf_number }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ ucfirst(str_replace('_', ' ', $application->application_type)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $application->status_badge }}">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $application->date_submitted->format('M d, Y') }}
                                    @if($application->target_decision_date)
                                        <small class="d-block text-muted">
                                            Target: {{ $application->target_decision_date->format('M d, Y') }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('planning.applications.show', $application) }}" 
                                           class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('planning.applications.edit', $application) }}" 
                                           class="btn btn-sm btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($application->status === 'submitted' || $application->status === 'under_review')
                                            <form method="POST" action="{{ route('planning.applications.destroy', $application) }}" 
                                                  style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this application?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            Showing {{ $applications->firstItem() ?? 0 }} to {{ $applications->lastItem() ?? 0 }} 
                            of {{ $applications->total() }} results
                        </small>
                    </div>
                    <div>
                        {{ $applications->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Applications Found</h5>
                    <p class="text-muted">
                        @if(request()->hasAny(['status', 'application_type', 'search']))
                            Try adjusting your filters or 
                            <a href="{{ route('planning.applications.index') }}">clear all filters</a>.
                        @else
                            Start by creating your first planning application.
                        @endif
                    </p>
                    <a href="{{ route('planning.applications.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Create New Application
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
