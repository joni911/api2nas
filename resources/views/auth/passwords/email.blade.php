@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="text-center mb-4">
            <h1 class="h4 fw-semibold mb-1">Lupa kata sandi?</h1>
            <p class="text-muted small mb-0">Masukkan email Anda, kami akan mengirim tautan reset.</p>
        </div>

        <div class="app-card app-card-body">
            @if (session('status'))
                <div class="alert alert-success small" role="alert">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" data-loading>
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="admin@example.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">Kirim Tautan Reset</button>
            </form>
        </div>

        <p class="text-center small mt-3 mb-0"><a href="{{ route('login') }}">Kembali ke halaman masuk</a></p>
    </div>
</div>
@endsection