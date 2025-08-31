@extends('layouts.admin')

@section('page-title', 'Water Connections')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💧 Water Connections</h4>
        <a href="{{ route('water.connections.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>New Connection
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Water Connections</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Connection #</th>
                            <th>Customer Name</th>
                            <th>Address</th>
                            <th>Type</th>
                            <th>Meter Size</th>
                            <th>Status</th>
                            <th>Installation Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($connections as $connection)
                        <tr>
                            <td>{{ $connection->connection_number }}</td>
                            <td>{{ $connection->customer_name }}</td>
                            <td>{{ Str::limit($connection->property_address, 50) }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($connection->connection_type) }}</span>
                            </td>
                            <td>{{ $connection->meter_size }}</td>
                            <td>
                                @if($connection->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($connection->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">{{ ucfirst($connection->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $connection->installation_date->format('Y-m-d') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-info" title="Meters">
                                        <i class="fas fa-tachometer-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-tint fa-3x mb-3"></i>
                                    <p>No water connections found</p>
                                    <a href="{{ route('water.connections.create') }}" class="btn btn-primary">
                                        Add First Connection
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($connections->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $connections->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
