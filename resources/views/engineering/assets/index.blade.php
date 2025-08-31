@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Asset Register</h4>
                <div class="page-title-right">
                    <a href="{{ route('engineering.assets.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Register Asset
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($assets->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Asset ID</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Purchase Date</th>
                                    <th>Value</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assets as $asset)
                                <tr>
                                    <td><strong>{{ $asset->asset_number ?? 'AST-001' }}</strong></td>
                                    <td>{{ $asset->name ?? 'Sample Asset' }}</td>
                                    <td>{{ $asset->category ?? 'Infrastructure' }}</td>
                                    <td>{{ $asset->location ?? 'Main Building' }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ ucfirst($asset->status ?? 'operational') }}</span>
                                    </td>
                                    <td>{{ $asset->purchase_date ? date('M d, Y', strtotime($asset->purchase_date)) : 'Not recorded' }}</td>
                                    <td>${{ number_format($asset->purchase_value ?? 0, 2) }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('engineering.assets.show', $asset->id ?? 1) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            <a href="{{ route('engineering.assets.edit', $asset->id ?? 1) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-building display-4 text-muted"></i>
                        <h5 class="mt-3">No Assets Found</h5>
                        <p class="text-muted">Register your first asset to get started.</p>
                        <a href="{{ route('engineering.assets.create') }}" class="btn btn-primary">Register Asset</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
