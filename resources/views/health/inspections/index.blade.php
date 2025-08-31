@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Health Inspections</h1>
                <a href="{{ route('health.inspections.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Schedule New Inspection
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    @if(count($inspections) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Inspection ID</th>
                                        <th>Establishment</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Inspector</th>
                                        <th>Status</th>
                                        <th>Score</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inspections as $inspection)
                                    <tr>
                                        <td>{{ $inspection['id'] }}</td>
                                        <td>{{ $inspection['establishment'] }}</td>
                                        <td>{{ $inspection['type'] }}</td>
                                        <td>{{ $inspection['date'] }}</td>
                                        <td>{{ $inspection['inspector'] }}</td>
                                        <td>
                                            <span class="badge badge-{{ $inspection['status'] === 'Completed' ? 'success' : ($inspection['status'] === 'In Progress' ? 'warning' : 'info') }}">
                                                {{ $inspection['status'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($inspection['score'])
                                                <span class="badge badge-{{ $inspection['score'] >= 90 ? 'success' : ($inspection['score'] >= 70 ? 'warning' : 'danger') }}">
                                                    {{ $inspection['score'] }}%
                                                </span>
                                            @else
                                                <span class="text-muted">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Inspections Found</h5>
                            <p class="text-muted">Schedule your first health inspection.</p>
                            <a href="{{ route('health.inspections.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Schedule New Inspection
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
