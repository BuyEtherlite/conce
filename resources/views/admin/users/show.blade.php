@extends('layouts.admin')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>👤 User Details</h1>
                <div>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit User
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>User Information</h5>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-3">Name:</dt>
                                <dd class="col-sm-9">{{ $user->name }}</dd>

                                <dt class="col-sm-3">Email:</dt>
                                <dd class="col-sm-9">{{ $user->email }}</dd>

                                <dt class="col-sm-3">Role:</dt>
                                <dd class="col-sm-9">
                                    <span class="badge badge-{{ $user->role == 'super_admin' ? 'danger' : ($user->role == 'admin' ? 'warning' : 'primary') }}">
                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </dd>

                                <dt class="col-sm-3">Status:</dt>
                                <dd class="col-sm-9">
                                    <span class="badge badge-{{ $user->is_active ? 'success' : 'secondary' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </dd>

                                <dt class="col-sm-3">Department:</dt>
                                <dd class="col-sm-9">{{ $user->department->name ?? 'Not Assigned' }}</dd>

                                <dt class="col-sm-3">Office:</dt>
                                <dd class="col-sm-9">{{ $user->office->name ?? 'Not Assigned' }}</dd>

                                <dt class="col-sm-3">Phone:</dt>
                                <dd class="col-sm-9">{{ $user->phone ?? 'Not provided' }}</dd>

                                <dt class="col-sm-3">Position:</dt>
                                <dd class="col-sm-9">{{ $user->position ?? 'Not specified' }}</dd>

                                <dt class="col-sm-3">Employee ID:</dt>
                                <dd class="col-sm-9">{{ $user->employee_id ?? 'Not assigned' }}</dd>

                                <dt class="col-sm-3">Created:</dt>
                                <dd class="col-sm-9">{{ $user->created_at->format('Y-m-d H:i:s') }}</dd>

                                <dt class="col-sm-3">Last Updated:</dt>
                                <dd class="col-sm-9">{{ $user->updated_at->format('Y-m-d H:i:s') }}</dd>

                                @if($user->email_verified_at)
                                <dt class="col-sm-3">Email Verified:</dt>
                                <dd class="col-sm-9">
                                    <span class="badge badge-success">
                                        {{ $user->email_verified_at->format('Y-m-d H:i:s') }}
                                    </span>
                                </dd>
                                @else
                                <dt class="col-sm-3">Email Verified:</dt>
                                <dd class="col-sm-9">
                                    <span class="badge badge-warning">Not Verified</span>
                                </dd>
                                @endif
                            </dl>
                        </div>
                    </div>

                    @if($user->access_modules)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Module Access</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach(json_decode($user->access_modules, true) ?? [] as $module)
                                <div class="col-md-4 mb-2">
                                    <span class="badge badge-primary">{{ ucfirst(str_replace('_', ' ', $module)) }}</span>
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
                            <h5>Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit User
                                </a>

                                @if(!$user->email_verified_at)
                                <form method="POST" action="#" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning w-100">
                                        <i class="fas fa-envelope"></i> Send Verification Email
                                    </button>
                                </form>
                                @endif

                                <form method="POST" action="#" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-info w-100">
                                        <i class="fas fa-key"></i> Reset Password
                                    </button>
                                </form>

                                @if($user->is_active)
                                <form method="POST" action="#" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Are you sure you want to deactivate this user?')">
                                        <i class="fas fa-user-slash"></i> Deactivate User
                                    </button>
                                </form>
                                @else
                                <form method="POST" action="#" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-user-check"></i> Activate User
                                    </button>
                                </form>
                                @endif

                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                        <i class="fas fa-trash"></i> Delete User
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>Activity Log</h5>
                        </div>
                        <div class="card-body">
                            <div class="small">
                                <div class="mb-2">
                                    <strong>Last Login:</strong><br>
                                    {{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never' }}
                                </div>
                                <div class="mb-2">
                                    <strong>Login Count:</strong><br>
                                    {{ $user->login_count ?? 0 }} times
                                </div>
                                <div class="mb-2">
                                    <strong>Account Created:</strong><br>
                                    {{ $user->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection