@extends('layouts.admin')

@section('title', 'FDMS Receipts')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">FDMS Receipts</h3>
                    <a href="{{ route('finance.fdms.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Receipt
                    </a>
                </div>
                
                <div class="card-body">
                    @if($receipts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Receipt Number</th>
                                        <th>Fiscal Receipt</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Currency</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>FDMS Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($receipts as $receipt)
                                        <tr>
                                            <td>{{ $receipt->receipt_number }}</td>
                                            <td>{{ $receipt->fiscal_receipt_number ?? 'Pending' }}</td>
                                            <td>
                                                @if($receipt->customer)
                                                    {{ $receipt->customer->name }}
                                                @else
                                                    Walk-in Customer
                                                @endif
                                            </td>
                                            <td>${{ number_format($receipt->total_amount, 2) }}</td>
                                            <td>{{ $receipt->currency_code }}</td>
                                            <td>{{ $receipt->receipt_date->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $receipt->status === 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($receipt->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($receipt->fdms_transmitted)
                                                    <span class="badge badge-success">Transmitted</span>
                                                @else
                                                    <span class="badge badge-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('finance.fdms.show', $receipt->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('finance.fdms.print', $receipt->id) }}" class="btn btn-sm btn-secondary" target="_blank">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $receipts->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">No FDMS receipts found.</p>
                            <a href="{{ route('finance.fdms.create') }}" class="btn btn-primary">Create First Receipt</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
