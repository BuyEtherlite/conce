@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-0">Market Management Dashboard</h2>
                    <p class="text-muted">Overview of all market operations and revenue</p>
                </div>
                <div>
                    <a href="{{ route('markets.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-1"></i>All Markets
                    </a>
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ number_format($totalMarkets) }}</h4>
                                    <p class="card-text">Total Markets</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-store fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ number_format($totalStalls) }}</h4>
                                    <p class="card-text">Total Stalls</p>
                                    <small>{{ number_format($occupiedStalls) }} occupied</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-th-large fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">R{{ number_format($monthlyRevenue, 2) }}</h4>
                                    <p class="card-text">Monthly Revenue</p>
                                    <small>{{ now()->format('F Y') }}</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-money-bill-wave fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card bg-warning text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">R{{ number_format($outstandingRevenue, 2) }}</h4>
                                    <p class="card-text">Outstanding Revenue</p>
                                    <small>Pending collections</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Occupancy Rates -->
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Market Occupancy Rates</h5>
                        </div>
                        <div class="card-body">
                            @foreach($occupancyRates as $market)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>{{ $market['name'] }}</span>
                                    <span>{{ $market['occupied'] }}/{{ $market['total'] }} ({{ $market['rate'] }}%)</span>
                                </div>
                                <div class="progress">
                                    @php
                                        $class = $market['rate'] >= 80 ? 'success' : ($market['rate'] >= 50 ? 'warning' : 'danger');
                                    @endphp
                                    <div class="progress-bar bg-{{ $class }}" 
                                         role="progressbar" 
                                         style="width: {{ $market['rate'] }}%"
                                         aria-valuenow="{{ $market['rate'] }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Top Performing Markets -->
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Top Revenue Generating Markets</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Market</th>
                                            <th>Revenue</th>
                                            <th>Stalls</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topPerformingMarkets as $market)
                                        <tr>
                                            <td>
                                                <a href="{{ route('markets.show', $market) }}">
                                                    {{ $market->name }}
                                                </a>
                                            </td>
                                            <td>R{{ number_format($market->revenue_collections_sum_amount_paid, 2) }}</td>
                                            <td>{{ $market->total_stalls }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Allocations -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Stall Allocations</h5>
                </div>
                <div class="card-body">
                    @if($recentAllocations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Allocation #</th>
                                        <th>Market</th>
                                        <th>Stall</th>
                                        <th>Tenant</th>
                                        <th>Business</th>
                                        <th>Period</th>
                                        <th>Monthly Rent</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAllocations as $allocation)
                                    <tr>
                                        <td>
                                            <code>{{ $allocation->allocation_number }}</code>
                                        </td>
                                        <td>{{ $allocation->stall->market->name }}</td>
                                        <td>{{ $allocation->stall->stall_number }}</td>
                                        <td>
                                            {{ $allocation->tenant_name }}
                                            <br>
                                            <small class="text-muted">{{ $allocation->tenant_phone }}</small>
                                        </td>
                                        <td>
                                            {{ $allocation->business_name }}
                                            <br>
                                            <small class="text-muted">{{ $allocation->business_type }}</small>
                                        </td>
                                        <td>
                                            {{ $allocation->start_date->format('d M Y') }} -
                                            {{ $allocation->end_date->format('d M Y') }}
                                        </td>
                                        <td>R{{ number_format($allocation->monthly_rent, 2) }}</td>
                                        <td>
                                            @if($allocation->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($allocation->status === 'expired')
                                                <span class="badge bg-danger">Expired</span>
                                            @elseif($allocation->status === 'terminated')
                                                <span class="badge bg-warning">Terminated</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($allocation->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h6>No Recent Allocations</h6>
                            <p class="text-muted">Recent stall allocations will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
