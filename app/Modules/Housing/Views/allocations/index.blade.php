@extends('layouts.app')

@section('page-title', 'Housing Allocations')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>🏠 Housing Allocations</h4>
        <a href="{{ route('housing.allocations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New Allocation
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['total'] }}</h4>
                            <span>Total Allocations</span>
                        </div>
                        <i class="fas fa-home fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['active'] }}</h4>
                            <span>Active</span>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['pending'] }}</h4>
                            <span>Pending</span>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['terminated'] }}</h4>
                            <span>Terminated</span>
                        </div>
                        <i class="fas fa-times-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Allocations Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Housing Allocations</h5>
        </div>
        <div class="card-body">
            @if($allocations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Applicant</th>
                                <th>Property</th>
                                <th>Allocation Date</th>
                                <th>Monthly Rent</th>
                                <th>Lease Period</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allocations as $allocation)
                                <tr>
                                    <td>ALL-{{ $allocation->id }}</td>
                                    <td>
                                        <strong>{{ $allocation->housingApplication->applicant_name }}</strong><br>
                                        <small class="text-muted">{{ $allocation->housingApplication->applicant_email }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $allocation->property->address }}</strong><br>
                                        <small class="text-muted">{{ $allocation->property->property_type }}</small>
                                    </td>
                                    <td>{{ $allocation->allocation_date ? \Carbon\Carbon::parse($allocation->allocation_date)->format('d M Y') : 'N/A' }}</td>
                                    <td>R {{ number_format($allocation->monthly_rent, 2) }}</td>
                                    <td>
                                        {{ $allocation->lease_start_date ? \Carbon\Carbon::parse($allocation->lease_start_date)->format('d M Y') : 'N/A' }} - 
                                        {{ $allocation->lease_end_date ? \Carbon\Carbon::parse($allocation->lease_end_date)->format('d M Y') : 'N/A' }}
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($allocation->status) {
                                                'active' => 'bg-success',
                                                'pending' => 'bg-warning',
                                                'terminated' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($allocation->status) }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('housing.allocations.show', $allocation) }}" class="btn btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('housing.allocations.edit', $allocation) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('housing.allocations.destroy', $allocation) }}" method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this allocation?')" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $allocations->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-home fa-3x text-muted mb-3"></i>
                    <h5>No housing allocations found</h5>
                    <p class="text-muted">Start by creating your first housing allocation.</p>
                    <a href="{{ route('housing.allocations.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Allocation
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
