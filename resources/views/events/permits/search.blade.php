@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Search Event Permits</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('events.permits.index') }}">Event Permits</a></li>
                        <li class="breadcrumb-item active">Search</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Search Results</h4>
                </div>
                <div class="card-body">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('events.permits.search') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Search Term</label>
                                <input type="text" name="q" class="form-control" value="{{ $query }}" 
                                       placeholder="Permit number, event name, or organizer name">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="under_review" {{ $status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                    <option value="requires_inspection" {{ $status == 'requires_inspection' ? 'selected' : '' }}>Requires Inspection</option>
                                    <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="expired" {{ $status == 'expired' ? 'selected' : '' }}>Expired</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary d-block w-100">Search</button>
                            </div>
                        </div>
                    </form>

                    @if($query || $status)
                        <div class="alert alert-info">
                            <strong>Search Results:</strong> 
                            @if($query)
                                Searching for "{{ $query }}"
                            @endif
                            @if($status)
                                @if($query) with @endif
                                Status: {{ ucfirst(str_replace('_', ' ', $status)) }}
                            @endif
                            ({{ $permits->total() }} results found)
                        </div>
                    @endif

                    @if($permits->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Permit Number</th>
                                        <th>Event Name</th>
                                        <th>Organizer</th>
                                        <th>Event Date</th>
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
                                            <td>
                                                <span class="badge {{ $permit->status_badge }}">
                                                    {{ ucfirst(str_replace('_', ' ', $permit->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('events.permits.show', $permit) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $permits->appends(request()->query())->links() }}
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">No Results Found</h5>
                            <p class="text-muted">No permits match your search criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
