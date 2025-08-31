@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>🛠️ New Maintenance Request</h4>
        <a href="{{ route('cemeteries.maintenance.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Maintenance
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Maintenance Details</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('cemeteries.maintenance.store') }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="maintenance_type" class="form-label">Maintenance Type</label>
                            <select class="form-select" id="maintenance_type" name="maintenance_type" required>
                                <option value="">Select Type</option>
                                <option value="groundskeeping">Groundskeeping</option>
                                <option value="repair">Repair</option>
                                <option value="cleaning">Cleaning</option>
                                <option value="landscaping">Landscaping</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" id="priority" name="priority" required>
                                <option value="">Select Priority</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="scheduled_date" class="form-label">Scheduled Date</label>
                            <input type="date" class="form-control" id="scheduled_date" name="scheduled_date" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="estimated_cost" class="form-label">Estimated Cost</label>
                            <input type="number" class="form-control" id="estimated_cost" name="estimated_cost" step="0.01">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Create Maintenance Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
