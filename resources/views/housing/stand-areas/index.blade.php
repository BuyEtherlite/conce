@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Stand Areas Management</h2>
                <a href="{{ route('housing.stand-areas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Stand Area
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ $standAreas->sum('total_stands') }}</h4>
                                    <p class="card-text">Total Stands</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-map fa-2x"></i>
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
                                    <h4 class="card-title">{{ $standAreas->sum('available_stands') }}</h4>
                                    <p class="card-text">Available Stands</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x"></i>
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
                                    <h4 class="card-title">{{ $standAreas->sum('allocated_stands') }}</h4>
                                    <p class="card-text">Allocated Stands</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-user-check fa-2x"></i>
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
                                    <h4 class="card-title">{{ $standAreas->count() }}</h4>
                                    <p class="card-text">Stand Areas</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-layer-group fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stand Areas Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Stand Areas</h5>
                </div>
                <div class="card-body">
                    @if($standAreas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Sector Type</th>
                                        <th>Location</th>
                                        <th>Total Stands</th>
                                        <th>Available</th>
                                        <th>Allocated</th>
                                        <th>Occupancy</th>
                                        <th>Development Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($standAreas as $area)
                                    <tr>
                                        <td><strong>{{ $area->code }}</strong></td>
                                        <td>{{ $area->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $area->sector_type === 'residential' ? 'primary' : 
                                                ($area->sector_type === 'commercial' ? 'success' : 
                                                ($area->sector_type === 'industrial' ? 'warning' : 'info')) }}">
                                                {{ $area->sector_type_display }}
                                            </span>
                                        </td>
                                        <td>{{ $area->location }}</td>
                                        <td>{{ number_format($area->total_stands) }}</td>
                                        <td>{{ number_format($area->available_stands) }}</td>
                                        <td>{{ number_format($area->allocated_stands) }}</td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ $area->occupancy_rate }}%">
                                                    {{ $area->occupancy_rate }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $area->development_status === 'developed' ? 'success' : 
                                                ($area->development_status === 'under_development' ? 'warning' : 'secondary') }}">
                                                {{ $area->development_status_display }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('housing.stand-areas.show', $area) }}" 
                                                   class="btn btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('housing.stand-areas.edit', $area) }}" 
                                                   class="btn btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($area->allocated_stands == 0)
                                                <form action="{{ route('housing.stand-areas.destroy', $area) }}" 
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this stand area?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $standAreas->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-map fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No Stand Areas Found</h4>
                            <p class="text-muted">Create your first stand area to get started.</p>
                            <a href="{{ route('housing.stand-areas.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Stand Area
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
