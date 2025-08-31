@extends('layouts.app')

@section('title', 'Available Modules')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Available Modules</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($modules as $module)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $module->display_name }}</h5>
                                        <p class="card-text">{{ $module->description }}</p>
                                        @if($module->is_active)
                                            <a href="{{ route($module->module_key . '.index') }}" class="btn btn-primary btn-sm">
                                                Access Module
                                            </a>
                                        @else
                                            <span class="badge badge-secondary">Disabled</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">
                                    No modules are currently available.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
