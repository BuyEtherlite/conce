@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-building text-primary"></i> Fixed Assets Register
                    </h3>
                    <div>
                        <a href="{{ route('finance.fixed-assets.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Asset
                        </a>
                        <a href="{{ route('finance.fixed-assets.depreciation') }}" class="btn btn-warning">
                            <i class="fas fa-calculator"></i> Depreciation
                        </a>
                        <a href="{{ route('finance.fixed-assets.register') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> Asset Register
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($assets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Asset Tag</th>
                                        <th>Asset Name</th>
                                        <th>Category</th>
                                        <th>Location</th>
                                        <th>Purchase Date</th>
                                        <th>Purchase Cost</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assets as $asset)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">{{ $asset->asset_tag }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $asset->asset_name }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $asset->category->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $asset->location->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($asset->purchase_date)->format('d M Y') }}</td>
                                        <td>
                                            <span class="fw-bold text-success">
                                                ${{ number_format($asset->purchase_cost, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($asset->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($asset->status === 'disposed')
                                                <span class="badge bg-danger">Disposed</span>
                                            @else
                                                <span class="badge bg-warning">{{ ucfirst($asset->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('finance.fixed-assets.show', $asset->id) }}" 
                                                   class="btn btn-outline-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('finance.fixed-assets.edit', $asset->id) }}" 
                                                   class="btn btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $assets->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-building fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Fixed Assets Found</h5>
                            <p class="text-muted">Start by adding your first fixed asset to the register.</p>
                            <a href="{{ route('finance.fixed-assets.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add First Asset
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
