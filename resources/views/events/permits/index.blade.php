@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-calendar-event"></i> Event Permits Management</h2>
                <div class="btn-group">
                    <a href="{{ route('events.permits.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> New Permit Application
                    </a>
                    <a href="{{ route('events.categories.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-tags"></i> Categories
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ number_format($stats['total_permits']) }}</h4>
                                    <p class="mb-0">Total Permits</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-calendar-event fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ number_format($stats['pending_permits']) }}</h4>
                                    <p class="mb-0">Pending Review</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-clock fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ number_format($stats['approved_permits']) }}</h4>
                                    <p class="mb-0">Approved</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-check-circle fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ number_format($stats['rejected_permits']) }}</h4>
                                    <p class="mb-0">Rejected</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-x-circle fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Main Permits List -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Event Permit Applications</h5>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('events.permits.index') }}" class="btn btn-outline-primary active">All</a>
                                <a href="{{ route('events.permits.rejected') }}" class="btn btn-outline-danger">Rejected</a>
                                <a href="{{ route('events.permits.expired') }}" class="btn btn-outline-warning">Expired</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Search Bar -->
                            <form method="GET" action="{{ route('events.permits.search') }}" class="mb-3">
                                <div class="input-group">
                                    <input type="text" name="q" class="form-control" placeholder="Search permits..." value="{{ request('q') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>

                            @if($permits->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Application #</th>
                                                <th>Event Name</th>
                                                <th>Date</th>
                                                <th>Venue</th>
                                                <th>Organizer</th>
                                                <th>Category</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($permits as $permit)
                                            <tr>
                                                <td>
                                                    <strong>{{ $permit->application_number }}</strong>
                                                </td>
                                                <td>
                                                    <strong>{{ $permit->event_name }}</strong>
                                                    <br><small class="text-muted">{{ Str::limit($permit->event_description, 50) }}</small>
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($permit->event_date)->format('d M Y') }}
                                                    <br><small class="text-muted">{{ $permit->start_time }} - {{ $permit->end_time }}</small>
                                                </td>
                                                <td>{{ $permit->venue }}</td>
                                                <td>
                                                    {{ $permit->organizer_name }}
                                                    <br><small class="text-muted">{{ $permit->organizer_contact }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">{{ $permit->category_name ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    @switch($permit->status)
                                                        @case('pending')
                                                            <span class="badge bg-warning">Pending</span>
                                                            @break
                                                        @case('approved')
                                                            <span class="badge bg-success">Approved</span>
                                                            @break
                                                        @case('rejected')
                                                            <span class="badge bg-danger">Rejected</span>
                                                            @break
                                                        @case('expired')
                                                            <span class="badge bg-secondary">Expired</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-light text-dark">{{ ucfirst($permit->status) }}</span>
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('events.permits.show', $permit->id) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="{{ route('events.permits.edit', $permit->id) }}" class="btn btn-sm btn-outline-warning">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('events.permits.destroy', $permit->id) }}" method="POST" class="d-inline" 
                                                              onsubmit="return confirm('Are you sure you want to delete this permit?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center">
                                    {{ $permits->links() }}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-calendar-event fs-1 text-muted mb-3"></i>
                                    <h5>No event permits found</h5>
                                    <p class="text-muted">Start by creating your first event permit application.</p>
                                    <a href="{{ route('events.permits.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-1"></i>New Permit Application
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Upcoming Events</h5>
                        </div>
                        <div class="card-body">
                            @forelse ($upcomingEvents as $event)
                                <div class="border-bottom pb-3 mb-3">
                                    <h6 class="mb-1">{{ $event->event_name }}</h6>
                                    <p class="mb-1 text-muted small">{{ $event->venue }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-primary">
                                            <i class="fas fa-calendar"></i>
                                            {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-users"></i>
                                            {{ number_format($event->expected_attendance) }} people
                                        </small>
                                    </div>
                                    <div class="mt-1">
                                        <span class="badge bg-light text-dark">{{ $event->category_name }}</span>
                                        @if($event->days_to_event <= 7)
                                            <span class="badge bg-warning">
                                                {{ $event->days_to_event }} days to go
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center">No upcoming events.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
