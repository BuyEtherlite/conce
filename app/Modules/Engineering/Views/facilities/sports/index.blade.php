@extends('layouts.app')

@section('page-title', 'Sports Facilities')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Sports Facilities Management</h4>
                <div class="page-title-right">
                    <a href="{{ route('facilities.sports.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add Sports Facility
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
                    <h5 class="card-title mb-0">Sports Facilities</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Facility Name</th>
                                    <th>Sport Type</th>
                                    <th>Status</th>
                                    <th>Hourly Rate</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sports as $sport)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-dribbble me-2 text-primary"></i>
                                            {{ $sport['name'] }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $sport['sport_type'] }}</span>
                                    </td>
                                    <td>
                                        @if($sport['status'] == 'available')
                                            <span class="badge bg-success">Available</span>
                                        @elseif($sport['status'] == 'booked')
                                            <span class="badge bg-warning">Booked</span>
                                        @else
                                            <span class="badge bg-danger">Maintenance</span>
                                        @endif
                                    </td>
                                    <td>R{{ number_format($sport['hourly_rate'], 2) }}/hour</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('facilities.sports.show', $sport['id']) }}" class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('facilities.sports.edit', $sport['id']) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteSport({{ $sport['id'] }})">
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

    <!-- Sports Statistics -->
    <div class="row mt-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Facilities</h6>
                            <h3>{{ count($sports) }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-trophy display-4"></i>
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
                            <h3>{{ collect($sports)->where('status', 'available')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Currently Booked</h6>
                            <h3>{{ collect($sports)->where('status', 'booked')->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-calendar-check display-4"></i>
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
                            <h6 class="card-title">Sport Types</h6>
                            <h3>{{ collect($sports)->pluck('sport_type')->unique()->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-collection display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sport Types Breakdown -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Facilities by Sport Type</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $sportTypes = collect($sports)->groupBy('sport_type');
                        @endphp
                        @foreach($sportTypes as $type => $facilities)
                        <div class="col-md-4 mb-3">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <h6 class="card-title">{{ $type }}</h6>
                                    <h4 class="text-primary">{{ $facilities->count() }}</h4>
                                    <small class="text-muted">
                                        Available: {{ $facilities->where('status', 'available')->count() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteSport(id) {
    if (confirm('Are you sure you want to delete this sports facility?')) {
        // Delete logic would go here
        alert('Sports facility deleted successfully');
    }
}
</script>
@endsection
