@extends('layouts.app')

@section('page-title', 'Vendor Payments')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💰 Vendor Payments</h4>
        <a href="{{ route('finance.accounts-payable.payments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Record Payment
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Payment History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Payment Date</th>
                            <th>Bill Number</th>
                            <th>Vendor</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2024-01-20</td>
                            <td>BILL-2024-002</td>
                            <td>XYZ Services Inc</td>
                            <td>$1,200.00</td>
                            <td>Bank Transfer</td>
                            <td>TXN-20240120-001</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">View</button>
                                <button class="btn btn-sm btn-outline-info">Receipt</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2024-01-18</td>
                            <td>BILL-2024-001</td>
                            <td>ABC Suppliers Ltd</td>
                            <td>$500.00</td>
                            <td>Check</td>
                            <td>CHK-001234</td>
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
