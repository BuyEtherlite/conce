@extends('layouts.app')

@section('page-title', 'Customer Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4>👤 Customer Details</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('administration.index') }}">Administration</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('administration.crm.index') }}">CRM</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('administration.customers.index') }}">Customers</a></li>
                    <li class="breadcrumb-item active">{{ $customer->customer_number }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('administration.customers.edit', $customer) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit me-1"></i>Edit Customer
            </a>
            <a href="{{ route('administration.customers.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to List
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Customer Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th>Customer Number:</th>
                                    <td>{{ $customer->customer_number }}</td>
                                </tr>
                                <tr>
                                    <th>Full Name:</th>
                                    <td>{{ $customer->full_name }}</td>
                                </tr>
                                <tr>
                                    <th>ID Number:</th>
                                    <td>{{ $customer->id_number }}</td>
                                </tr>
                                <tr>
                                    <th>Gender:</th>
                                    <td>{{ ucfirst($customer->gender) }}</td>
                                </tr>
                                <tr>
                                    <th>Date of Birth:</th>
                                    <td>{{ $customer->date_of_birth ? $customer->date_of_birth->format('d M Y') : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $customer->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $customer->phone }}</td>
                                </tr>
                                <tr>
                                    <th>Alternative Phone:</th>
                                    <td>{{ $customer->alternative_phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $customer->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($customer->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Council:</th>
                                    <td>{{ $customer->council->name ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Requests -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Service Requests</h5>
                    <a href="{{ route('administration.service-requests.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>New Request
                    </a>
                </div>
                <div class="card-body">
                    @if($customer->serviceRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Request #</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->serviceRequests as $request)
                                    <tr>
                                        <td>{{ $request->request_number }}</td>
                                        <td>{{ $request->title }}</td>
                                        <td>
                                            <span class="badge bg-{{ $request->status === 'completed' ? 'success' : ($request->status === 'pending' ? 'warning' : 'info') }}">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $request->priority === 'urgent' ? 'danger' : ($request->priority === 'high' ? 'warning' : 'info') }}">
                                                {{ ucfirst($request->priority) }}
                                            </span>
                                        </td>
                                        <td>{{ $request->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('administration.service-requests.show', $request) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No service requests yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Address Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Address Information</h5>
                </div>
                <div class="card-body">
                    <h6>Physical Address:</h6>
                    <p class="text-muted">{{ $customer->physical_address }}</p>
                    
                    @if($customer->postal_address)
                    <h6>Postal Address:</h6>
                    <p class="text-muted">{{ $customer->postal_address }}</p>
                    @endif
                </div>
            </div>

            <!-- Additional Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Additional Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Nationality:</th>
                            <td>{{ $customer->nationality }}</td>
                        </tr>
                        @if($customer->occupation)
                        <tr>
                            <th>Occupation:</th>
                            <td>{{ $customer->occupation }}</td>
                        </tr>
                        @endif
                        @if($customer->employer)
                        <tr>
                            <th>Employer:</th>
                            <td>{{ $customer->employer }}</td>
                        </tr>
                        @endif
                        @if($customer->monthly_income)
                        <tr>
                            <th>Monthly Income:</th>
                            <td>${{ number_format($customer->monthly_income, 2) }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            @if($customer->notes)
            <!-- Notes -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Notes</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">{{ $customer->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
