@extends('layouts.admin')

@section('title', 'Markets Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-store me-2"></i>
                        Markets Management
                    </h5>
                    <a href="{{ route('markets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New Market
                    </a>
                </div>
                <div class="card-body">
                    <!-- Quick Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Total Markets</h6>
                                            <h4>{{ $stats['total_markets'] ?? 0 }}</h4>
                                        </div>
                                        <i class="fas fa-store fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Active Stalls</h6>
                                            <h4>{{ $stats['active_stalls'] ?? 0 }}</h4>
                                        </div>
                                        <i class="fas fa-boxes fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Monthly Revenue</h6>
                                            <h4>${{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</h4>
                                        </div>
                                        <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Vendors</h6>
                                            <h4>{{ $stats['total_vendors'] ?? 0 }}</h4>
                                        </div>
                                        <i class="fas fa-users fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Quick Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <a href="{{ route('markets.stalls.index') }}" class="btn btn-outline-primary w-100">
                                                <i class="fas fa-boxes mb-2 d-block"></i>
                                                Manage Stalls
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <a href="{{ route('markets.vendors.index') }}" class="btn btn-outline-success w-100">
                                                <i class="fas fa-users mb-2 d-block"></i>
                                                Manage Vendors
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <a href="{{ route('markets.dashboard') }}" class="btn btn-outline-info w-100">
                                                <i class="fas fa-chart-bar mb-2 d-block"></i>
                                                View Dashboard
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <a href="#" class="btn btn-outline-warning w-100">
                                                <i class="fas fa-file-alt mb-2 d-block"></i>
                                                Reports
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Markets List -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Market Name</th>
                                    <th>Location</th>
                                    <th>Total Stalls</th>
                                    <th>Occupied Stalls</th>
                                    <th>Monthly Revenue</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($markets ?? [] as $market)
                                <tr>
                                    <td>
                                        <strong>{{ $market->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $market->type ?? 'General Market' }}</small>
                                    </td>
                                    <td>{{ $market->location ?? 'N/A' }}</td>
                                    <td>{{ $market->total_stalls ?? 0 }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ $market->occupied_stalls ?? 0 }}</span>
                                        / {{ $market->total_stalls ?? 0 }}
                                    </td>
                                    <td>${{ number_format($market->monthly_revenue ?? 0, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $market->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($market->status ?? 'inactive') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('markets.show', $market->id) }}" class="btn btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('markets.edit', $market->id) }}" class="btn btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-store fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No markets found. <a href="{{ route('markets.create') }}">Create your first market</a>.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
