@extends('layouts.admin')

@section('title', 'Market Vendors')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-0">Market Vendors</h2>
                    <p class="text-muted">Manage vendor allocations and stall assignments</p>
                </div>
                <div>
                    <a href="{{ route('markets.vendors.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Allocate Stall
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Active Vendors</div>
                            <div class="h4 mb-0 font-weight-bold">{{ $vendors->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Occupied Stalls</div>
                            <div class="h4 mb-0 font-weight-bold">{{ $vendors->where('status', 'active')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-store fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-info text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Revenue</div>
                            <div class="h4 mb-0 font-weight-bold">R{{ number_format($vendors->sum('rental_amount'), 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-gradient-warning text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Business Types</div>
                            <div class="h4 mb-0 font-weight-bold">{{ $vendors->pluck('business_type')->unique()->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendors List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Vendor Allocations</h5>
                </div>
                <div class="card-body">
                    @if($vendors->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Vendor Name</th>
                                        <th>Market</th>
                                        <th>Stall Number</th>
                                        <th>Business Type</th>
                                        <th>Contact</th>
                                        <th>Rental Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendors as $vendor)
                                    <tr>
                                        <td>
                                            <strong>{{ $vendor->vendor_name }}</strong>
                                            <br><small class="text-muted">Since {{ \Carbon\Carbon::parse($vendor->allocated_date)->format('M Y') }}</small>
                                        </td>
                                        <td>{{ $vendor->market_name }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $vendor->stall_number }}</span>
                                        </td>
                                        <td>{{ $vendor->business_type }}</td>
                                        <td>
                                            <small>
                                                <i class="fas fa-phone"></i> {{ $vendor->contact_phone }}<br>
                                                @if($vendor->contact_email)
                                                    <i class="fas fa-envelope"></i> {{ $vendor->contact_email }}
                                                @endif
                                            </small>
                                        </td>
                                        <td>R{{ number_format($vendor->rental_amount, 2) }}</td>
                                        <td>
                                            @if($vendor->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($vendor->status == 'inactive')
                                                <span class="badge bg-warning">Inactive</span>
                                            @else
                                                <span class="badge bg-danger">Terminated</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('markets.vendors.show', $vendor->id) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('markets.vendors.edit', $vendor->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('markets.vendors.destroy', $vendor->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{ $vendors->links() }}
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5>No Vendors Found</h5>
                            <p class="text-muted">Start by allocating stalls to vendors.</p>
                            <a href="{{ route('markets.vendors.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Allocate First Stall
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
