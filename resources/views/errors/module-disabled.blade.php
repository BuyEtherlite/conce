@extends('layouts.admin')

@section('title', 'Module Disabled')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-warning">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i> Module Disabled
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-ban fa-5x text-warning mb-3"></i>
                        <h3>{{ $moduleName ?? ucfirst($module) }} Module is Disabled</h3>
                        <p class="text-muted">
                            This module has been disabled by the system administrator and is currently not available.
                        </p>
                    </div>

                    <div class="alert alert-info">
                        <strong>Module:</strong> {{ $module }}<br>
                        <strong>Status:</strong> Disabled
                    </div>

                    <div class="mt-4">
                        @if(auth()->user()->role === 'super_admin')
                            <a href="{{ route('administration.core-modules.index') }}" class="btn btn-primary">
                                <i class="fas fa-cogs"></i> Manage Modules
                            </a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-home"></i> Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Module Disabled')

@section('content')
<div class="error-container">
    <div class="error-content">
        <div class="error-icon">
            <i class="fas fa-ban"></i>
        </div>
        <h1>Module Disabled</h1>
        <p>The {{ $module }} module has been disabled by the administrator.</p>
        <p>Please contact your system administrator for access.</p>
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
@extends('layouts.admin')

@section('title', 'Module Not Available')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-ban fa-4x text-warning"></i>
                    </div>
                    
                    <h2 class="h4 text-gray-800 mb-3">Module Not Available</h2>
                    
                    <p class="text-gray-600 mb-4">
                        The <strong>{{ $moduleName }}</strong> module is currently disabled and cannot be accessed.
                    </p>

                    @if(Auth::user()->role === 'super_admin')
                        <div class="mb-4">
                            <p class="text-muted small">
                                As a super administrator, you can enable this module in the Core Modules Management section.
                            </p>
                            <a href="{{ route('administration.core-modules.index') }}" class="btn btn-primary">
                                <i class="fas fa-cogs mr-2"></i>Manage Modules
                            </a>
                        </div>
                    @else
                        <p class="text-muted small mb-4">
                            Please contact your system administrator to enable this module.
                        </p>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-home mr-2"></i>Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
