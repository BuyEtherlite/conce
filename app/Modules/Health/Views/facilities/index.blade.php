@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Health Facilities</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFacilityModal">
                <i class="bi bi-plus"></i> Add New Facility
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Facility Type</label>
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="clinic">Clinic</option>
                                <option value="hospital">Hospital</option>
                                <option value="pharmacy">Pharmacy</option>
                                <option value="laboratory">Laboratory</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search facilities...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('health.facilities.index') }}" class="btn btn-outline-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Facilities Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Facility Name</th>
                            <th>Type</th>
                            <th>Registration #</th>
                            <th>Contact Person</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>License Expiry</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($facilities as $facility)
                        <tr>
                            <td>
                                <strong>{{ $facility->facility_name }}</strong><br>
                                <small class="text-muted">{{ Str::limit($facility->address, 30) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($facility->facility_type) }}</span>
                            </td>
                            <td>{{ $facility->registration_number }}</td>
                            <td>{{ $facility->contact_person }}</td>
                            <td>{{ $facility->phone }}</td>
                            <td>
                                <span class="badge bg-{{ $facility->status == 'active' ? 'success' : ($facility->status == 'suspended' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($facility->status) }}
                                </span>
                            </td>
                            <td>
                                {{ $facility->license_expiry_date->format('M j, Y') }}
                                @if($facility->license_expiry_date <= now()->addDays(30))
                                    <i class="bi bi-exclamation-triangle text-warning" title="Expiring soon"></i>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-outline-info" title="Inspect">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No health facilities found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $facilities->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Facility Modal -->
<div class="modal fade" id="addFacilityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Health Facility</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Facility Name *</label>
                                <input type="text" class="form-control" name="facility_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Facility Type *</label>
                                <select class="form-select" name="facility_type" required>
                                    <option value="">Select Type</option>
                                    <option value="clinic">Clinic</option>
                                    <option value="hospital">Hospital</option>
                                    <option value="pharmacy">Pharmacy</option>
                                    <option value="laboratory">Laboratory</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Registration Number *</label>
                                <input type="text" class="form-control" name="registration_number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Bed Capacity</label>
                                <input type="number" class="form-control" name="bed_capacity">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address *</label>
                        <textarea class="form-control" name="address" rows="2" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Contact Person *</label>
                                <input type="text" class="form-control" name="contact_person" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone *</label>
                                <input type="tel" class="form-control" name="phone" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">License Expiry Date *</label>
                                <input type="date" class="form-control" name="license_expiry_date" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Facility</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
