@extends('layouts.admin')

@section('page-title', 'Burial Records')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>⚱️ Burial Records</h4>
        <a href="{{ route('cemeteries.burials.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add New Burial
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search by name...">
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" placeholder="From Date">
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" placeholder="To Date">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary w-100">Search</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Burials Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Deceased Name</th>
                            <th>Date of Death</th>
                            <th>Burial Date</th>
                            <th>Plot</th>
                            <th>Next of Kin</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No burial records found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
