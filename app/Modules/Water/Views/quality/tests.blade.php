@extends('layouts.admin')

@section('page-title', 'Water Quality Tests')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>🧪 Water Quality Tests</h4>
        <a href="{{ route('water.quality.tests.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>New Test
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">All Quality Tests</h5>
                </div>
                <div class="col-auto">
                    <div class="row g-2">
                        <div class="col">
                            <select class="form-select">
                                <option value="">All Locations</option>
                                <option value="main_plant">Main Plant</option>
                                <option value="dist_a">Distribution Point A</option>
                                <option value="dist_b">Distribution Point B</option>
                                <option value="reservoir_1">Reservoir Tank 1</option>
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-select">
                                <option value="">All Test Types</option>
                                <option value="chlorine">Chlorine Level</option>
                                <option value="ph">pH Level</option>
                                <option value="bacteria">Bacteria Count</option>
                                <option value="turbidity">Turbidity</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Test ID</th>
                            <th>Date & Time</th>
                            <th>Location</th>
                            <th>Test Type</th>
                            <th>Result</th>
                            <th>Standard Range</th>
                            <th>Status</th>
                            <th>Tested By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>QT-2024-001</td>
                            <td>2024-01-25 09:30</td>
                            <td>Main Plant</td>
                            <td>Chlorine Level</td>
                            <td>1.2 ppm</td>
                            <td>0.5-2.0 ppm</td>
                            <td><span class="badge bg-success">Passed</span></td>
                            <td>Lab Tech A</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View</button>
                                <button class="btn btn-sm btn-outline-secondary">Report</button>
                            </td>
                        </tr>
                        <tr>
                            <td>QT-2024-002</td>
                            <td>2024-01-24 14:15</td>
                            <td>Distribution Point A</td>
                            <td>pH Level</td>
                            <td>7.2</td>
                            <td>6.5-8.5</td>
                            <td><span class="badge bg-success">Passed</span></td>
                            <td>Lab Tech B</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View</button>
                                <button class="btn btn-sm btn-outline-secondary">Report</button>
                            </td>
                        </tr>
                        <tr>
                            <td>QT-2024-003</td>
                            <td>2024-01-23 11:00</td>
                            <td>Reservoir Tank 1</td>
                            <td>Bacteria Count</td>
                            <td>0 CFU/100ml</td>
                            <td>0 CFU/100ml</td>
                            <td><span class="badge bg-success">Passed</span></td>
                            <td>Lab Tech A</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View</button>
                                <button class="btn btn-sm btn-outline-secondary">Report</button>
                            </td>
                        </tr>
                        <tr>
                            <td>QT-2024-004</td>
                            <td>2024-01-22 16:45</td>
                            <td>Distribution Point B</td>
                            <td>Turbidity</td>
                            <td>1.2 NTU</td>
                            <td>< 1.0 NTU</td>
                            <td><span class="badge bg-warning">Failed</span></td>
                            <td>Lab Tech C</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View</button>
                                <button class="btn btn-sm btn-outline-secondary">Report</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
