@extends('layouts.app')

@section('title', 'Buat Pengguna')
@section('page_title', 'Buat Pengguna')
@section('page_sub', 'Tambahkan akun baru secara manual')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">
        <div class="app-card reveal">
            <div class="app-card-header">
                <h2 class="app-card-title">Akun Baru</h2>
                <a href="{{ route('user-management.index') }}" class="small">← Kembali</a>
            </div>
            <div class="app-card-body">
                <form method="POST" action="{{ route('user-management.store') }}" data-loading>
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <div class="input-group">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            <button class="btn btn-outline-secondary" type="button" data-toggle-password="#password" aria-label="Tampilkan kata sandi">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-text">Minimal 8 karakter.</div>
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm" class="form-label">Konfirmasi Kata Sandi</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Buat Pengguna</button>
                        <a href="{{ route('user-management.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection