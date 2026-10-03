@extends('layouts.app')

@section('title', 'Edit API Key')
@section('page_title', $apiKey->name)

@section('breadcrumb_extra')
    <a href="{{ route('api-management.index') }}" class="win-crumb">API Keys</a>
    <span class="win-crumb-sep">›</span>
    <span class="win-crumb current">Edit</span>
@endsection

@section('commands')
    <a href="{{ route('api-management.index') }}" class="win-cmd">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Kembali
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">
        <div class="win-listwrap p-4 reveal">
            <h2 class="h6 fw-semibold mb-4">Properti API Key</h2>

            <form action="{{ route('api-management.update', $apiKey->id) }}" method="POST" data-loading>
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nama API Key</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $apiKey->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $apiKey->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktifkan API key ini</label>
                    </div>
                    <div class="form-text">Kunci nonaktif ditolak saat dipakai upload.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label">API Key</label>
                    <div class="input-group">
                        <input type="text" class="form-control cell-mono" value="{{ $apiKey->api_key }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" data-copy="{{ $apiKey->api_key }}">Salin</button>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('api-management.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection