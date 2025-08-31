@extends('layouts.admin')

@section('title', 'Create Water Bill')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Create Water Bill</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('water.billing.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="connection_id">Water Connection</label>
                                    <select class="form-control" id="connection_id" name="connection_id" required>
                                        <option value="">Select Connection</option>
                                        @foreach($connections as $connection)
                                            <option value="{{ $connection->id }}">
                                                {{ $connection->customer->name ?? 'Unknown' }} - {{ $connection->meter_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="billing_period_start">Billing Period Start</label>
                                    <input type="date" class="form-control" id="billing_period_start" name="billing_period_start" required>
                                </div>
                                <div class="form-group">
                                    <label for="billing_period_end">Billing Period End</label>
                                    <input type="date" class="form-control" id="billing_period_end" name="billing_period_end" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="previous_reading">Previous Reading</label>
                                    <input type="number" class="form-control" id="previous_reading" name="previous_reading" step="0.01" required>
                                </div>
                                <div class="form-group">
                                    <label for="current_reading">Current Reading</label>
                                    <input type="number" class="form-control" id="current_reading" name="current_reading" step="0.01" required>
                                </div>
                                <div class="form-group">
                                    <label for="rate_per_unit">Rate per Unit ($/m³)</label>
                                    <input type="number" class="form-control" id="rate_per_unit" name="rate_per_unit" step="0.01" value="2.50" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Bill</button>
                        <a href="{{ route('water.billing.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
