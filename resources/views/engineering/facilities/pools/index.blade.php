@extends('layouts.app')

@section('page-title', 'Swimming Pools')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Swimming Pool Facilities</h4>
                <div class="page-title-right">
                    <a href="{{ route('facilities.pools.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Pool
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pool Management</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Pool Name</th>
                                    <th>Status</th>
                                    <th>Capacity</th>
                                    <th>Hourly Rate</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pools as $pool)
                                <tr>
                                    <td>{{ $pool['name'] }}</td>
                                    <td>
                                        @if($pool['status'] == 'available')
                                            <span class="badge bg-success">Available</span>
                                        @elseif($pool['status'] == 'booked')
                                            <span class="badge bg-warning">Booked</span>
                                        @else
                                            <span class="badge bg-danger">Maintenance</span>
                                        @endif
                                    </td>
                                    <td>{{ $pool['capacity'] }} people</td>
                                    <td>R{{ number_format($pool['hourly_rate'], 2) }}/hour</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('facilities.pools.show', $pool['id']) }}" class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('facilities.pools.edit', $pool['id']) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deletePool({{ $pool['id'] }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pool Statistics -->
    <div class="row mt-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Pools</h6>
                            <h3>{{ count($pools) }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-water display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Available</h6>
                            <h3>{{ collect($pools)->where('status', 'available')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Maintenance</h6>
                            <h3>{{ collect($pools)->where('status', 'maintenance')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-tools display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Capacity</h6>
                            <h3>{{ collect($pools)->sum('capacity') }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deletePool(id) {
    if (confirm('Are you sure you want to delete this pool?')) {
        // Delete logic would go here
        alert('Pool deleted successfully');
    }
}
</script>
@endsection
