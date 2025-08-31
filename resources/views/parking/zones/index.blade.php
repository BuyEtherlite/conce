@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Parking Zones Management</h4>
                <div class="page-title-right">
                    <a href="{{ route('parking.zones.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create New Zone
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($zones->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Zone Code</th>
                                    <th>Zone Name</th>
                                    <th>Type</th>
                                    <th>Hourly Rate</th>
                                    <th>Spaces</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($zones as $zone)
                                <tr>
                                    <td><strong>{{ $zone->zone_code }}</strong></td>
                                    <td>{{ $zone->zone_name }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $zone->zone_type)) }}</span>
                                    </td>
                                    <td>${{ number_format($zone->hourly_rate, 2) }}</td>
                                    <td>{{ $zone->spaces_count ?? 0 }}</td>
                                    <td>
                                        @if($zone->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary">View</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $zones->links() }}
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-geo-alt display-4 text-muted"></i>
                        <h5 class="mt-3">No Parking Zones Found</h5>
                        <p class="text-muted">Create your first parking zone to get started.</p>
                        <a href="{{ route('parking.zones.create') }}" class="btn btn-primary">Create Zone</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
