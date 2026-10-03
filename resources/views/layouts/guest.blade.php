<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#f3f3f3" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#202020" media="(prefers-color-scheme: dark)">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel')) - {{ config('app.name', 'API2NAS') }}</title>
    <meta name="description" content="@yield('meta_description', 'API image storage system for backup and public file access')">
    <meta name="keywords" content="@yield('meta_keywords', 'api, image storage, file upload, backup')">
    <meta name="robots" content="index, follow">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', config('app.name', 'API2NAS'))">
    <meta property="og:description" content="@yield('meta_description', 'API image storage system for backup and public file access')">
    <meta property="og:url" content="{{ url()->current() }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <script>
        try {
            var t = localStorage.getItem('app-theme');
            if (t === 'dark' || t === 'light') document.documentElement.setAttribute('data-bs-theme', t);
        } catch (e) {}
    </script>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('seo')
</head>
<body>
    <div class="guest-shell">
        <header class="guest-nav">
            <a href="{{ url('/') }}" class="app-brand">
                <span class="app-logo">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg>
                </span>
                <span>{{ config('app.name', 'API2NAS') }}</span>
            </a>

            <div class="d-flex align-items-center gap-2">
                <button class="win-cmd" type="button" data-theme-toggle aria-label="Ganti tema" title="Ganti tema">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
                </button>

                @auth
                    <a href="{{ route('home') }}" class="btn btn-primary btn-sm px-3">Dashboard</a>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm px-3">Masuk</a>
                    @endif
                @endauth
            </div>
        </header>

        <div class="guest-body">
            @yield('content')
        </div>
    </div>

    <div class="win-toasts" id="appToastContainer" aria-live="polite">
        @foreach (['success' => 'success', 'status' => 'success', 'error' => 'danger', 'info' => 'info'] as $key => $variant)
            @if (session()->has($key))
                <div class="win-toast {{ $variant }}" data-autodismiss>
                    <span class="dot"></span>
                    <div class="small">{{ session($key) }}</div>
                </div>
            @endif
        @endforeach
    </div>
</body>
</html>