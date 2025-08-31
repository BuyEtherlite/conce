@extends('layouts.app')

@section('title', 'Electricity Meters')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Electricity Meters Management</h3>
                    <div class="card-tools">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bulkReadingModal">
                            <i class="fas fa-upload"></i> Bulk Reading Upload
                        </button>
                        <a href="{{ route('utilities.electricity.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Options -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Search meter number...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-control">
                                <option>All Zones</option>
                                <option>Zone A</option>
                                <option>Zone B</option>
                                <option>Zone C</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" placeholder="Reading Date">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100">Filter</button>
                        </div>
                    </div>

                    <!-- Meters Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Meter Number</th>
                                    <th>Customer</th>
                                    <th>Location</th>
                                    <th>Last Reading</th>
                                    <th>Current Reading</th>
                                    <th>Consumption (kWh)</th>
                                    <th>Reading Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>MTR-001</td>
                                    <td>John Smith</td>
                                    <td>123 Main St</td>
                                    <td>1250</td>
                                    <td>1700</td>
                                    <td>450</td>
                                    <td>2025-01-08</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#readingModal">
                                            <i class="fas fa-edit"></i> Read
                                        </button>
                                        <button class="btn btn-sm btn-info">
                                            <i class="fas fa-chart-line"></i> History
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>MTR-002</td>
                                    <td>ABC Company</td>
                                    <td>456 Business Ave</td>
                                    <td>5600</td>
                                    <td>6850</td>
                                    <td>1250</td>
                                    <td>2025-01-08</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#readingModal">
                                            <i class="fas fa-edit"></i> Read
                                        </button>
                                        <button class="btn btn-sm btn-info">
                                            <i class="fas fa-chart-line"></i> History
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>MTR-003</td>
                                    <td>Jane Doe</td>
                                    <td>789 Oak St</td>
                                    <td>890</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#readingModal">
                                            <i class="fas fa-edit"></i> Read
                                        </button>
                                        <button class="btn btn-sm btn-info">
                                            <i class="fas fa-chart-line"></i> History
                                        </button>
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

<!-- Reading Modal -->
<div class="modal fade" id="readingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Record Meter Reading</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Meter Number</label>
                        <input type="text" class="form-control" value="MTR-001" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Customer</label>
                        <input type="text" class="form-control" value="John Smith" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Previous Reading</label>
                        <input type="number" class="form-control" value="1250" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Reading *</label>
                        <input type="number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reading Date *</label>
                        <input type="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Reading</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Reading Upload Modal -->
<div class="modal fade" id="bulkReadingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Reading Upload</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Upload CSV File</label>
                        <input type="file" class="form-control" accept=".csv">
                        <small class="form-text text-muted">CSV format: Meter Number, Reading, Date</small>
                    </div>
                    <div class="mb-3">
                        <a href="#" class="btn btn-outline-secondary">Download Template</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
