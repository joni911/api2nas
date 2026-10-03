@extends('layouts.app')

@section('title', 'Buat Pengguna')
@section('page_title', 'Users')

@section('breadcrumb_extra')
    <a href="{{ route('user-management.index') }}" class="win-crumb">Users</a>
    <span class="win-crumb-sep">›</span>
    <span class="win-crumb current">Baru</span>
@endsection

@section('commands')
    <a href="{{ route('user-management.index') }}" class="win-cmd">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Kembali
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">
        <div class="win-listwrap p-4 reveal">
            <h2 class="h6 fw-semibold mb-4">Akun Baru</h2>

            <form method="POST" action="{{ route('user-management.store') }}" data-loading>
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="input-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button" data-toggle-password="#password" aria-label="Tampilkan kata sandi">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
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
                    <button type="submit" class="btn btn-primary">Buat</button>
                    <a href="{{ route('user-management.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection