@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-calculator text-warning"></i> Asset Depreciation
                    </h3>
                    <div>
                        <form action="{{ route('finance.fixed-assets.calculate-depreciation') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-calculator"></i> Calculate Monthly Depreciation
                            </button>
                        </form>
                        <a href="{{ route('finance.fixed-assets.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Assets
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
                                        <th>Purchase Cost</th>
                                        <th>Accumulated Depreciation</th>
                                        <th>Net Book Value</th>
                                        <th>Monthly Depreciation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assets as $asset)
                                    @php
                                        $totalDepreciation = $asset->depreciations->sum('depreciation_amount');
                                        $netBookValue = $asset->purchase_cost - $totalDepreciation;
                                        $monthlyDepreciation = ($asset->purchase_cost - $asset->residual_value) / ($asset->useful_life_years * 12);
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">{{ $asset->asset_tag }}</span>
                                        </td>
                                        <td>{{ $asset->asset_name }}</td>
                                        <td>{{ $asset->category->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="fw-bold text-success">
                                                ${{ number_format($asset->purchase_cost, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-danger">
                                                ${{ number_format($totalDepreciation, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-primary">
                                                ${{ number_format($netBookValue, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-warning">
                                                ${{ number_format($monthlyDepreciation, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calculator fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Active Assets Found</h5>
                            <p class="text-muted">Add some assets to start calculating depreciation.</p>
                            <a href="{{ route('finance.fixed-assets.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Asset
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection