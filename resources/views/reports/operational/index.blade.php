@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cogs fa-fw"></i> Operational Metrics
        </h1>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Reports
        </a>
    </div>

    <!-- Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Date Range Filter</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.operational') }}">
                <div class="row">
                    <div class="col-md-3">
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" placeholder="Start Date">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" placeholder="End Date">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('reports.operational') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Housing Statistics -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Housing Department</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Total Applications</span>
                            <span class="font-weight-bold">{{ $housingStats['total_applications'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Approved</span>
                            <span class="font-weight-bold text-success">{{ $housingStats['approved_applications'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Pending</span>
                            <span class="font-weight-bold text-warning">{{ $housingStats['pending_applications'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Rejected</span>
                            <span class="font-weight-bold text-danger">{{ $housingStats['rejected_applications'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- License Statistics -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Licensing Department</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Total Licenses</span>
                            <span class="font-weight-bold">{{ $licenseStats['total_licenses'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Approved</span>
                            <span class="font-weight-bold text-success">{{ $licenseStats['approved_licenses'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Pending</span>
                            <span class="font-weight-bold text-warning">{{ $licenseStats['pending_licenses'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Expired</span>
                            <span class="font-weight-bold text-danger">{{ $licenseStats['expired_licenses'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Tax Statistics -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Property Tax Department</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Total Assessments</span>
                            <span class="font-weight-bold">{{ $propertyTaxStats['total_assessments'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Assessed Value</span>
                            <span class="font-weight-bold text-success">${{ number_format($propertyTaxStats['total_assessed_value'], 2) }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Tax Due</span>
                            <span class="font-weight-bold text-info">${{ number_format($propertyTaxStats['total_tax_due'], 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
