@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-building text-primary"></i> Asset Details
                    </h3>
                    <div>
                        <a href="{{ route('finance.fixed-assets.edit', $asset->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('finance.fixed-assets.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Asset Information</h5>
                                    
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Asset Tag:</strong></div>
                                        <div class="col-8">
                                            <span class="badge bg-secondary">{{ $asset->asset_tag }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Asset Name:</strong></div>
                                        <div class="col-8">{{ $asset->asset_name }}</div>
                                    </div>
                                    
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Category:</strong></div>
                                        <div class="col-8">{{ $asset->category->name ?? 'N/A' }}</div>
                                    </div>
                                    
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Location:</strong></div>
                                        <div class="col-8">{{ $asset->location->name ?? 'N/A' }}</div>
                                    </div>
                                    
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Status:</strong></div>
                                        <div class="col-8">
                                            @if($asset->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($asset->status === 'disposed')
                                                <span class="badge bg-danger">Disposed</span>
                                            @else
                                                <span class="badge bg-warning">{{ ucfirst($asset->status) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-2">
                                        <div class="col-4"><strong>Supplier:</strong></div>
                                        <div class="col-8">{{ $asset->supplier_name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title text-success">Financial Information</h5>
                                    
                                    <div class="row mb-2">
                                        <div class="col-5"><strong>Purchase Date:</strong></div>
                                        <div class="col-7">{{ \Carbon\Carbon::parse($asset->purchase_date)->format('d M Y') }}</div>
                                    </div>
                                    
                                    <div class="row mb-2">
                                        <div class="col-5"><strong>Purchase Cost:</strong></div>
                                        <div class="col-7">
                                            <span class="fw-bold text-success">${{ number_format($asset->purchase_cost, 2) }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-2">
                                        <div class="col-5"><strong>Useful Life:</strong></div>
                                        <div class="col-7">{{ $asset->useful_life_years }} years</div>
                                    </div>
                                    
                                    <div class="row mb-2">
                                        <div class="col-5"><strong>Depreciation Method:</strong></div>
                                        <div class="col-7">{{ ucwords(str_replace('_', ' ', $asset->depreciation_method)) }}</div>
                                    </div>
                                    
                                    <div class="row mb-2">
                                        <div class="col-5"><strong>Residual Value:</strong></div>
                                        <div class="col-7">${{ number_format($asset->residual_value, 2) }}</div>
                                    </div>
                                    
                                    <div class="row mb-2">
                                        <div class="col-5"><strong>Total Depreciation:</strong></div>
                                        <div class="col-7">
                                            <span class="fw-bold text-danger">${{ number_format($totalDepreciation, 2) }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-2">
                                        <div class="col-5"><strong>Net Book Value:</strong></div>
                                        <div class="col-7">
                                            <span class="fw-bold text-primary">${{ number_format($netBookValue, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($asset->description)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title text-info">Description</h5>
                                    <p class="mb-0">{{ $asset->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($asset->warranty_expiry)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-shield-alt"></i>
                                <strong>Warranty expires on:</strong> {{ \Carbon\Carbon::parse($asset->warranty_expiry)->format('d M Y') }}
                                @if(\Carbon\Carbon::parse($asset->warranty_expiry)->isPast())
                                    <span class="text-danger">(Expired)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
