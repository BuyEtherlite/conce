@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Expired Event Permits</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('events.permits.index') }}">Event Permits</a></li>
                        <li class="breadcrumb-item active">Expired</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title mb-0">Expired Permits</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('events.permits.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>New Application
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($permits->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Permit Number</th>
                                        <th>Event Name</th>
                                        <th>Organizer</th>
                                        <th>Event Date</th>
                                        <th>Approved Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permits as $permit)
                                        <tr>
                                            <td>
                                                <a href="{{ route('events.permits.show', $permit) }}" class="fw-medium">
                                                    {{ $permit->permit_number }}
                                                </a>
                                            </td>
                                            <td>{{ $permit->event_name }}</td>
                                            <td>{{ $permit->organizer_name }}</td>
                                            <td>{{ $permit->event_date->format('M d, Y') }}</td>
                                            <td>{{ $permit->approved_at ? $permit->approved_at->format('M d, Y') : '-' }}</td>
                                            <td>
                                                <span class="badge bg-secondary">Expired</span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-soft-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('events.permits.show', $permit) }}">
                                                                <i class="bi bi-eye me-2"></i>View Details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('events.permits.print', $permit) }}">
                                                                <i class="bi bi-printer me-2"></i>Print Permit
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $permits->links() }}
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-clock text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">No Expired Permits</h5>
                            <p class="text-muted">There are currently no expired event permits.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
