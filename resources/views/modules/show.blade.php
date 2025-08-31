@extends('layouts.app')

@section('title', $moduleData->display_name)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ $moduleData->display_name }}</h4>
                </div>
                <div class="card-body">
                    <p>{{ $moduleData->description }}</p>
                    
                    @if($moduleData->is_active)
                        <div class="alert alert-success">
                            This module is currently active and available for use.
                        </div>
                        
                        <a href="{{ route($moduleData->module_key . '.index') }}" class="btn btn-primary">
                            Access {{ $moduleData->display_name }}
                        </a>
                    @else
                        <div class="alert alert-warning">
                            This module is currently disabled.
                        </div>
                    @endif
                    
                    <div class="mt-3">
                        <a href="{{ route('modules.index') }}" class="btn btn-secondary">
                            Back to Modules
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
