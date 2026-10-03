@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="text-center mb-4">
            <span class="app-logo mx-auto mb-3" style="width:48px;height:48px;border-radius:14px;display:grid;place-items:center;background:linear-gradient(135deg,var(--app-accent),var(--app-accent-2));box-shadow:0 8px 20px -8px var(--app-accent);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg>
            </span>
            <h1 class="h4 fw-semibold mb-1">Selamat datang kembali</h1>
            <p class="text-muted small mb-0">Masuk untuk mengelola API dan file Anda.</p>
        </div>

        <div class="app-card app-card-body reveal">
            <form method="POST" action="{{ route('login') }}" data-loading>
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="admin@example.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="input-group">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                        <button class="btn btn-outline-secondary" type="button" data-toggle-password="#password" aria-label="Tampilkan kata sandi">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="remember">Ingat saya</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="small" href="{{ route('password.request') }}">Lupa kata sandi?</a>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">Masuk</button>
            </form>
        </div>

        <p class="text-center text-muted small mt-3 mb-0">
            Registrasi publik dimatikan. Hubungi admin untuk pembuatan akun.
        </p>
    </div>
</div>
@endsection