@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Building Inspections</h4>
                <div class="page-title-right">
                    <a href="{{ route('engineering.inspections.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Schedule Inspection
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($inspections->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Inspection #</th>
                                    <th>Property</th>
                                    <th>Type</th>
                                    <th>Inspector</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inspections as $inspection)
                                <tr>
                                    <td><strong>{{ $inspection->inspection_number ?? 'INS-001' }}</strong></td>
                                    <td>{{ $inspection->property_address ?? 'Sample Property' }}</td>
                                    <td>{{ ucfirst($inspection->type ?? 'building') }}</td>
                                    <td>{{ $inspection->inspector_name ?? 'John Doe' }}</td>
                                    <td>{{ $inspection->inspection_date ? date('M d, Y', strtotime($inspection->inspection_date)) : 'To be scheduled' }}</td>
                                    <td>
                                        <span class="badge bg-warning">{{ ucfirst($inspection->status ?? 'scheduled') }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('engineering.inspections.show', $inspection->id ?? 1) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            <a href="{{ route('engineering.inspections.edit', $inspection->id ?? 1) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-search display-4 text-muted"></i>
                        <h5 class="mt-3">No Inspections Found</h5>
                        <p class="text-muted">Schedule your first inspection to get started.</p>
                        <a href="{{ route('engineering.inspections.create') }}" class="btn btn-primary">Schedule Inspection</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
