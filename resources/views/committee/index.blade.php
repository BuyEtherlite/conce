@extends('layouts.app')

@section('title', 'Committee Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Committee Management</h6>
                    <a href="{{ route('committee.committees.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> New Committee
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($committees) && $committees->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Chairperson</th>
                                        <th>Meeting Frequency</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($committees as $committee)
                                    <tr>
                                        <td>{{ $committee->name }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $committee->committee_type)) }}</td>
                                        <td>{{ $committee->chairperson->name ?? 'Not assigned' }}</td>
                                        <td>{{ ucfirst($committee->meeting_frequency) }}</td>
                                        <td>
                                            <span class="badge {{ $committee->is_active ? 'badge-success' : 'badge-danger' }}">
                                                {{ $committee->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('committee.committees.show', $committee) }}" class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('committee.committees.edit', $committee) }}" class="btn btn-sm btn-warning">Edit</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Committees Found</h5>
                            <p class="text-muted">Start by creating your first committee.</p>
                            <a href="{{ route('committee.committees.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Committee
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection