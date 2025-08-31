@extends('layouts.admin')

@section('title', 'Parking Violations')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-exclamation-triangle text-warning"></i>
            Parking Violations
        </h1>
        <div>
            <a href="{{ route('parking.violations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Issue Violation
            </a>
            <a href="{{ route('parking.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Violations
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $violations->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Overdue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $violations->where('status', 'overdue')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Paid
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $violations->where('status', 'paid')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Outstanding Amount
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                R{{ number_format($violations->sum('fine_amount') - $violations->sum('amount_paid'), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" name="status">
                        <option value="">All Status</option>
                        <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>Issued</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="contested" {{ request('status') == 'contested' ? 'selected' : '' }}>Contested</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="zone_id" class="form-label">Zone</label>
                    <select class="form-control" name="zone_id">
                        <option value="">All Zones</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{ request('zone_id') == $zone->id ? 'selected' : '' }}>
                                {{ $zone->zone_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" name="search" placeholder="Violation number, registration..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('parking.violations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Violations Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Violations ({{ $violations->total() }})</h6>
        </div>
        <div class="card-body">
            @if($violations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Violation #</th>
                                <th>Registration</th>
                                <th>Type</th>
                                <th>Zone</th>
                                <th>Date/Time</th>
                                <th>Fine Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($violations as $violation)
                                <tr>
                                    <td>
                                        <strong>{{ $violation->violation_number }}</strong>
                                    </td>
                                    <td>
                                        {{ $violation->vehicle_registration }}
                                        @if($violation->vehicle_make)
                                            <br><small class="text-muted">{{ $violation->vehicle_make }} {{ $violation->vehicle_model }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $violation->getViolationTypeLabel() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $violation->zone->zone_name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        {{ $violation->violation_datetime->format('Y-m-d H:i') }}
                                    </td>
                                    <td>
                                        <strong>R{{ number_format($violation->fine_amount, 2) }}</strong>
                                        @if($violation->amount_paid > 0)
                                            <br><small class="text-success">Paid: R{{ number_format($violation->amount_paid, 2) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'issued' => 'primary',
                                                'paid' => 'success',
                                                'overdue' => 'danger',
                                                'contested' => 'warning',
                                                'cancelled' => 'secondary'
                                            ];
                                            $color = $statusColors[$violation->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ ucfirst($violation->status) }}</span>
                                        @if($violation->isOverdue())
                                            <br><span class="badge bg-danger">Overdue</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('parking.violations.show', $violation) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(!$violation->isPaid())
                                                <a href="{{ route('parking.violations.edit', $violation) }}" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-edit"></i>
                                                </a>
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
                    {{ $violations->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
                    <h5>No violations found</h5>
                    <p class="text-muted">No parking violations match your current filters.</p>
                    <a href="{{ route('parking.violations.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Issue First Violation
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection