@extends('layouts.app')

@section('title', 'Revenue Analysis Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Revenue Analysis Report</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports.analytics.index') }}">Analytics</a></li>
                        <li class="breadcrumb-item active">Revenue Analysis</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-left-primary">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Invoices</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($revenueStats['total_invoices']) }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-left-warning">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Outstanding Amount</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">R{{ number_format($revenueStats['outstanding_amount'], 2) }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card border-left-success">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Collected Amount</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">R{{ number_format($revenueStats['collected_amount'], 2) }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-money-check-alt fa-2x text-gray-300"></i>
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
                            <h5 class="mb-0">Revenue Analysis Summary</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">This report provides a comprehensive analysis of revenue trends and performance metrics.</p>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Collection Rate</h6>
                                    @php
                                        $total = $revenueStats['outstanding_amount'] + $revenueStats['collected_amount'];
                                        $rate = $total > 0 ? ($revenueStats['collected_amount'] / $total) * 100 : 0;
                                    @endphp
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $rate }}%">
                                            {{ number_format($rate, 1) }}%
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6>Outstanding Rate</h6>
                                    @php
                                        $outstandingRate = $total > 0 ? ($revenueStats['outstanding_amount'] / $total) * 100 : 0;
                                    @endphp
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $outstandingRate }}%">
                                            {{ number_format($outstandingRate, 1) }}%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
