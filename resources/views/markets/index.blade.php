@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-0">Market Management</h2>
                    <p class="text-muted">Manage market facilities and stall allocations</p>
                </div>
                <div>
                    <a href="{{ route('markets.dashboard') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-chart-bar me-1"></i>Dashboard
                    </a>
                    <a href="{{ route('markets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Add Market
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ number_format($stats['total_markets']) }}</h4>
                                    <p class="card-text">Total Markets</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-store fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ number_format($stats['total_stalls']) }}</h4>
                                    <p class="card-text">Total Stalls</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-th-large fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ number_format($stats['occupied_stalls']) }}</h4>
                                    <p class="card-text">Occupied Stalls</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">R{{ number_format($stats['total_revenue'], 2) }}</h4>
                                    <p class="card-text">Monthly Revenue</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-money-bill-wave fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Markets List -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Markets</h5>
                </div>
                <div class="card-body">
                    @if($markets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Manager</th>
                                        <th>Location</th>
                                        <th>Manager</th>
                                        <th>Total Stalls</th>
                                        <th>Occupied Stalls</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($markets as $market)
                                    <tr>
                                        <td>
                                            <code>{{ $market->code }}</code>
                                        </td>
                                        <td>
                                            <strong>{{ $market->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($market->address, 50) }}</small>
                                        </td>
                                        <td>
                                            {{ $market->manager_name }}
                                            <br>
                                            <small class="text-muted">{{ $market->manager_phone }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ number_format($market->total_stalls) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ number_format($market->occupied_stalls) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $rate = $market->occupancy_rate;
                                                $class = $rate >= 80 ? 'success' : ($rate >= 50 ? 'warning' : 'danger');
                                            @endphp
                                            <span class="badge bg-{{ $class }}">{{ $rate }}%</span>
                                        </td>
                                        <td>
                                            @if($market->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($market->status === 'maintenance')
                                                <span class="badge bg-warning">Maintenance</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('markets.show', $market) }}"
                                                   class="btn btn-sm btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('markets.edit', $market) }}"
                                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="confirmDelete({{ $market->id }})" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $markets->links() }}
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-store fa-3x text-muted mb-3"></i>
                            <h5>No Markets Found</h5>
                            <p class="text-muted">Start by adding your first market.</p>
                            <a href="{{ route('markets.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Add Market
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this market? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(marketId) {
    document.getElementById('deleteForm').action = `/markets/${marketId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection