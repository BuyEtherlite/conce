@extends('layouts.app')

@section('title', 'Environmental Health')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">Environmental Health</h1>
                <p class="page-subtitle">Environmental monitoring and public health protection</p>
            </div>
        </div>
    </div>

    <!-- Environmental Metrics -->
    <div class="row">
        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h2 text-success">{{ $environmentalData['air_quality_index'] }}</div>
                    <p class="text-muted mb-0">Air Quality Index</p>
                    <small class="text-success">Good</small>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h2 text-primary">{{ $environmentalData['water_quality_tests'] }}</div>
                    <p class="text-muted mb-0">Water Tests</p>
                    <small class="text-muted">This Month</small>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h2 text-success">{{ $environmentalData['waste_management_compliance'] }}%</div>
                    <p class="text-muted mb-0">Waste Compliance</p>
                    <small class="text-success">Excellent</small>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h2 text-warning">{{ $environmentalData['noise_complaints'] }}</div>
                    <p class="text-muted mb-0">Noise Complaints</p>
                    <small class="text-muted">This Month</small>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h2 text-danger">{{ $environmentalData['environmental_violations'] }}</div>
                    <p class="text-muted mb-0">Violations</p>
                    <small class="text-muted">This Month</small>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h2 text-info">{{ $environmentalData['recycling_rate'] }}%</div>
                    <p class="text-muted mb-0">Recycling Rate</p>
                    <small class="text-success">↑ 5% from last year</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Environmental Programs -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Active Monitoring Programs</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Air Quality Monitoring</h6>
                                <small class="text-muted">24/7 monitoring at 5 stations</small>
                            </div>
                            <span class="badge bg-success">Active</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Water Quality Testing</h6>
                                <small class="text-muted">Daily testing at treatment plants</small>
                            </div>
                            <span class="badge bg-success">Active</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Noise Level Monitoring</h6>
                                <small class="text-muted">Strategic locations monitored</small>
                            </div>
                            <span class="badge bg-warning">Limited</span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Soil Contamination Assessment</h6>
                                <small class="text-muted">Industrial area monitoring</small>
                            </div>
                            <span class="badge bg-info">Quarterly</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Environmental Actions</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Water Treatment Upgrade</h6>
                                <p class="timeline-text">New filtration system installed at Main Treatment Plant</p>
                                <small class="text-muted">2 days ago</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Air Quality Alert Issued</h6>
                                <p class="timeline-text">Temporary advisory for sensitive groups</p>
                                <small class="text-muted">1 week ago</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Recycling Campaign Launch</h6>
                                <p class="timeline-text">Community-wide recycling awareness program</p>
                                <small class="text-muted">2 weeks ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Environmental Reports -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Environmental Reports & Assessments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Report Type</th>
                                    <th>Location/Area</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Risk Level</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Air Quality Assessment</td>
                                    <td>Downtown Area</td>
                                    <td>2025-01-07</td>
                                    <td><span class="badge bg-success">Complete</span></td>
                                    <td><span class="badge bg-success">Low</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View Report</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Water Contamination Study</td>
                                    <td>Industrial Zone</td>
                                    <td>2025-01-05</td>
                                    <td><span class="badge bg-warning">Under Review</span></td>
                                    <td><span class="badge bg-warning">Medium</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View Details</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Noise Impact Assessment</td>
                                    <td>Residential District</td>
                                    <td>2025-01-03</td>
                                    <td><span class="badge bg-primary">In Progress</span></td>
                                    <td><span class="badge bg-success">Low</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary">Monitor</button>
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

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline:before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    top: 5px;
}

.timeline-content {
    margin-left: 10px;
}

.timeline-title {
    margin-bottom: 5px;
}

.timeline-text {
    margin-bottom: 5px;
    font-size: 0.9rem;
}
</style>
@endsection
