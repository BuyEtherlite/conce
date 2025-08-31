@extends('layouts.app')

@section('page-title', 'Bills')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>📄 Bills</h4>
        <a href="{{ route('finance.accounts-payable.bills.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>New Bill
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Bills List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Bill Number</th>
                            <th>Vendor</th>
                            <th>Bill Date</th>
                            <th>Due Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>BILL-2024-001</td>
                            <td>ABC Suppliers Ltd</td>
                            <td>2024-01-15</td>
                            <td>2024-02-14</td>
                            <td>$2,500.00</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View</button>
                                <button class="btn btn-sm btn-outline-success">Pay</button>
                            </td>
                        </tr>
                        <tr>
                            <td>BILL-2024-002</td>
                            <td>XYZ Services Inc</td>
                            <td>2024-01-10</td>
                            <td>2024-02-09</td>
                            <td>$1,200.00</td>
                            <td><span class="badge bg-success">Paid</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View</button>
                                <button class="btn btn-sm btn-outline-info">Receipt</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
