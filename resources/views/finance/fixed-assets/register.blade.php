@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-chart-bar text-info"></i> Fixed Asset Register
                    </h3>
                    <a href="{{ route('finance.fixed-assets.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Assets
                    </a>
                </div>

                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">{{ $totalAssets }}</h4>
                                            <p class="card-text">Total Assets</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-building fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">${{ number_format($totalValue, 2) }}</h4>
                                            <p class="card-text">Total Value</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-dollar-sign fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">${{ number_format($totalDepreciation, 2) }}</h4>
                                            <p class="card-text">Total Depreciation</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-chart-line-down fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">${{ number_format($netBookValue, 2) }}</h4>
                                            <p class="card-text">Net Book Value</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-balance-scale fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assets by Category -->
                    @if($assetsByCategory->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Assets by Category</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Category</th>
                                            <th>Count</th>
                                            <th>Total Cost</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assetsByCategory as $category)
                                        @php
                                            $percentage = $totalValue > 0 ? ($category->total_cost / $totalValue) * 100 : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $category->category->name ?? 'Uncategorized' }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $category->count }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">
                                                    ${{ number_format($category->total_cost, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar" role="progressbar" 
                                                         style="width: {{ $percentage }}%"
                                                         aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                                        {{ number_format($percentage, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Asset Data Available</h5>
                        <p class="text-muted">Add some assets to view the register.</p>
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
