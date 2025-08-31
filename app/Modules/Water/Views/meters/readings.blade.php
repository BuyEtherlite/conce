@extends('layouts.admin')

@section('page-title', 'Water Meter Readings')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📈 Water Meter Readings</h4>
        <a href="{{ route('water.meters.readings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Record Reading
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Recent Readings</h5>
                </div>
                <div class="col-auto">
                    <select class="form-select">
                        <option value="">All Meters</option>
                        <option value="M-001234">M-001234 - John Smith</option>
                        <option value="M-001235">M-001235 - ABC Corporation</option>
                        <option value="M-001236">M-001236 - XYZ Restaurant</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Reading ID</th>
                            <th>Meter #</th>
                            <th>Customer</th>
                            <th>Previous Reading</th>
                            <th>Current Reading</th>
                            <th>Usage</th>
                            <th>Reading Date</th>
                            <th>Recorded By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>R-2024-001</td>
                            <td>M-001234</td>
                            <td>John Smith</td>
                            <td>1,150</td>
                            <td>1,250</td>
                            <td>100 gal</td>
                            <td>2024-01-25</td>
                            <td>Admin User</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View</button>
                                <button class="btn btn-sm btn-outline-secondary">Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>R-2024-002</td>
                            <td>M-001235</td>
                            <td>ABC Corporation</td>
                            <td>5,200</td>
                            <td>5,450</td>
                            <td>250 gal</td>
                            <td>2024-01-24</td>
                            <td>Admin User</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View</button>
                                <button class="btn btn-sm btn-outline-secondary">Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>R-2024-003</td>
                            <td>M-001236</td>
                            <td>XYZ Restaurant</td>
                            <td>3,050</td>
                            <td>3,200</td>
                            <td>150 gal</td>
                            <td>2024-01-20</td>
                            <td>Admin User</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View</button>
                                <button class="btn btn-sm btn-outline-secondary">Edit</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
