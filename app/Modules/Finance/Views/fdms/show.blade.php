@extends('layouts.admin')

@section('title', 'FDMS Receipt Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">FDMS Receipt: {{ $receipt->receipt_number }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('finance.fdms.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Receipts
                        </a>
                        <a href="{{ route('finance.fdms.print', $receipt->id) }}" class="btn btn-primary" target="_blank">
                            <i class="fas fa-print"></i> Print
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Receipt Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Receipt Number:</strong></td>
                                    <td>{{ $receipt->receipt_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Fiscal Receipt:</strong></td>
                                    <td>{{ $receipt->fiscal_receipt_number ?? 'Not assigned' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Receipt Date:</strong></td>
                                    <td>{{ $receipt->receipt_date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Receipt Time:</strong></td>
                                    <td>{{ $receipt->receipt_time->format('H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td>{{ ucfirst($receipt->payment_method) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $receipt->status === 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($receipt->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Customer & Terminal</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Customer:</strong></td>
                                    <td>
                                        @if($receipt->customer)
                                            {{ $receipt->customer->name }}
                                        @else
                                            Walk-in Customer
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>POS Terminal:</strong></td>
                                    <td>
                                        @if($receipt->posTerminal)
                                            {{ $receipt->posTerminal->terminal_name }}
                                        @else
                                            Not specified
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Cashier:</strong></td>
                                    <td>{{ $receipt->cashier->name ?? 'Unknown' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Financial Details</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Subtotal:</strong></td>
                                    <td>${{ number_format($receipt->subtotal_amount, 2) }} {{ $receipt->currency_code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tax Amount:</strong></td>
                                    <td>${{ number_format($receipt->tax_amount, 2) }} {{ $receipt->currency_code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td><strong>${{ number_format($receipt->total_amount, 2) }} {{ $receipt->currency_code }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Amount Tendered:</strong></td>
                                    <td>${{ number_format($receipt->amount_tendered, 2) }} {{ $receipt->currency_code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Change:</strong></td>
                                    <td>${{ number_format($receipt->change_amount, 2) }} {{ $receipt->currency_code }}</td>
                                </tr>
                                @if($receipt->exchange_rate != 1)
                                <tr>
                                    <td><strong>Exchange Rate:</strong></td>
                                    <td>{{ $receipt->exchange_rate }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>FDMS Compliance</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>FDMS Transmitted:</strong></td>
                                    <td>
                                        @if($receipt->fdms_transmitted)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-warning">No</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($receipt->fdms_transmission_date)
                                <tr>
                                    <td><strong>Transmission Date:</strong></td>
                                    <td>{{ $receipt->fdms_transmission_date->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                @endif
                                @if($receipt->fiscal_device_id)
                                <tr>
                                    <td><strong>Fiscal Device ID:</strong></td>
                                    <td>{{ $receipt->fiscal_device_id }}</td>
                                </tr>
                                @endif
                                @if($receipt->verification_code)
                                <tr>
                                    <td><strong>Verification Code:</strong></td>
                                    <td><code>{{ $receipt->verification_code }}</code></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    
                    @if($receipt->items)
                    <hr>
                    <h5>Receipt Items</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(json_decode($receipt->items, true) as $item)
                                <tr>
                                    <td>{{ $item['description'] ?? 'Service' }}</td>
                                    <td>{{ $item['quantity'] ?? 1 }}</td>
                                    <td>${{ number_format($item['unit_price'] ?? 0, 2) }}</td>
                                    <td>${{ number_format($item['total'] ?? 0, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
