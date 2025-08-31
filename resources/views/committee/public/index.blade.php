@extends('layouts.admin')

@section('content')
<!-- Top Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Committee Management</span>
        <div class="navbar-nav">
            <a class="nav-link" href="{{ route('committee.index') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a class="nav-link" href="{{ route('committee.committees.index') }}">
                <i class="fas fa-users"></i> Committees
            </a>
            <a class="nav-link" href="{{ route('committee.meetings.index') }}">
                <i class="fas fa-calendar"></i> Meetings
            </a>
            <a class="nav-link" href="{{ route('committee.agendas.index') }}">
                <i class="fas fa-list"></i> Agendas
            </a>
            <a class="nav-link" href="{{ route('committee.minutes.index') }}">
                <i class="fas fa-file-alt"></i> Minutes
            </a>
            <a class="nav-link" href="{{ route('committee.members.index') }}">
                <i class="fas fa-user-friends"></i> Members
            </a>
            <a class="nav-link" href="{{ route('committee.resolutions.index') }}">
                <i class="fas fa-gavel"></i> Resolutions
            </a>
            <a class="nav-link active" href="{{ route('committee.public.index') }}">
                <i class="fas fa-globe"></i> Public Documents
            </a>
            <a class="nav-link" href="{{ route('committee.archive.index') }}">
                <i class="fas fa-archive"></i> Archive
            </a>
        </div>
    </div>
</nav>

<!-- Main content -->
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Public Committee Information</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <!-- Publish Information Modal Button -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#publishModal">
                <i class="fas fa-plus"></i> Publish Information
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Published Meeting Schedules</h5>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                        <i class="fas fa-plus"></i> Add Schedule
                    </button>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Executive Committee - Next Meeting</strong>
                                <br><small class="text-muted">Location: Council Chamber</small>
                            </div>
                            <div>
                                <span class="badge bg-primary">{{ date('M d, Y', strtotime('+1 week')) }}</span>
                                <button class="btn btn-sm btn-outline-secondary ms-2" onclick="editSchedule(1)">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Finance Committee - Next Meeting</strong>
                                <br><small class="text-muted">Location: Finance Office</small>
                            </div>
                            <div>
                                <span class="badge bg-primary">{{ date('M d, Y', strtotime('+10 days')) }}</span>
                                <button class="btn btn-sm btn-outline-secondary ms-2" onclick="editSchedule(2)">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Recent Resolutions</h5>
                    <a href="{{ route('committee.resolutions.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i> View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6>Budget Approval 2024</h6>
                                    <small class="text-muted">Approved - {{ date('M d, Y', strtotime('-5 days')) }}</small>
                                </div>
                                <div>
                                    <span class="badge bg-success">Approved</span>
                                    <button class="btn btn-sm btn-outline-primary ms-2" onclick="viewResolution(1)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6>Infrastructure Development</h6>
                                    <small class="text-muted">Under Review - {{ date('M d, Y', strtotime('-2 days')) }}</small>
                                </div>
                                <div>
                                    <span class="badge bg-warning">Under Review</span>
                                    <button class="btn btn-sm btn-outline-primary ms-2" onclick="viewResolution(2)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Published Documents</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                        <i class="fas fa-upload"></i> Upload Document
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Document</th>
                                    <th>Type</th>
                                    <th>Committee</th>
                                    <th>Date Published</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Annual Report 2023</td>
                                    <td><span class="badge bg-info">Report</span></td>
                                    <td>Executive Committee</td>
                                    <td>{{ date('M d, Y', strtotime('-30 days')) }}</td>
                                    <td><span class="badge bg-success">Published</span></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="downloadDocument(1)">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="viewDocument(1)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="unpublishDocument(1)">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Meeting Minutes - January</td>
                                    <td><span class="badge bg-secondary">Minutes</span></td>
                                    <td>Finance Committee</td>
                                    <td>{{ date('M d, Y', strtotime('-15 days')) }}</td>
                                    <td><span class="badge bg-success">Published</span></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="downloadDocument(2)">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="viewDocument(2)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="unpublishDocument(2)">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Budget Resolution 2024</td>
                                    <td><span class="badge bg-warning">Resolution</span></td>
                                    <td>Finance Committee</td>
                                    <td>{{ date('M d, Y', strtotime('-7 days')) }}</td>
                                    <td><span class="badge bg-warning">Draft</span></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-success" onclick="publishDocument(3)">
                                                <i class="fas fa-globe"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="viewDocument(3)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editDocument(3)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-primary">12</h5>
                    <p class="card-text">Published Documents</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success">8</h5>
                    <p class="card-text">Scheduled Meetings</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning">5</h5>
                    <p class="card-text">Active Resolutions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-info">156</h5>
                    <p class="card-text">Total Downloads</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Publish Information Modal -->
