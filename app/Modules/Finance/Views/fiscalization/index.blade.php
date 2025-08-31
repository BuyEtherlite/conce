@extends('layouts.admin')

@section('title', 'ZIMRA Fiscalization')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-receipt me-2"></i>ZIMRA Fiscalization
                    </h1>
                    <p class="mb-0 text-muted">Manage fiscal devices and ZIMRA-compliant receipts</p>
                </div>
                
                @if(!$fiscalConfig->isFiscalizationEnabled())
                    <div class="alert alert-warning mb-0" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Fiscalization is currently disabled. 
                        <a href="{{ route('finance.fiscalization.configuration') }}" class="alert-link">Configure now</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Devices
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_devices'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-desktop fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Devices
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_devices'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Today's Receipts
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['today_receipts'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Transmission
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_transmission'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('finance.fiscalization.configuration') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-cog me-2"></i>Configuration
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('finance.fiscalization.devices.create') }}" class="btn btn-success btn-block">
                                <i class="fas fa-plus me-2"></i>Add Device
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('finance.fiscalization.receipts.create') }}" class="btn btn-info btn-block">
                                <i class="fas fa-receipt me-2"></i>New Receipt
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('finance.fiscalization.compliance-report') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-chart-bar me-2"></i>Compliance Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Receipts</h6>
                    <a href="{{ route('finance.fiscalization.receipts.index') }}" class="btn btn-sm btn-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($recentReceipts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Receipt #</th>
                                        <th>Device</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentReceipts as $receipt)
                                        <tr>
                                            <td>{{ $receipt->receipt_number }}</td>
                                            <td>{{ $receipt->fiscalDevice->device_name }}</td>
                                            <td>${{ number_format($receipt->total_amount, 2) }}</td>
                                            <td>
                                                @if($receipt->compliance_status === 'transmitted')
                                                    <span class="badge badge-success">Transmitted</span>
                                                @elseif($receipt->compliance_status === 'pending')
                                                    <span class="badge badge-warning">Pending</span>
                                                @else
                                                    <span class="badge badge-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>{{ $receipt->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('finance.fiscalization.receipts.print', $receipt) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-receipt fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No receipts created yet.</p>
                            <a href="{{ route('finance.fiscalization.receipts.create') }}" class="btn btn-primary">
                                Create First Receipt
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Fiscal Devices</h6>
                </div>
                <div class="card-body">
                    @if($devices->count() > 0)
                        @foreach($devices as $device)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    @if($device->isOperational())
                                        <i class="fas fa-circle text-success"></i>
                                    @else
                                        <i class="fas fa-circle text-danger"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="font-weight-bold">{{ $device->device_name }}</div>
                                    <div class="text-muted small">{{ $device->location }}</div>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge badge-{{ $device->isOperational() ? 'success' : 'danger' }}">
                                        {{ ucfirst($device->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center">
                            <i class="fas fa-desktop fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No fiscal devices registered.</p>
                            <a href="{{ route('finance.fiscalization.devices.create') }}" class="btn btn-primary btn-sm">
                                Add Device
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
