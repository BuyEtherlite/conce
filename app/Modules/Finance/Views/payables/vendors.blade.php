@extends('layouts.app')

@section('page-title', 'Vendors')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>🏢 Vendors</h4>
        <a href="{{ route('finance.accounts-payable.vendors.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>New Vendor
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Vendor List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Vendor Name</th>
                            <th>Contact Person</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Outstanding</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>ABC Suppliers Ltd</td>
                            <td>John Doe</td>
                            <td>john@abcsuppliers.com</td>
                            <td>+1234567890</td>
                            <td>$5,250.00</td>
                            <td><span class="badge bg-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View</button>
                                <button class="btn btn-sm btn-outline-secondary">Edit</button>
                            </td>
                        </tr>
                        <tr>
                            <td>XYZ Services Inc</td>
                            <td>Jane Smith</td>
                            <td>jane@xyzservices.com</td>
                            <td>+1234567891</td>
                            <td>$0.00</td>
                            <td><span class="badge bg-success">Active</span></td>
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
