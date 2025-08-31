@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Housing Areas</h4>
                    <a href="{{ route('housing.areas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Area
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($areas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Type</th>
                                        <th>Total Stands</th>
                                        <th>Available</th>
                                        <th>Occupancy</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($areas as $area)
                                        <tr>
                                            <td><strong>{{ $area->code }}</strong></td>
                                            <td>{{ $area->name }}</td>
                                            <td>{{ $area->location }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ ucfirst($area->area_type) }}</span>
                                            </td>
                                            <td>{{ $area->total_stands }}</td>
                                            <td>{{ $area->available_stands }}</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar" role="progressbar" 
                                                         style="width: {{ $area->occupancy_rate }}%"
                                                         aria-valuenow="{{ $area->occupancy_rate }}" 
                                                         aria-valuemin="0" aria-valuemax="100">
                                                        {{ $area->occupancy_rate }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $area->status_badge }}">
                                                    {{ ucfirst($area->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('housing.areas.show', $area) }}" 
                                                       class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('housing.areas.edit', $area) }}" 
                                                       class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" 
                                                          action="{{ route('housing.areas.destroy', $area) }}" 
                                                          style="display: inline;"
                                                          onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
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

                        <div class="d-flex justify-content-center">
                            {{ $areas->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No housing areas found</h5>
                            <p class="text-muted">Create your first housing area to get started.</p>
                            <a href="{{ route('housing.areas.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Housing Area
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
