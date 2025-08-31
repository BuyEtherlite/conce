@extends('layouts.admin')

@section('page-title', 'Water Rates')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Side Navigation -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5>💧 Water Management</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('water.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('water.connections.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-plug me-2"></i>Connections
                    </a>
                    <a href="{{ route('water.meters.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i>Meters & Readings
                    </a>
                    <a href="{{ route('water.billing.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-invoice-dollar me-2"></i>Billing
                    </a>
                    <a href="{{ route('water.rates.index') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-dollar-sign me-2"></i>Water Rates
                    </a>
                    <a href="{{ route('water.quality.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-flask me-2"></i>Quality Testing
                    </a>
                    <a href="{{ route('water.infrastructure.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tools me-2"></i>Infrastructure
                    </a>
                    <a href="{{ route('water.reports.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-bar me-2"></i>Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>💰 Water Rates Management</h4>
                <a href="{{ route('water.rates.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add New Rate
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Current Water Rates</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Rate Name</th>
                                    <th>Connection Type</th>
                                    <th>Base Charge</th>
                                    <th>Rate per Unit</th>
                                    <th>Minimum Charge</th>
                                    <th>Effective Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rates as $rate)
                                <tr>
                                    <td>{{ $rate->rate_name }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($rate->connection_type) }}</span>
                                    </td>
                                    <td>${{ number_format($rate->base_charge, 2) }}</td>
                                    <td>${{ number_format($rate->rate_per_unit, 2) }}</td>
                                    <td>${{ number_format($rate->minimum_charge, 2) }}</td>
                                    <td>{{ $rate->effective_date->format('M d, Y') }}</td>
                                    <td>
                                        @if($rate->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('water.rates.edit', $rate) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-dollar-sign fa-3x mb-3"></i>
                                            <p>No water rates configured</p>
                                            <a href="{{ route('water.rates.create') }}" class="btn btn-primary">
                                                Create First Rate
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($rates->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $rates->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('page-title', 'Water Rates Management')

@section('content')
<div class="container-fluid">
    <!-- Water Management Side Navigation -->
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">💧 Water Management</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('water.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-line me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('water.connections.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-link me-2"></i>Water Connections
                    </a>
                    <a href="{{ route('water.meters.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i>Meter Management
                    </a>
                    <a href="{{ route('water.billing.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-invoice-dollar me-2"></i>Billing
                    </a>
                    <a href="{{ route('water.quality.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-flask me-2"></i>Quality Control
                    </a>
                    <a href="{{ route('water.rates.index') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-dollar-sign me-2"></i>Water Rates
                    </a>
                    <a href="{{ route('water.infrastructure.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-cogs me-2"></i>Infrastructure
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>💰 Water Rates Management</h4>
                <a href="{{ route('water.rates.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Create New Rate
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Water Rates</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Rate Type</th>
                                    <th>Rate per m³</th>
                                    <th>Min. Charge</th>
                                    <th>Service Charge</th>
                                    <th>Effective Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rates as $rate)
                                <tr>
                                    <td>{{ $rate->name }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($rate->rate_type) }}</span>
                                    </td>
                                    <td>${{ number_format($rate->rate_per_unit, 2) }}</td>
                                    <td>${{ number_format($rate->minimum_charge ?? 0, 2) }}</td>
                                    <td>${{ number_format($rate->service_charge ?? 0, 2) }}</td>
                                    <td>{{ $rate->effective_date->format('Y-m-d') }}</td>
                                    <td>
                                        @if($rate->is_active && $rate->effective_date <= now() && (!$rate->end_date || $rate->end_date >= now()))
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('water.rates.edit', $rate) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('water.rates.destroy', $rate) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-dollar-sign fa-3x mb-3"></i>
                                            <p>No water rates configured</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($rates->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $rates->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
