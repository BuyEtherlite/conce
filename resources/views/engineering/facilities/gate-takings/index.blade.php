@extends('layouts.app')

@section('page-title', 'Gate Takings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Facility Gate Takings</h4>
                <div class="page-title-right">
                    <a href="{{ route('facilities.gate-takings.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Record Gate Taking
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Today's Revenue</h6>
                            <h3>R{{ number_format(collect($gateTakings)->where('date', now()->format('Y-m-d'))->sum('amount'), 2) }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-cash display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Today's Visitors</h6>
                            <h3>{{ collect($gateTakings)->where('date', now()->format('Y-m-d'))->sum('visitors') }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Revenue</h6>
                            <h3>R{{ number_format(collect($gateTakings)->sum('amount'), 2) }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-graph-up display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Visitors</h6>
                            <h3>{{ collect($gateTakings)->sum('visitors') }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-check display-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Gate Taking Records</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Facility</th>
                                    <th>Amount</th>
                                    <th>Visitors</th>
                                    <th>Avg per Visitor</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gateTakings as $taking)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($taking['date'])->format('d M Y') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-building me-2 text-primary"></i>
                                            {{ $taking['facility'] }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">R{{ number_format($taking['amount'], 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $taking['visitors'] }} visitors</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">R{{ number_format($taking['amount'] / max($taking['visitors'], 1), 2) }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('facilities.gate-takings.show', $taking['id']) }}" class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('facilities.gate-takings.edit', $taking['id']) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteGateTaking({{ $taking['id'] }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue by Facility Chart -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Revenue by Facility</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $facilityRevenue = collect($gateTakings)->groupBy('facility');
                        @endphp
                        @foreach($facilityRevenue as $facility => $records)
                        <div class="col-md-4 mb-3">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <h6 class="card-title">{{ $facility }}</h6>
                                    <h4 class="text-success">R{{ number_format($records->sum('amount'), 2) }}</h4>
                                    <small class="text-muted">{{ $records->sum('visitors') }} visitors</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteGateTaking(id) {
    if (confirm('Are you sure you want to delete this gate taking record?')) {
        // Delete logic would go here
        alert('Gate taking record deleted successfully');
    }
}
</script>
@endsection
