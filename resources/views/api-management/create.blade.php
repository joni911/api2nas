@extends('layouts.app')

@section('title', 'Buat API Key')
@section('page_title', 'Buat API Key')

@section('breadcrumb_extra')
    <a href="{{ route('api-management.index') }}" class="win-crumb">API Keys</a>
    <span class="win-crumb-sep">›</span>
    <span class="win-crumb current">Baru</span>
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
            <h2 class="h6 fw-semibold mb-1">API Key Baru</h2>
            <p class="text-muted small mb-4">Kunci baru langsung aktif dan siap dipakai.</p>

            <form action="{{ route('api-management.store') }}" method="POST" data-loading>
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama API Key</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="mis. Website Utama" required autofocus>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Nama ini dipakai sebagai nama folder penyimpanan file.</div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Buat API Key</button>
                    <a href="{{ route('api-management.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection