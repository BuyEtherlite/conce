@extends('layouts.admin')

@section('page-title', 'Water Meters')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💧 Water Meters</h4>
        <a href="{{ route('water.meters.create-reading') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Record Reading
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Water Meters</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Meter Number</th>
                            <th>Customer</th>
                            <th>Type</th>
                            <th>Current Reading</th>
                            <th>Last Reading Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($meters as $meter)
                        <tr>
                            <td>{{ $meter->meter_number }}</td>
                            <td>{{ $meter->connection->customer_name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($meter->meter_type) }}</td>
                            <td>{{ number_format($meter->current_reading, 2) }}</td>
                            <td>{{ $meter->last_reading_date ? $meter->last_reading_date->format('Y-m-d') : 'Never' }}</td>
                            <td>
                                @if($meter->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($meter->status === 'faulty')
                                    <span class="badge bg-danger">Faulty</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($meter->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-info">View</a>
                                <a href="#" class="btn btn-sm btn-outline-primary">History</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="py-4">
                                    <i class="fas fa-tachometer-alt fa-3x mb-3"></i>
                                    <p>No meters found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($meters->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $meters->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection