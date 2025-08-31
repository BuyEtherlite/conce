@extends('layouts.app')

@section('page-title', 'Grave Register')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📖 Grave Register</h4>
        <div>
            <button class="btn btn-outline-primary me-2">
                <i class="fas fa-print me-1"></i>Print Register
            </button>
            <button class="btn btn-success">
                <i class="fas fa-file-excel me-1"></i>Export Excel
            </button>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Search by name or plot...">
                </div>
                <div class="col-md-2">
                    <select class="form-select">
                        <option value="">All Sections</option>
                        <option value="A">Section A</option>
                        <option value="B">Section B</option>
                        <option value="C">Section C</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select">
                        <option value="">All Status</option>
                        <option value="occupied">Occupied</option>
                        <option value="available">Available</option>
                        <option value="reserved">Reserved</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" placeholder="From Date">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" placeholder="To Date">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-primary w-100">Search</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Grave Register Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Plot No.</th>
                            <th>Section</th>
                            <th>Deceased Name</th>
                            <th>Date of Death</th>
                            <th>Burial Date</th>
                            <th>Age</th>
                            <th>Next of Kin</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="text-center text-muted">No grave records found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
