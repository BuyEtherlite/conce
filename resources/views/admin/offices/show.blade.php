@extends('layouts.app')

@section('title', 'Office Details')

@extends('layouts.admin')

@section('title', 'Office Details')
@section('page-title', 'Office Details')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Office Details</h5>
                    <div>
                        <a href="{{ route('admin.offices.edit', $office) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.offices.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Office Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $office->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Code:</strong></td>
                                    <td>{{ $office->code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Department:</strong></td>
                                    <td>{{ $office->department->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($office->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-primary">Contact Information</h6>
                            <table class="table table-sm">
                                @if($office->phone)
                                    <tr>
                                        <td><strong>Phone:</strong></td>
                                        <td>{{ $office->phone }}</td>
                                    </tr>
                                @endif
                                @if($office->email)
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $office->email }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $office->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $office->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($office->address)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h6 class="text-primary">Address</h6>
                                <p>{{ $office->address }}</p>
                            </div>
                        </div>
                    @endif

                    @if($office->description)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h6 class="text-primary">Description</h6>
                                <p>{{ $office->description }}</p>
                            </div>
                        </div>
                    @endif

                    @if($office->users && $office->users->count() > 0)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h6 class="text-primary">Staff Members</h6>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($office->users as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td><span class="badge bg-primary">{{ ucfirst($user->role) }}</span></td>
                                                    <td>
                                                        @if($user->is_active)
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
