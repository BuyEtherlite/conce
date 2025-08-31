@extends('layouts.admin')

@section('page-title', 'Municipal Services')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Municipal Services</h3>
                    <div class="card-tools">
                        <a href="{{ route('billing.services.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Service
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Service Name</th>
                                    <th>Category</th>
                                    <th>Rate</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Services will be displayed here -->
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <p class="text-muted">No services configured yet.</p>
                                        <a href="{{ route('billing.services.create') }}" class="btn btn-primary">
                                            Add First Service
                                        </a>
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
