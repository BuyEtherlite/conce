@extends('layouts.app')

@section('title', 'Access Denied')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-lock"></i>
                        Access Denied
                    </h4>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-user-slash fa-5x text-danger"></i>
                    </div>
                    <h2 class="text-dark mb-3">Access to {{ $moduleName ?? 'Module' }} Denied</h2>
                    <p class="text-muted mb-4">
                        You do not have permission to access this module. 
                        Please contact your department supervisor or system administrator 
                        to request access to this functionality.
                    </p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Need Access?</strong> Contact your administrator to request module permissions for your department.
                    </div>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i> Return to Dashboard
                        </a>
                        <button onclick="history.back()" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Go Back
                        </button>
                    </div>
                </div>
                <div class="card-footer text-muted text-center">
                    <small>
                        <i class="fas fa-user"></i>
                        User: {{ auth()->user()->name ?? 'Guest' }} | 
                        <i class="fas fa-clock"></i>
                        Time: {{ now()->format('Y-m-d H:i:s') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Access Denied')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-ban"></i>
                        Access Denied
                    </h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-lock fa-5x text-danger"></i>
                    </div>
                    
                    <h3 class="mb-3">{{ $moduleName ?? ucfirst($module) }} Module</h3>
                    
                    <p class="lead text-muted mb-4">
                        You do not have permission to access this module.
                        Your department access does not include this functionality.
                    </p>

                    <div class="alert alert-warning">
                        <strong>Module:</strong> {{ $module }}<br>
                        <strong>Your Role:</strong> {{ Auth::user()->role }}<br>
                        <strong>Department:</strong> {{ Auth::user()->department->name ?? 'Not assigned' }}
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i> Return to Dashboard
                        </a>
                        
                        <a href="#" onclick="history.back()" class="btn btn-secondary ms-2">
                            <i class="fas fa-arrow-left"></i> Go Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Access Denied')

@section('content')
<div class="error-container">
    <div class="error-content">
        <div class="error-icon">
            <i class="fas fa-lock"></i>
        </div>
        <h1>Access Denied</h1>
        <p>You don't have permission to access this resource.</p>
        <p>Please contact your system administrator if you believe this is an error.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">
            <i class="fas fa-home"></i> Return to Dashboard
        </a>
    </div>
</div>

<style>
.error-container {
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.error-content {
    max-width: 500px;
    padding: 40px;
}

.error-icon {
    font-size: 64px;
    color: #dc3545;
    margin-bottom: 20px;
}

.error-content h1 {
    font-size: 32px;
    margin-bottom: 16px;
    color: #333;
}

.error-content p {
    font-size: 16px;
    color: #666;
    margin-bottom: 16px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    margin-top: 20px;
    transition: background 0.3s ease;
}

.btn:hover {
    background: #0056b3;
    color: white;
}
</style>
@endsection
