@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Planning Approvals & Permits</h1>
        <div>
            <a href="{{ route('planning.approvals.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Issue Approval
            </a>
            <a href="{{ route('planning.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Planning
            </a>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row">
                <div class="col-md-3">
                    <label class="form-label">Approval Type</label>
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="building_permit">Building Permit</option>
                        <option value="subdivision_approval">Subdivision Approval</option>
                        <option value="zoning_clearance">Zoning Clearance</option>
                        <option value="environmental_clearance">Environmental Clearance</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="expired">Expired</option>
                        <option value="revoked">Revoked</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date Range</label>
                    <input type="date" name="date_from" class="form-control" placeholder="From Date">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex">
                        <input type="date" name="date_to" class="form-control mr-2" placeholder="To Date">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Approvals List -->
    <div class="card shadow">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Issued Approvals & Permits</h6>
        </div>
        <div class="card-body">
            @php
                $approvals = \App\Models\Planning\PlanningApplication::where('status', 'approved')->latest()->get();
            @endphp

            @if($approvals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Permit #</th>
                                <th>Property Owner</th>
                                <th>Property Address</th>
                                <th>Approval Type</th>
                                <th>Issue Date</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvals as $approval)
                            <tr>
                                <td>
                                    <strong class="text-primary">{{ $approval->application_number }}</strong>
                                </td>
                                <td>{{ $approval->applicant_name }}</td>
                                <td>{{ $approval->property_address }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ ucfirst(str_replace('_', ' ', $approval->application_type)) }}
                                    </span>
                                </td>
                                <td>{{ $approval->date_reviewed?->format('M d, Y') ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $expiryDate = $approval->date_reviewed?->addYear();
                                    @endphp
                                    {{ $expiryDate?->format('M d, Y') ?? 'N/A' }}
                                </td>
                                <td>
                                    @php
                                        $isExpired = $expiryDate && $expiryDate->isPast();
                                    @endphp
                                    <span class="badge badge-{{ $isExpired ? 'danger' : 'success' }}">
                                        {{ $isExpired ? 'Expired' : 'Active' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('planning.applications.show', $approval) }}" class="btn btn-sm btn-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-success" title="Print Certificate" onclick="printCertificate({{ $approval->id }})">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        @if(!$isExpired)
                                        <button class="btn btn-sm btn-warning" title="Extend Permit" onclick="extendPermit({{ $approval->id }})">
                                            <i class="fas fa-calendar-plus"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-certificate fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Approvals Found</h5>
                    <p class="text-muted">No planning approvals have been issued yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function printCertificate(approvalId) {
    window.open(`/planning/approvals/${approvalId}/certificate`, '_blank');
}

function extendPermit(approvalId) {
    if (confirm('Are you sure you want to extend this permit for another year?')) {
        // Handle permit extension
        alert('Permit extension functionality would be implemented here');
    }
}
</script>
@endsection