@extends('layouts.app')

@section('title', 'HR Departments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">HR Departments</h6>
                    <a href="{{ route('hr.departments.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Department
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Employees</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Human Resources</td>
                                    <td>Human Resources Department</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>5</td>
                                    <td>
                                        <a href="{{ route('hr.departments.show', 1) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('hr.departments.edit', 1) }}" class="btn btn-warning btn-sm">Edit</a>
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
