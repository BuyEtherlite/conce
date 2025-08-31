@extends('layouts.app')

@section('page-title', $facility->facility_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ $facility->facility_name }}</h4>
                <div class="page-title-right">
                    <a href="{{ route('facilities.edit', $facility) }}" class="btn btn-primary me-2">
                        <i class="bi bi-pencil me-2"></i>Edit Facility
                    </a>
                    <a href="{{ route('facilities.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Facility Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Type:</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $facility->facility_type)) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Capacity:</td>
                                    <td>{{ $facility->capacity }} people</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status:</td>
                                    <td>
                                        @if($facility->active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Manager:</td>
                                    <td>{{ $facility->manager_name ?: 'Not assigned' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Operating Hours:</td>
                                    <td>{{ date('H:i', strtotime($facility->opening_time)) }} - {{ date('H:i', strtotime($facility->closing_time)) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Operating Days:</td>
                                    <td>
                                        @if($facility->operating_days)
                                            @foreach($facility->operating_days as $day)
                                                <span class="badge bg-light text-dark me-1">{{ ucfirst($day) }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Contact:</td>
                                    <td>{{ $facility->manager_contact ?: 'Not provided' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-bold">Address:</h6>
                            <p>{{ $facility->address }}</p>
                        </div>
                    </div>

                    @if($facility->description)
                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-bold">Description:</h6>
                            <p>{{ $facility->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Pricing -->
            @if($facility->hourly_rate > 0 || $facility->daily_rate > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pricing</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($facility->hourly_rate > 0)
                        <div class="col-md-6">
                            <div class="text-center p-3 border rounded">
                                <h4 class="text-primary">${{ number_format($facility->hourly_rate, 2) }}</h4>
                                <p class="mb-0">per hour</p>
                            </div>
                        </div>
                        @endif
                        @if($facility->daily_rate > 0)
                        <div class="col-md-6">
                            <div class="text-center p-3 border rounded">
                                <h4 class="text-success">${{ number_format($facility->daily_rate, 2) }}</h4>
                                <p class="mb-0">per day</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Documents -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Documents ({{ $facility->documents->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($facility->documents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Document</th>
                                        <th>Type</th>
                                        <th>Size</th>
                                        <th>Uploaded By</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($facility->documents as $document)
                                    <tr>
                                        <td>
                                            <i class="bi bi-file-earmark me-2"></i>
                                            {{ $document->document_name }}
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ strtoupper($document->document_type) }}</span>
                                        </td>
                                        <td>{{ $document->file_size_human }}</td>
                                        <td>{{ $document->uploaded_by ?: 'System' }}</td>
                                        <td>{{ $document->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('facilities.documents.download', $document) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            <button onclick="deleteDocument({{ $document->id }})" 
                                                    class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-file-earmark display-4 d-block mb-3"></i>
                            <p>No documents uploaded yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Amenities -->
            @if($facility->amenities)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Amenities</h5>
                </div>
                <div class="card-body">
                    @foreach($facility->amenities as $amenity)
                        <span class="badge bg-info me-2 mb-2">{{ ucfirst(str_replace('_', ' ', $amenity)) }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Total Bookings</span>
                        <span class="badge bg-primary">{{ $facility->bookings->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Pending Maintenance</span>
                        <span class="badge bg-warning">{{ $facility->maintenance->where('status', 'scheduled')->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Documents</span>
                        <span class="badge bg-info">{{ $facility->documents->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Document Modal -->
<div class="modal fade" id="deleteDocumentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this document? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteDocumentForm" method="POST" style="display: inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteDocument(id) {
    document.getElementById('deleteDocumentForm').action = `/facilities/documents/${id}`;
    new bootstrap.Modal(document.getElementById('deleteDocumentModal')).show();
}
</script>
@endsection