<div class="modal fade" id="publishModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Publish Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('committee.public.store-info') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="publishType" class="form-label">Information Type</label>
                        <select class="form-select" id="publishType" name="type" required>
                            <option value="">Select Type</option>
                            <option value="meeting">Meeting Schedule</option>
                            <option value="resolution">Resolution</option>
                            <option value="document">Document</option>
                            <option value="announcement">Announcement</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="publishTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="publishTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="publishDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="publishDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="publishCommittee" class="form-label">Committee</label>
                        <select class="form-select" id="publishCommittee" name="committee_id">
                            <option value="">Select Committee</option>
                            <option value="1">Executive Committee</option>
                            <option value="2">Finance Committee</option>
                            <option value="3">Planning Committee</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Publish</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Meeting Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('committee.public.store-schedule') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="scheduleCommittee" class="form-label">Committee</label>
                        <select class="form-select" id="scheduleCommittee" name="committee_id" required>
                            <option value="">Select Committee</option>
                            <option value="1">Executive Committee</option>
                            <option value="2">Finance Committee</option>
                            <option value="3">Planning Committee</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="scheduleDate" class="form-label">Meeting Date</label>
                        <input type="date" class="form-control" id="scheduleDate" name="meeting_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="scheduleTime" class="form-label">Meeting Time</label>
                        <input type="time" class="form-control" id="scheduleTime" name="meeting_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="scheduleLocation" class="form-label">Location</label>
                        <input type="text" class="form-control" id="scheduleLocation" name="location" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('committee.public.upload-document') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="documentTitle" class="form-label">Document Title</label>
                        <input type="text" class="form-control" id="documentTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="documentType" class="form-label">Document Type</label>
                        <select class="form-select" id="documentType" name="type" required>
                            <option value="">Select Type</option>
                            <option value="minutes">Meeting Minutes</option>
                            <option value="agenda">Meeting Agenda</option>
                            <option value="resolution">Resolution</option>
                            <option value="report">Report</option>
                            <option value="policy">Policy Document</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="documentCommittee" class="form-label">Committee</label>
                        <select class="form-select" id="documentCommittee" name="committee_id" required>
                            <option value="">Select Committee</option>
                            <option value="1">Executive Committee</option>
                            <option value="2">Finance Committee</option>
                            <option value="3">Planning Committee</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="documentFile" class="form-label">Document File</label>
                        <input type="file" class="form-control" id="documentFile" name="document" accept=".pdf,.doc,.docx" required>
                    </div>
                    <div class="mb-3">
                        <label for="documentDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="documentDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="publishImmediately" name="publish_immediately" value="1">
                        <label class="form-check-label" for="publishImmediately">
                            Publish immediately
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload Document</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// JavaScript functions for button actions
function editSchedule(scheduleId) {
    // Implementation for editing schedule
    alert('Edit Schedule functionality - Schedule ID: ' + scheduleId);
    // You can redirect to edit form or open a modal
}

function viewResolution(resolutionId) {
    // Redirect to resolution view page
    window.open('{{ route("committee.resolutions.index") }}', '_blank');
}

function downloadDocument(documentId) {
    // Implementation for downloading document
    alert('Download Document functionality - Document ID: ' + documentId);
    // You can trigger actual file download here
}

function viewDocument(documentId) {
    // Implementation for viewing document
    alert('View Document functionality - Document ID: ' + documentId);
    // You can open document in new tab or modal
}

function editDocument(documentId) {
    // Implementation for editing document
    alert('Edit Document functionality - Document ID: ' + documentId);
}

function publishDocument(documentId) {
    if (confirm('Are you sure you want to publish this document?')) {
        alert('Publish Document functionality - Document ID: ' + documentId);
        // You can make AJAX call to publish the document
    }
}

function unpublishDocument(documentId) {
    if (confirm('Are you sure you want to unpublish this document?')) {
        alert('Unpublish Document functionality - Document ID: ' + documentId);
        // You can make AJAX call to unpublish the document
    }
}

// Initialize tooltips if using Bootstrap 5
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
.btn-group .btn {
    margin-right: 2px;
}
.badge {
    font-size: 0.8em;
}
.list-group-item {
    border-left: 4px solid transparent;
}
.list-group-item:hover {
    border-left-color: #007bff;
    background-color: #f8f9fa;
}
.card-title {
    font-size: 2rem;
    font-weight: bold;
}
</style>
@endsection
