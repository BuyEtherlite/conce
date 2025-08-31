@extends('layouts.admin')

@section('title', 'Market Stalls')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-boxes me-2"></i>
                        Market Stalls Management
                    </h5>
                    <a href="{{ route('markets.stalls.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New Stall
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <select class="form-select" id="marketFilter">
                                <option value="">All Markets</option>
                                @foreach($markets ?? [] as $market)
                                    <option value="{{ $market->id }}">{{ $market->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="available">Available</option>
                                <option value="occupied">Occupied</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Search stalls..." id="searchInput">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                <i class="fas fa-filter me-1"></i> Clear Filters
                            </button>
                        </div>
                    </div>

                    <!-- Stalls Grid/List -->
                    <div class="row">
                        @forelse($stalls ?? [] as $stall)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card stall-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Stall {{ $stall->number ?? 'N/A' }}</h6>
                                    <span class="badge bg-{{ 
                                        $stall->status === 'available' ? 'success' : 
                                        ($stall->status === 'occupied' ? 'primary' : 'warning') 
                                    }}">
                                        {{ ucfirst($stall->status ?? 'available') }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-store me-1"></i>
                                        {{ $stall->market->name ?? 'Unknown Market' }}
                                    </p>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-layer-group me-1"></i>
                                        Section: {{ $stall->section ?? 'General' }}
                                    </p>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-ruler-combined me-1"></i>
                                        Size: {{ $stall->size ?? 'Standard' }}
                                    </p>
                                    <p class="text-muted mb-3">
                                        <i class="fas fa-dollar-sign me-1"></i>
                                        Monthly Rate: ${{ number_format($stall->monthly_rate ?? 0, 2) }}
                                    </p>
                                    
                                    @if($stall->status === 'occupied' && isset($stall->vendor))
                                    <div class="border-top pt-2">
                                        <small class="text-muted">Current Vendor:</small>
                                        <p class="mb-0"><strong>{{ $stall->vendor->name ?? 'Unknown' }}</strong></p>
                                    </div>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <div class="btn-group btn-group-sm w-100">
                                        <a href="{{ route('markets.stalls.show', $stall->id) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('markets.stalls.edit', $stall->id) }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        @if($stall->status === 'available')
                                        <button class="btn btn-outline-success" onclick="allocateStall({{ $stall->id }})">
                                            <i class="fas fa-user-plus"></i> Allocate
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-boxes fa-4x text-muted mb-4"></i>
                                <h5 class="text-muted">No Stalls Found</h5>
                                <p class="text-muted">Create your first market stall to get started.</p>
                                <a href="{{ route('markets.stalls.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Create First Stall
                                </a>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function clearFilters() {
    document.getElementById('marketFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('searchInput').value = '';
}

function allocateStall(stallId) {
    // Implementation for stall allocation
    alert('Stall allocation feature coming soon!');
}
</script>
@endpush
@endsection
