@extends('layouts.admin')

@section('title', 'Supplier Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>🏢 Supplier Details</h1>
                <div>
                    <a href="{{ route('finance.procurement.suppliers.edit', $supplier->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>Edit Supplier
                    </a>
                    <a href="{{ route('finance.procurement.suppliers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Suppliers
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Supplier Information -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>Supplier Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Vendor Number:</strong></td>
                                            <td>{{ $supplier->vendor_number }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Vendor Name:</strong></td>
                                            <td>{{ $supplier->vendor_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Contact Person:</strong></td>
                                            <td>{{ $supplier->contact_person ?: 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $supplier->email ?: 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Phone:</strong></td>
                                            <td>{{ $supplier->phone ?: 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Tax Number:</strong></td>
                                            <td>{{ $supplier->tax_number ?: 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Bank Name:</strong></td>
                                            <td>{{ $supplier->bank_name ?: 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Account Number:</strong></td>
                                            <td>{{ $supplier->account_number ?: 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Credit Limit:</strong></td>
                                            <td>${{ number_format($supplier->credit_limit ?? 0, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Terms:</strong></td>
                                            <td>{{ $supplier->payment_terms ? $supplier->payment_terms . ' days' : 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            @if($supplier->address)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <strong>Address:</strong>
                                    <p class="mt-1">{{ $supplier->address }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status and Stats -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-bar me-2"></i>Status & Statistics
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Status:</strong>
                                <span class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-danger' }} ms-2">
                                    {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Total Purchase Orders:</strong>
                                <span class="text-primary">{{ $supplier->purchaseOrders->count() }}</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Created:</strong>
                                <br>{{ $supplier->created_at->format('M d, Y h:i A') }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Last Updated:</strong>
                                <br>{{ $supplier->updated_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Purchase Orders -->
            @if($supplier->purchaseOrders && $supplier->purchaseOrders->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-shopping-cart me-2"></i>Recent Purchase Orders
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>PO Number</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Total Amount</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($supplier->purchaseOrders as $po)
                                        <tr>
                                            <td>{{ $po->po_number }}</td>
                                            <td>{{ $po->po_date ? $po->po_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $po->status === 'completed' ? 'success' : ($po->status === 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($po->status) }}
                                                </span>
                                            </td>
                                            <td>${{ number_format($po->total_amount ?? 0, 2) }}</td>
                                            <td>
                                                <a href="{{ route('finance.procurement.show', $po->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
