@extends('layouts.admin')

@section('title', 'Property Tax Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-file-invoice-dollar me-2"></i>
                        Property Tax Management
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Quick Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Total Properties</h6>
                                            <h4>{{ $stats['total_properties'] ?? 0 }}</h4>
                                        </div>
                                        <i class="fas fa-building fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Total Assessments</h6>
                                            <h4>{{ $stats['total_assessments'] ?? 0 }}</h4>
                                        </div>
                                        <i class="fas fa-calculator fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Annual Revenue</h6>
                                            <h4>${{ number_format($stats['annual_revenue'] ?? 0, 2) }}</h4>
                                        </div>
                                        <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Outstanding Bills</h6>
                                            <h4>{{ $stats['outstanding_bills'] ?? 0 }}</h4>
                                        </div>
                                        <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Property Tax Services</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <a href="{{ route('property-tax.assessments.index') }}" class="btn btn-outline-primary w-100 py-3">
                                                <i class="fas fa-calculator fa-2x mb-2 d-block"></i>
                                                <div>Property Assessments</div>
                                                <small class="text-muted">Manage property valuations</small>
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <a href="{{ route('property-tax.bills.index') }}" class="btn btn-outline-success w-100 py-3">
                                                <i class="fas fa-file-invoice fa-2x mb-2 d-block"></i>
                                                <div>Tax Bills</div>
                                                <small class="text-muted">Generate and manage bills</small>
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <a href="{{ route('property-tax.payments.index') }}" class="btn btn-outline-info w-100 py-3">
                                                <i class="fas fa-credit-card fa-2x mb-2 d-block"></i>
                                                <div>Payments</div>
                                                <small class="text-muted">Process tax payments</small>
                                            </a>
                                        </div>
                                        <div class="col-md-6 col-lg-3 mb-3">
                                            <a href="{{ route('property-tax.reports.index') }}" class="btn btn-outline-warning w-100 py-3">
                                                <i class="fas fa-chart-bar fa-2x mb-2 d-block"></i>
                                                <div>Reports</div>
                                                <small class="text-muted">Tax reports & analytics</small>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Recent Assessments</h6>
                                    <a href="{{ route('property-tax.assessments.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-1"></i> New Assessment
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Property</th>
                                                    <th>Owner</th>
                                                    <th>Assessed Value</th>
                                                    <th>Annual Tax</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($recentAssessments ?? [] as $assessment)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $assessment->property->address ?? 'N/A' }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $assessment->property->type ?? 'Residential' }}</small>
                                                    </td>
                                                    <td>{{ $assessment->property->owner_name ?? 'N/A' }}</td>
                                                    <td>${{ number_format($assessment->assessed_value ?? 0, 2) }}</td>
                                                    <td>${{ number_format($assessment->annual_tax ?? 0, 2) }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $assessment->status === 'approved' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($assessment->status ?? 'pending') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="{{ route('property-tax.assessments.show', $assessment->id) }}" class="btn btn-outline-primary" title="View">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('property-tax.assessments.edit', $assessment->id) }}" class="btn btn-outline-secondary" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-3">
                                                        <i class="fas fa-calculator fa-2x text-muted mb-2"></i>
                                                        <p class="text-muted mb-0">No assessments found.</p>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Quick Links</h6>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <a href="{{ route('property-tax.assessments.create') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-plus text-primary me-2"></i>
                                            Create New Assessment
                                        </a>
                                        <a href="{{ route('property-tax.valuations.index') }}" class="list-group-item list-group-item-action">
                                            <i class="fas fa-home text-success me-2"></i>
                                            Property Valuations
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <i class="fas fa-file-export text-info me-2"></i>
                                            Export Tax Roll
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <i class="fas fa-cog text-warning me-2"></i>
                                            Tax Rate Settings
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Collection Summary</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Current Year</span>
                                            <strong>${{ number_format($stats['current_year_collected'] ?? 0, 2) }}</strong>
                                        </div>
                                        <div class="progress mt-1">
                                            <div class="progress-bar bg-success" style="width: {{ $stats['collection_rate'] ?? 0 }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $stats['collection_rate'] ?? 0 }}% collection rate</small>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Outstanding</span>
                                            <strong class="text-danger">${{ number_format($stats['outstanding_amount'] ?? 0, 2) }}</strong>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex justify-content-between">
                                            <span>Total Assessed</span>
                                            <strong>${{ number_format($stats['total_assessed_value'] ?? 0, 2) }}</strong>
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
