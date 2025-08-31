@extends('layouts.app')

@section('title', 'Quality Assurance')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Health Quality Assurance</h1>
                <p class="page-subtitle">Quality metrics and improvement initiatives</p>
            </div>
        </div>
    </div>

    <!-- Quality Metrics -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="progress-circle" data-percentage="{{ $qualityMetrics['patient_satisfaction'] }}">
                        <div class="progress-circle-inner">
                            <div class="progress-circle-text">
                                <span class="h3">{{ number_format($qualityMetrics['patient_satisfaction'], 1) }}%</span>
                                <div class="small text-muted">Patient Satisfaction</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="progress-circle" data-percentage="{{ $qualityMetrics['infection_control_rate'] }}">
                        <div class="progress-circle-inner">
                            <div class="progress-circle-text">
                                <span class="h3">{{ number_format($qualityMetrics['infection_control_rate'], 1) }}%</span>
                                <div class="small text-muted">Infection Control</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-success">{{ $qualityMetrics['medication_errors'] }}%</h3>
                    <p class="text-muted mb-0">Medication Errors</p>
                    <small class="text-success">↓ 15% from last quarter</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Metrics -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Performance Indicators</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Readmission Rate</span>
                            <strong>{{ $qualityMetrics['readmission_rate'] }}%</strong>
                        </div>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-warning" style="width: {{ $qualityMetrics['readmission_rate'] * 5 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Mortality Rate</span>
                            <strong>{{ $qualityMetrics['mortality_rate'] }}%</strong>
                        </div>
                        <div class="progress mt-1">
                            <div class="progress-bar bg-danger" style="width: {{ $qualityMetrics['mortality_rate'] * 20 }}%"></div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex justify-content-between">
                            <span>Accreditation Status</span>
                            <span class="badge bg-success">{{ $qualityMetrics['accreditation_status'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quality Improvement Initiatives</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle text-success me-3"></i>
                                <div>
                                    <h6 class="mb-1">Hand Hygiene Campaign</h6>
                                    <small class="text-muted">Completed - 95% compliance achieved</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-arrow-right-circle text-primary me-3"></i>
                                <div>
                                    <h6 class="mb-1">Electronic Health Records</h6>
                                    <small class="text-muted">In Progress - 60% implementation</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock text-warning me-3"></i>
                                <div>
                                    <h6 class="mb-1">Staff Training Program</h6>
                                    <small class="text-muted">Scheduled - Starting next month</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quality Reports -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quality Reports & Audits</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Report Type</th>
                                    <th>Period</th>
                                    <th>Score/Rating</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Patient Safety Audit</td>
                                    <td>Q4 2024</td>
                                    <td><span class="badge bg-success">Excellent</span></td>
                                    <td><span class="badge bg-success">Complete</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View Report</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Infection Control Assessment</td>
                                    <td>December 2024</td>
                                    <td><span class="badge bg-success">95/100</span></td>
                                    <td><span class="badge bg-success">Complete</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View Report</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Medication Management Review</td>
                                    <td>January 2025</td>
                                    <td><span class="badge bg-warning">In Progress</span></td>
                                    <td><span class="badge bg-warning">Ongoing</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary">View Details</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
