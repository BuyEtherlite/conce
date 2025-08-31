@extends('layouts.council-admin')

@section('title', 'System Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">System Settings</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('council-admin.dashboard') }}">Admin</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="mdi mdi-apps text-primary me-2"></i>Module Management
                    </h5>
                    <p class="card-text">Enable or disable system modules and features.</p>
                    <a href="{{ route('council-admin.settings.modules') }}" class="btn btn-primary">
                        Manage Modules
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="mdi mdi-security text-warning me-2"></i>Security Settings
                    </h5>
                    <p class="card-text">Configure system security and access controls.</p>
                    <a href="{{ route('council-admin.settings.security') }}" class="btn btn-warning">
                        Security Settings
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="mdi mdi-database text-info me-2"></i>System Information
                    </h5>
                    <p class="card-text">View system information and status.</p>
                    <a href="{{ route('council-admin.system.index') }}" class="btn btn-info">
                        System Info
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">System Configuration</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Setting</th>
                                    <th>Value</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Application Name</td>
                                    <td>{{ config('app.name') }}</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td>Environment</td>
                                    <td>{{ config('app.env') }}</td>
                                    <td><span class="badge bg-{{ config('app.env') === 'production' ? 'success' : 'warning' }}">{{ ucfirst(config('app.env')) }}</span></td>
                                </tr>
                                <tr>
                                    <td>Debug Mode</td>
                                    <td>{{ config('app.debug') ? 'Enabled' : 'Disabled' }}</td>
                                    <td><span class="badge bg-{{ config('app.debug') ? 'warning' : 'success' }}">{{ config('app.debug') ? 'On' : 'Off' }}</span></td>
                                </tr>
                                <tr>
                                    <td>Database Connection</td>
                                    <td>{{ config('database.default') }}</td>
                                    <td><span class="badge bg-success">Connected</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
