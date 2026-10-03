@extends('layouts.app')

@section('title', 'Buat API Key')
@section('page_title', 'Buat API Key')
@section('page_sub', 'Kunci baru langsung aktif dan siap dipakai')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">
        <div class="app-card reveal">
            <div class="app-card-header">
                <h2 class="app-card-title">Detail API Key</h2>
                <a href="{{ route('api-management.index') }}" class="small">← Kembali</a>
            </div>
            <div class="app-card-body">
                <form action="{{ route('api-management.store') }}" method="POST" data-loading>
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama API Key</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="mis. Website Utama" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Nama ini dipakai sebagai folder penyimpanan file.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Buat API Key</button>
                        <a href="{{ route('api-management.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection