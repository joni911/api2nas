@extends('layouts.guest')

@section('title', 'Konfirmasi Kata Sandi')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="text-center mb-4">
            <h1 class="h4 fw-semibold mb-1">Konfirmasi kata sandi</h1>
            <p class="text-muted small mb-0">Masukkan kata sandi Anda sebelum melanjutkan.</p>
        </div>

        <div class="app-card app-card-body">
            <form method="POST" action="{{ route('password.confirm') }}" data-loading>
                @csrf
                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">Konfirmasi</button>
            </form>
        </div>
    </div>
</div>
@endsection