@extends('layouts.council-admin')

@section('title', 'System Information')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">System Information</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('council-admin.dashboard') }}">Admin</a></li>
                        <li class="breadcrumb-item active">System</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Application Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Laravel Version:</strong></td>
                            <td>{{ app()->version() }}</td>
                        </tr>
                        <tr>
                            <td><strong>PHP Version:</strong></td>
                            <td>{{ PHP_VERSION }}</td>
                        </tr>
                        <tr>
                            <td><strong>Environment:</strong></td>
                            <td>{{ config('app.env') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Timezone:</strong></td>
                            <td>{{ config('app.timezone') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Database Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Connection:</strong></td>
                            <td>{{ config('database.default') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Driver:</strong></td>
                            <td>{{ config('database.connections.' . config('database.default') . '.driver') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Host:</strong></td>
                            <td>{{ config('database.connections.' . config('database.default') . '.host') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Database:</strong></td>
                            <td>{{ config('database.connections.' . config('database.default') . '.database') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">System Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-primary">{{ \App\Models\User::count() }}</h3>
                                <p class="text-muted">Total Users</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-success">{{ \App\Models\Department::count() }}</h3>
                                <p class="text-muted">Departments</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-info">{{ \App\Models\Office::count() }}</h3>
                                <p class="text-muted">Offices</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-warning">{{ \App\Models\Customer::count() }}</h3>
                                <p class="text-muted">Customers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
