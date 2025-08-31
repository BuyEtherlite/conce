@extends('layouts.admin')

@section('page-title', 'Cemetery Plots')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📍 Cemetery Plots</h4>
        <div class="btn-group" role="group">
            <a href="{{ route('cemeteries.plots.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Add New Plot
            </a>
            <a href="{{ route('cemeteries.sections.create') }}" class="btn btn-outline-primary">
                <i class="fas fa-layer-group me-1"></i>Add Section
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('cemeteries.plots.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="available">Available</option>
                            <option value="reserved">Reserved</option>
                            <option value="occupied">Occupied</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="section" class="form-select">
                            <option value="">All Sections</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->section_name }}">{{ $section->section_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="size" class="form-select">
                            <option value="">All Sizes</option>
                            <option value="Single">Single</option>
                            <option value="Double">Double</option>
                            <option value="Family">Family</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-primary me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('cemeteries.plots.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Plots Table -->
    <div class="card">
        <div class="card-body">
            @if($plots->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Plot Number</th>
                                <th>Section</th>
                                <th>Size</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Burials</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plots as $plot)
                                <tr>
                                    <td>
                                        <strong>{{ $plot->plot_number }}</strong>
                                        @if($plot->description)
                                            <br><small class="text-muted">{{ $plot->description }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $plot->section }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $plot->size }}</span>
                                    </td>
                                    <td>${{ number_format($plot->price, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $plot->status_color }}">
                                            {{ ucfirst($plot->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($plot->burial_records_count > 0)
                                            <span class="badge bg-secondary">{{ $plot->burial_records_count }}</span>
                                        @else
                                            <span class="text-muted">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('cemeteries.plots.show', $plot->id) }}" 
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('cemeteries.plots.edit', $plot->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($plot->burial_records_count == 0)
                                                <form method="POST" 
                                                      action="{{ route('cemeteries.plots.destroy', $plot->id) }}" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this plot?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
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

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $plots->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-map fa-3x text-muted mb-3"></i>
                    <h5>No Cemetery Plots Found</h5>
                    <p class="text-muted">Start by adding your first cemetery plot.</p>
                    <a href="{{ route('cemeteries.plots.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Add First Plot
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
