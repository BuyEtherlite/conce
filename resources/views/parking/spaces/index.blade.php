@extends('layouts.admin')

@section('title', 'Parking Spaces')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-parking text-primary"></i>
            Parking Spaces
        </h1>
        <div>
            <a href="{{ route('parking.spaces.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Space
            </a>
            <a href="{{ route('parking.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="zone_id" class="form-label">Zone</label>
                    <select class="form-control" name="zone_id">
                        <option value="">All Zones</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{ request('zone_id') == $zone->id ? 'selected' : '' }}>
                                {{ $zone->zone_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" name="status">
                        <option value="">All Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                        <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                        <option value="out_of_order" {{ request('status') == 'out_of_order' ? 'selected' : '' }}>Out of Order</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-control" name="type">
                        <option value="">All Types</option>
                        <option value="standard" {{ request('type') == 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="disabled" {{ request('type') == 'disabled' ? 'selected' : '' }}>Disabled</option>
                        <option value="loading" {{ request('type') == 'loading' ? 'selected' : '' }}>Loading</option>
                        <option value="visitor" {{ request('type') == 'visitor' ? 'selected' : '' }}>Visitor</option>
                        <option value="reserved" {{ request('type') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('parking.spaces.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Spaces Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Parking Spaces ({{ $spaces->total() }})</h6>
        </div>
        <div class="card-body">
            @if($spaces->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Space Number</th>
                                <th>Zone</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($spaces as $space)
                                <tr>
                                    <td>
                                        <strong>{{ $space->space_number }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $space->zone->zone_name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $space->getSpaceTypeLabel() }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'available' => 'success',
                                                'occupied' => 'warning',
                                                'reserved' => 'info',
                                                'out_of_order' => 'danger'
                                            ];
                                            $color = $statusColors[$space->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $space->status)) }}</span>
                                    </td>
                                    <td>
                                        @if(is_array($space->location))
                                            {{ implode(', ', array_filter($space->location)) }}
                                        @else
                                            {{ $space->location ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('parking.spaces.show', $space) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('parking.spaces.edit', $space) }}" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $spaces->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-parking fa-3x text-muted mb-3"></i>
                    <h5>No parking spaces found</h5>
                    <p class="text-muted">Start by creating a new parking space.</p>
                    <a href="{{ route('parking.spaces.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add First Space
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
