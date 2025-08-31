@extends('layouts.app')

@section('page-title', 'Tenant Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>🏠 Tenant Management</h4>
        <a href="{{ route('housing.tenants.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Tenant
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
                            <span>Total Tenants</span>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
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
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['inactive'] }}</h4>
                            <span>Inactive</span>
                        </div>
                        <i class="fas fa-times-circle fa-2x opacity-50"></i>
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
    </div>

    <!-- Tenants Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Tenants List</h5>
        </div>
        <div class="card-body">
            @if($tenants->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tenant ID</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Property</th>
                                <th>Move-in Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tenants as $tenant)
                                <tr>
                                    <td>TEN-{{ $tenant->id }}</td>
                                    <td>
                                        <strong>{{ $tenant->name }}</strong><br>
                                        <small class="text-muted">ID: {{ $tenant->id_number }}</small>
                                    </td>
                                    <td>
                                        @if($tenant->email)
                                            <small>📧 {{ $tenant->email }}</small><br>
                                        @endif
                                        @if($tenant->phone)
                                            <small>📞 {{ $tenant->phone }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($tenant->allocation && $tenant->allocation->property)
                                            <strong>{{ $tenant->allocation->property->address }}</strong><br>
                                            <small class="text-muted">{{ $tenant->allocation->property->property_type }}</small>
                                        @else
                                            <span class="text-muted">No property assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($tenant->move_in_date)
                                            {{ $tenant->move_in_date->format('d M Y') }}
                                        @else
                                            <span class="text-muted">Not moved in</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($tenant->status) {
                                                'active' => 'bg-success',
                                                'inactive' => 'bg-secondary',
                                                'pending' => 'bg-warning',
                                                default => 'bg-light text-dark'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($tenant->status) }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('housing.tenants.show', $tenant) }}" class="btn btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('housing.tenants.edit', $tenant) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('housing.tenants.destroy', $tenant) }}" method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to remove this tenant?')" style="display: inline;">
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
                    {{ $tenants->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5>No tenants found</h5>
                    <p class="text-muted">Start by adding your first tenant to the system.</p>
                    <a href="{{ route('housing.tenants.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add First Tenant
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
