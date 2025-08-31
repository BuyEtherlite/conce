@extends('layouts.admin')

@section('page-title', 'Water Billing Invoices')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📄 Water Billing Invoices</h4>
        <a href="{{ route('water.billing.invoices.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Create Invoice
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">All Invoices</h5>
                </div>
                <div class="col-auto">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search invoices...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer</th>
                            <th>Meter #</th>
                            <th>Usage (Gallons)</th>
                            <th>Amount</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>INV-2024-001</td>
                            <td>John Smith</td>
                            <td>M-001234</td>
                            <td>1,250</td>
                            <td>$45.50</td>
                            <td>2024-01-15</td>
                            <td>2024-02-15</td>
                            <td><span class="badge bg-success">Paid</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View</button>
                                <button class="btn btn-sm btn-outline-secondary">Print</button>
                            </td>
                        </tr>
                        <tr>
                            <td>INV-2024-002</td>
                            <td>ABC Corporation</td>
                            <td>M-001235</td>
                            <td>5,450</td>
                            <td>$234.75</td>
                            <td>2024-01-20</td>
                            <td>2024-02-20</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View</button>
                                <button class="btn btn-sm btn-outline-secondary">Print</button>
                            </td>
                        </tr>
                        <tr>
                            <td>INV-2024-003</td>
                            <td>XYZ Restaurant</td>
                            <td>M-001236</td>
                            <td>3,200</td>
                            <td>$145.80</td>
                            <td>2024-01-25</td>
                            <td>2024-02-25</td>
                            <td><span class="badge bg-danger">Overdue</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View</button>
                                <button class="btn btn-sm btn-outline-secondary">Print</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
