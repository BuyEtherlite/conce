@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-0 text-primary fw-bold">
                        <i class="fas fa-user me-2"></i>User Details
                    </h1>
                    <p class="text-muted mb-0">View user information and settings</p>
                </div>
                <div>
                    <a href="{{ route('administration.users.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>Back to Users
                    </a>
                    <a href="{{ route('administration.users.edit', $user) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>Edit User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Name:</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Role:</th>
                                    <td>
                                        <span class="badge 
                                            @if($user->role === 'super_admin') bg-danger
                                            @elseif($user->role === 'admin') bg-warning
                                            @elseif($user->role === 'pos_operator') bg-info
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Employee ID:</th>
                                    <td>{{ $user->employee_id ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $user->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Position:</th>
                                    <td>{{ $user->position ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Department:</th>
                                    <td>{{ $user->department->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Office:</th>
                                    <td>{{ $user->office->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Hire Date:</th>
                                    <td>{{ $user->hire_date ? $user->hire_date->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Salary:</th>
                                    <td>{{ $user->salary ? '$' . number_format($user->salary, 2) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Employment Status:</th>
                                    <td>
                                        <span class="badge 
                                            @if($user->employment_status === 'active') bg-success
                                            @elseif($user->employment_status === 'inactive') bg-warning
                                            @else bg-danger
                                            @endif">
                                            {{ ucfirst($user->employment_status ?? 'Active') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge {{ $user->active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $user->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if($user->department && $user->department->modules_access)
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Module Access (via Department)</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($user->department->modules_access as $module)
                            <div class="col-md-6 mb-2">
                                <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $module)) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Account Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-circle mx-auto mb-2" style="width: 80px; height: 80px; font-size: 32px;">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <h6>{{ $user->name }}</h6>
                        <p class="text-muted small">{{ $user->email }}</p>
                    </div>
                    
                    <hr>
                    
                    <div class="small">
                        <p><strong>Created:</strong><br>{{ $user->created_at->format('M d, Y g:i A') }}</p>
                        <p><strong>Last Updated:</strong><br>{{ $user->updated_at->format('M d, Y g:i A') }}</p>
                        @if($user->email_verified_at)
                            <p><strong>Email Verified:</strong><br>{{ $user->email_verified_at->format('M d, Y g:i A') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    border-radius: 50%;
    background: linear-gradient(45deg, #007bff, #6f42c1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}
</style>
@endsection
