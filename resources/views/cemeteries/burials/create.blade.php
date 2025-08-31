@extends('layouts.admin')

@section('page-title', 'Add Burial Record')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>➕ Add New Burial Record</h4>
        <a href="{{ route('cemeteries.burials.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Burials
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('cemeteries.burials.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="deceased_name" class="form-label">Deceased Name *</label>
                            <input type="text" class="form-control @error('deceased_name') is-invalid @enderror" 
                                   id="deceased_name" name="deceased_name" value="{{ old('deceased_name') }}" required>
                            @error('deceased_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date_of_death" class="form-label">Date of Death *</label>
                            <input type="date" class="form-control @error('date_of_death') is-invalid @enderror" 
                                   id="date_of_death" name="date_of_death" value="{{ old('date_of_death') }}" required>
                            @error('date_of_death')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="burial_date" class="form-label">Burial Date *</label>
                            <input type="date" class="form-control @error('burial_date') is-invalid @enderror" 
                                   id="burial_date" name="burial_date" value="{{ old('burial_date') }}" required>
                            @error('burial_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="plot_id" class="form-label">Plot *</label>
                            <select class="form-select @error('plot_id') is-invalid @enderror" id="plot_id" name="plot_id" required>
                                <option value="">Select Plot</option>
                                <option value="1" {{ old('plot_id') == '1' ? 'selected' : '' }}>Plot A-001</option>
                                <option value="2" {{ old('plot_id') == '2' ? 'selected' : '' }}>Plot A-002</option>
                                <option value="3" {{ old('plot_id') == '3' ? 'selected' : '' }}>Plot B-001</option>
                            </select>
                            @error('plot_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="next_of_kin" class="form-label">Next of Kin *</label>
                            <input type="text" class="form-control @error('next_of_kin') is-invalid @enderror" 
                                   id="next_of_kin" name="next_of_kin" value="{{ old('next_of_kin') }}" required>
                            @error('next_of_kin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">Contact Phone</label>
                            <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror" 
                                   id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}">
                            @error('contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('cemeteries.burials.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Burial Record</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
