@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">📊 {{ $data['title'] }}</h3>
                </div>
                <div class="card-body">
                    <p>Period: {{ $data['period'] }}</p>
                    <div class="alert alert-info">
                        Budget execution report functionality will be implemented here.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
