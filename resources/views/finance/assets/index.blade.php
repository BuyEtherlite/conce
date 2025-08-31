@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Fixed Assets Management</h4>
                <div class="page-title-right">
                    <a href="{{ route('finance.assets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Asset
                    </a>
                    <button class="btn btn-warning" data-toggle="modal" data-target="#depreciateModal">
                        <i class="fas fa-calculator"></i> Calculate Depreciation
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Assets</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_assets']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Assets</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['active_assets']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Value</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['total_value'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Accumulated Depreciation</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['depreciated_value'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line-down fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assets Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Assets List</h6>
                </div>
                <div class="card-body">
                    @if($assets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Asset Tag</th>
                                        <th>Asset Name</th>
                                        <th>Category</th>
                                        <th>Location</th>
                                        <th>Acquisition Cost</th>
                                        <th>Book Value</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assets as $asset)
                                    <tr>
                                        <td><input type="checkbox" class="asset-checkbox" value="{{ $asset->id }}"></td>
                                        <td>{{ $asset->asset_tag }}</td>
                                        <td>{{ $asset->asset_name }}</td>
                                        <td>{{ $asset->category->name ?? 'N/A' }}</td>
                                        <td>{{ $asset->location->name ?? 'N/A' }}</td>
                                        <td>${{ number_format($asset->acquisition_cost, 2) }}</td>
                                        <td>${{ number_format($asset->book_value, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $asset->status === 'active' ? 'success' : 'warning' }}">
                                                {{ ucfirst(str_replace('_', ' ', $asset->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('finance.assets.show', $asset) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('finance.assets.edit', $asset) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $assets->links() }}
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Assets Found</h5>
                            <p class="text-muted">Start by registering your first asset.</p>
                            <a href="{{ route('finance.assets.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add First Asset
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Depreciation Modal -->
<div class="modal fade" id="depreciateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('finance.assets.depreciate') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Calculate Depreciation</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Depreciation Date</label>
                        <input type="date" name="depreciation_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Selected Assets</label>
                        <div id="selectedAssets" class="border p-2" style="min-height: 100px;">
                            <small class="text-muted">Please select assets from the table above</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning" id="depreciateBtn" disabled>Calculate Depreciation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const assetCheckboxes = document.querySelectorAll('.asset-checkbox');
    const selectedAssetsDiv = document.getElementById('selectedAssets');
    const depreciateBtn = document.getElementById('depreciateBtn');

    selectAllCheckbox.addEventListener('change', function() {
        assetCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedAssets();
    });

    assetCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedAssets);
    });

    function updateSelectedAssets() {
        const selectedIds = [];
        const selectedNames = [];
        
        assetCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedIds.push(checkbox.value);
                const row = checkbox.closest('tr');
                const assetName = row.cells[2].textContent;
                selectedNames.push(assetName);
            }
        });

        // Update hidden inputs
        const existingInputs = selectedAssetsDiv.querySelectorAll('input[name="asset_ids[]"]');
        existingInputs.forEach(input => input.remove());

        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'asset_ids[]';
            input.value = id;
            selectedAssetsDiv.appendChild(input);
        });

        // Update display
        if (selectedNames.length > 0) {
            selectedAssetsDiv.innerHTML = '<div class="mb-2"><strong>Selected Assets:</strong></div>' + 
                selectedNames.map(name => `<span class="badge badge-primary mr-1">${name}</span>`).join('') +
                (selectedAssetsDiv.querySelectorAll('input').length > 0 ? '' : ''); // Append appended inputs if any
            depreciateBtn.disabled = false;
        } else {
            selectedAssetsDiv.innerHTML = '<small class="text-muted">Please select assets from the table above</small>';
            depreciateBtn.disabled = true;
        }
    }
});
</script>
@endsection