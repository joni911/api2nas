@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="text-center mb-4">
            <h1 class="h4 fw-semibold mb-1">Buat kata sandi baru</h1>
            <p class="text-muted small mb-0">Pastikan kata sandi minimal 8 karakter.</p>
        </div>

        <div class="app-card app-card-body">
            <form method="POST" action="{{ route('password.update') }}" data-loading>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi Baru</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password-confirm" class="form-label">Konfirmasi Kata Sandi</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">Reset Kata Sandi</button>
            </form>
        </div>
    </div>
</div>
@endsection