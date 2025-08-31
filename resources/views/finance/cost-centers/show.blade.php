@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Cost Center Details</h3>
                    <div>
                        <a href="{{ route('finance.cost-centers.edit', $id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('finance.cost-centers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Code:</th>
                                    <td>CC{{ str_pad($id, 4, '0', STR_PAD_LEFT) }}</td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td>Sample Cost Center</td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td>Sample description for cost center</td>
                                </tr>
                                <tr>
                                    <th>Budget:</th>
                                    <td>${{ number_format(100000, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td><span class="badge badge-success">Active</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
