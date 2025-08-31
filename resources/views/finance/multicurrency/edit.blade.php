@extends('layouts.admin')

@section('title', 'Edit Currency - ' . $currency->currency_name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Currency - {{ $currency->currency_name }}
                    </h3>
                </div>
                <form action="{{ route('finance.multicurrency.update', $currency) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency_code">Currency Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('currency_code') is-invalid @enderror" 
                                           id="currency_code" name="currency_code" 
                                           value="{{ old('currency_code', $currency->currency_code) }}" 
                                           maxlength="3" style="text-transform: uppercase;" disabled>
                                    <small class="form-text text-muted">Currency code cannot be changed</small>
                                    @error('currency_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency_name">Currency Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('currency_name') is-invalid @enderror" 
                                           id="currency_name" name="currency_name" 
                                           value="{{ old('currency_name', $currency->currency_name) }}" 
                                           required maxlength="100">
                                    @error('currency_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="currency_symbol">Currency Symbol <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('currency_symbol') is-invalid @enderror" 
                                           id="currency_symbol" name="currency_symbol" 
                                           value="{{ old('currency_symbol', $currency->currency_symbol) }}" 
                                           required maxlength="10">
                                    @error('currency_symbol')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="decimal_places">Decimal Places <span class="text-danger">*</span></label>
                                    <select class="form-control @error('decimal_places') is-invalid @enderror" 
                                            id="decimal_places" name="decimal_places" required>
                                        <option value="">Select decimal places</option>
                                        @for($i = 0; $i <= 4; $i++)
                                            <option value="{{ $i }}" {{ old('decimal_places', $currency->decimal_places) == $i ? 'selected' : '' }}>
                                                {{ $i }} {{ $i == 1 ? 'place' : 'places' }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('decimal_places')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="hidden" name="is_base_currency" value="0">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="is_base_currency" name="is_base_currency" value="1"
                                               {{ old('is_base_currency', $currency->is_base_currency) ? 'checked' : '' }}
                                               {{ $currency->is_base_currency ? 'disabled' : '' }}>
                                        <label class="custom-control-label" for="is_base_currency">
                                            Base Currency
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Only one currency can be set as base currency. 
                                        @if($currency->is_base_currency)
                                            This currency is currently the base currency.
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="is_active" name="is_active" value="1"
                                               {{ old('is_active', $currency->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Inactive currencies cannot be used in transactions</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Currency
                        </button>
                        <a href="{{ route('finance.multicurrency.show', $currency) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
