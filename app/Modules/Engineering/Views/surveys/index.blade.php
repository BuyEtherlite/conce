@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Land Surveys</h4>
                <div class="page-title-right">
                    <a href="{{ route('engineering.surveys.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create Survey
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($surveys->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Survey #</th>
                                    <th>Property</th>
                                    <th>Type</th>
                                    <th>Surveyor</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($surveys as $survey)
                                <tr>
                                    <td><strong>{{ $survey->survey_number ?? 'SUR-001' }}</strong></td>
                                    <td>{{ $survey->property_description ?? 'Lot 1, Block 1' }}</td>
                                    <td>{{ ucfirst($survey->type ?? 'boundary') }}</td>
                                    <td>{{ $survey->surveyor_name ?? 'Jane Smith' }}</td>
                                    <td>{{ $survey->survey_date ? date('M d, Y', strtotime($survey->survey_date)) : 'TBD' }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ ucfirst($survey->status ?? 'completed') }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('engineering.surveys.show', $survey->id ?? 1) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            <a href="{{ route('engineering.surveys.edit', $survey->id ?? 1) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-geo-alt display-4 text-muted"></i>
                        <h5 class="mt-3">No Surveys Found</h5>
                        <p class="text-muted">Create your first survey to get started.</p>
                        <a href="{{ route('engineering.surveys.create') }}" class="btn btn-primary">Create Survey</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
