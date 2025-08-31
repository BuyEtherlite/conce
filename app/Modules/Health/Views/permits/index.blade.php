@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Health Permits</h1>
                <a href="{{ route('health.permits.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Permit Application
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    @if(count($permits) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Permit ID</th>
                                        <th>Business Name</th>
                                        <th>Permit Type</th>
                                        <th>Issue Date</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permits as $permit)
                                    <tr>
                                        <td>{{ $permit['id'] }}</td>
                                        <td>{{ $permit['business_name'] }}</td>
                                        <td>{{ $permit['permit_type'] }}</td>
                                        <td>{{ $permit['issue_date'] }}</td>
                                        <td>{{ $permit['expiry_date'] }}</td>
                                        <td>
                                            <span class="badge badge-{{ $permit['status'] === 'Active' ? 'success' : 'warning' }}">
                                                {{ $permit['status'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" title="Renew">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                                <button class="btn btn-sm btn-primary" title="Print">
                                                    <i class="fas fa-print"></i>
                                                </button>
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
                            <h5 class="text-muted">No Health Permits Found</h5>
                            <p class="text-muted">Start by creating your first permit application.</p>
                            <a href="{{ route('health.permits.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> New Permit Application
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Health Permits</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPermitModal">
                <i class="bi bi-plus"></i> New Permit Application
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-warning">{{ $permits->where('status', 'pending')->count() }}</h3>
                    <small>Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-success">{{ $permits->where('status', 'approved')->count() }}</h3>
                    <small>Approved</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-danger">{{ $permits->where('status', 'rejected')->count() }}</h3>
                    <small>Rejected</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h3 class="text-info">{{ $permits->where('status', 'expired')->count() }}</h3>
                    <small>Expired</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Permits Table -->
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-0">Health Permits</h5>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search permits...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Permit #</th>
                            <th>Business Name</th>
                            <th>Type</th>
                            <th>Owner</th>
                            <th>Application Date</th>
                            <th>Status</th>
                            <th>Fee</th>
                            <th>Expiry</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permits as $permit)
                        <tr>
                            <td>{{ $permit->permit_number }}</td>
                            <td>
                                <strong>{{ $permit->business_name }}</strong><br>
                                <small class="text-muted">{{ Str::limit($permit->business_address, 30) }}</small>
                            </td>
                            <td>{{ ucfirst(str_replace('_', ' ', $permit->business_type)) }}</td>
                            <td>
                                {{ $permit->owner_name }}<br>
                                <small class="text-muted">{{ $permit->owner_phone }}</small>
                            </td>
                            <td>{{ $permit->application_date->format('M j, Y') }}</td>
                            <td>
                                @php
                                    $badgeColor = match($permit->status) {
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        'expired' => 'secondary',
                                        default => 'light'
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeColor }}">
                                    {{ ucfirst($permit->status) }}
                                </span>
                            </td>
                            <td>TZS {{ number_format($permit->permit_fee, 0) }}</td>
                            <td>
                                @if($permit->expiry_date)
                                    {{ $permit->expiry_date->format('M j, Y') }}
                                    @if($permit->expiry_date <= now()->addDays(30) && $permit->status === 'approved')
                                        <i class="bi bi-exclamation-triangle text-warning" title="Expiring soon"></i>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @if($permit->status === 'pending')
                                        <button class="btn btn-outline-success" title="Approve">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" title="Reject">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    @endif
                                    @if($permit->status === 'approved')
                                        <button class="btn btn-outline-info" title="Print">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No health permits found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $permits->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Permit Modal -->
<div class="modal fade" id="addPermitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Health Permit Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Business Name *</label>
                                <input type="text" class="form-control" name="business_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Business Type *</label>
                                <select class="form-select" name="business_type" required>
                                    <option value="">Select Type</option>
                                    <option value="restaurant">Restaurant</option>
                                    <option value="food_vendor">Food Vendor</option>
                                    <option value="beauty_salon">Beauty Salon</option>
                                    <option value="barbershop">Barbershop</option>
                                    <option value="hotel">Hotel/Lodge</option>
                                    <option value="pharmacy">Pharmacy</option>
                                    <option value="clinic">Clinic</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Business Address *</label>
                        <textarea class="form-control" name="business_address" rows="2" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Owner Name *</label>
                                <input type="text" class="form-control" name="owner_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Owner Phone *</label>
                                <input type="tel" class="form-control" name="owner_phone" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Owner Email</label>
                                <input type="email" class="form-control" name="owner_email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Permit Fee (TZS) *</label>
                                <input type="number" class="form-control" name="permit_fee" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
