@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Infrastructure Management</h4>
                <div class="page-title-right">
                    <a href="{{ route('engineering.infrastructure.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add Infrastructure
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($infrastructure->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Infrastructure ID</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Condition</th>
                                    <th>Last Inspection</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($infrastructure as $item)
                                <tr>
                                    <td><strong>{{ $item->infrastructure_id ?? 'INF-001' }}</strong></td>
                                    <td>{{ $item->name ?? 'Main Road Network' }}</td>
                                    <td>{{ ucfirst($item->type ?? 'road') }}</td>
                                    <td>{{ $item->location ?? 'Central District' }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ ucfirst($item->status ?? 'operational') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($item->condition ?? 'good') }}</span>
                                    </td>
                                    <td>{{ $item->last_inspection ? date('M d, Y', strtotime($item->last_inspection)) : 'Never' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('engineering.infrastructure.show', $item->id ?? 1) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            <a href="{{ route('engineering.infrastructure.edit', $item->id ?? 1) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-building display-4 text-muted"></i>
                        <h5 class="mt-3">No Infrastructure Found</h5>
                        <p class="text-muted">Add your first infrastructure item to get started.</p>
                        <a href="{{ route('engineering.infrastructure.create') }}" class="btn btn-primary">Add Infrastructure</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
