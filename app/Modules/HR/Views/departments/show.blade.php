@extends('layouts.app')

@section('title', 'Department Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Department Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Human Resources</h5>
                            <p class="text-muted">Human Resources Department</p>
                            <p><strong>Status:</strong> <span class="badge badge-success">Active</span></p>
                        </div>
                        <div class="col-md-6">
                            <div class="text-right">
                                <a href="{{ route('hr.departments.edit', $id) }}" class="btn btn-warning">Edit</a>
                                <a href="{{ route('hr.departments.index') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
