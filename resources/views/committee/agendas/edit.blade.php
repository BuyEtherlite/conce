@extends('layouts.app')

@section('page-title', 'Edit Meeting Agenda')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Side Navigation -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{ route('committee.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-home me-2"></i>Committee Dashboard
                </a>
                <a href="{{ route('committee.committees.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-users me-2"></i>Committees
                </a>
                <a href="{{ route('committee.meetings.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-calendar me-2"></i>Meetings
                </a>
                <a href="{{ route('committee.minutes.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-file-alt me-2"></i>Meeting Minutes
                </a>
                <a href="{{ route('committee.agendas.index') }}" class="list-group-item list-group-item-action active">
                    <i class="fas fa-list me-2"></i>Meeting Agendas
                </a>
                <a href="{{ route('committee.resolutions.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-gavel me-2"></i>Resolutions
                </a>
                <a href="{{ route('committee.members.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-user-friends me-2"></i>Members
                </a>
                <a href="{{ route('committee.public.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-globe me-2"></i>Public Documents
                </a>
                <a href="{{ route('committee.archive.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-archive me-2"></i>Archive
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Edit Meeting Agenda Item</h4>
                <a href="{{ route('committee.agendas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('committee.agendas.update', $agenda->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="meeting_id" class="form-label">Meeting <span class="text-danger">*</span></label>
                                    <select class="form-select @error('meeting_id') is-invalid @enderror" id="meeting_id" name="meeting_id" required>
                                        <option value="">Select Meeting</option>
                                        @foreach($meetings as $meeting)
                                            <option value="{{ $meeting->id }}" {{ old('meeting_id', $agenda->meeting_id) == $meeting->id ? 'selected' : '' }}>
                                                {{ $meeting->title }} - {{ $meeting->meeting_date->format('M d, Y') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('meeting_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="order_index" class="form-label">Order Index <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('order_index') is-invalid @enderror" 
                                           id="order_index" name="order_index" value="{{ old('order_index', $agenda->order_index) }}" min="1" required>
                                    @error('order_index')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $agenda->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="5">{{ old('description', $agenda->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Agenda Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
