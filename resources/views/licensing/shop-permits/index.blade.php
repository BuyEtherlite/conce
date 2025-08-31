@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-store me-2 text-primary"></i>Shop Permits
                </h1>
                <a href="{{ route('licensing.shop-permits.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>New Shop Permit Application
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ $applications->where('status', 'pending')->count() }}</h4>
                                    <p class="card-text">Pending Applications</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ $applications->where('status', 'under_review')->count() }}</h4>
                                    <p class="card-text">Under Review</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-search fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ $applications->where('status', 'inspection_required')->count() }}</h4>
                                    <p class="card-text">Needs Inspection</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clipboard-check fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ $applications->where('status', 'approved')->count() }}</h4>
                                    <p class="card-text">Approved</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Applications Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Shop Permit Applications</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Application #</th>
                                    <th>Business Name</th>
                                    <th>Permit Type</th>
                                    <th>Owner</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($applications as $application)
                                    <tr>
                                        <td>
                                            <strong>{{ $application->application_number }}</strong>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $application->business_name }}</strong><br>
                                                <small class="text-muted">{{ $application->business_type }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $application->permitType->name }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                {{ $application->owner_name }}<br>
                                                <small class="text-muted">{{ $application->contact_email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $application->status_badge }}">
                                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $application->payment_status_badge }}">
                                                {{ ucfirst($application->payment_status) }}
                                            </span><br>
                                            <small class="text-muted">R{{ number_format($application->fee_amount, 2) }}</small>
                                        </td>
                                        <td>
                                            {{ $application->submitted_at ? $application->submitted_at->format('M d, Y') : 'Draft' }}
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('licensing.shop-permits.show', $application) }}" 
                                                   class="btn btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($application->status === 'pending' || $application->status === 'under_review')
                                                    <button class="btn btn-outline-success" title="Review Application"
                                                            onclick="reviewApplication({{ $application->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                @if($application->status === 'inspection_required')
                                                    <button class="btn btn-outline-warning" title="Schedule Inspection"
                                                            onclick="scheduleInspection({{ $application->id }})">
                                                        <i class="fas fa-calendar"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-store fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No shop permit applications found.</p>
                                            <a href="{{ route('licensing.shop-permits.create') }}" class="btn btn-primary">
                                                Create First Application
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($applications->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $applications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function reviewApplication(applicationId) {
    // Implementation for review modal
    alert('Review functionality to be implemented');
}

function scheduleInspection(applicationId) {
    // Implementation for inspection scheduling modal
    alert('Inspection scheduling to be implemented');
}
</script>
@endpush
