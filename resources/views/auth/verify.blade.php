@extends('layouts.guest')

@section('title', 'Verifikasi Email')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <div class="app-card app-card-body text-center">
            @if (session('resent'))
                <div class="alert alert-success small" role="alert">{{ __('A fresh verification link has been sent to your email address.') }}</div>
            @endif

            <h1 class="h5 fw-semibold mb-2">Verifikasi email Anda</h1>
            <p class="text-muted small">{{ __('Before proceeding, please check your email for a verification link.') }}</p>
            <p class="text-muted small mb-0">
                {{ __('If you did not receive the email') }},
                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                </form>
            </p>
        </div>
    </div>
</div>
@endsection