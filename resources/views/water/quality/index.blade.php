@extends('layouts.admin')

@section('page-title', 'Water Quality')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>💧 Water Quality Tests</h4>
        <a href="#" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>New Test
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Quality Test Results</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Test Number</th>
                            <th>Location</th>
                            <th>Test Date</th>
                            <th>Tested By</th>
                            <th>pH Level</th>
                            <th>Chlorine</th>
                            <th>Result</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tests as $test)
                        <tr>
                            <td>{{ $test->test_number }}</td>
                            <td>{{ $test->sample_location }}</td>
                            <td>{{ $test->test_date->format('Y-m-d') }}</td>
                            <td>{{ $test->tested_by }}</td>
                            <td>{{ $test->ph_level ?? 'N/A' }}</td>
                            <td>{{ $test->chlorine_level ?? 'N/A' }}</td>
                            <td>
                                @if($test->result === 'pass')
                                    <span class="badge bg-success">Pass</span>
                                @elseif($test->result === 'fail')
                                    <span class="badge bg-danger">Fail</span>
                                @else
                                    <span class="badge bg-warning">Retest</span>
                                @endif
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-info">View</a>
                                <a href="#" class="btn btn-sm btn-outline-primary">Report</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="py-4">
                                    <i class="fas fa-flask fa-3x mb-3"></i>
                                    <p>No quality tests found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tests->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $tests->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection