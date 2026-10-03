<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#f3f3f3" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#202020" media="(prefers-color-scheme: dark)">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel')) - {{ config('app.name', 'API2NAS') }}</title>
    <meta name="robots" content="noindex, nofollow">

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
@php
    $tabs = [
        ['route' => 'home', 'label' => 'Home', 'match' => 'home', 'icon' => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/>'],
        ['route' => 'api-management.index', 'label' => 'API Keys', 'match' => 'api-management.*', 'icon' => '<circle cx="8" cy="15" r="4"/><path d="m10.85 12.15 7.4-7.4"/><path d="M18 5.5 21 8.5"/>'],
        ['route' => 'data-api.index', 'label' => 'Data Upload', 'match' => 'data-api.*', 'icon' => '<path d="M4 7h6l2 2h8v9a2 2 0 0 1-2 2H4z"/>'],
        ['route' => 'user-management.index', 'label' => 'Users', 'match' => 'user-management.*', 'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>'],
    ];
@endphp

<div class="win-shell">
    {{-- Title bar --}}
    <div class="win-titlebar">
        <a href="{{ url('/') }}" class="app-brand">
            <span class="app-logo">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg>
            </span>
            <span class="d-none d-sm-inline">{{ config('app.name', 'API2NAS') }} — File Explorer</span>
        </a>

        <div class="ms-auto d-flex align-items-center gap-1">
            <button class="win-cmd" type="button" data-theme-toggle aria-label="Ganti tema" title="Ganti tema">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
            </button>

            <div class="dropdown">
                <button class="win-cmd" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Akun">
                    <span style="display:grid;place-items:center;width:24px;height:24px;border-radius:50%;background:var(--win-accent);color:var(--win-accent-fg);font-size:.7rem;font-weight:600;">{{ strtoupper(substr(auth()->user()->name ?? '?', 0, 1)) }}</span>
                    <span class="d-none d-md-inline">{{ auth()->user()->name ?? 'Guest' }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li class="px-3 py-2">
                        <div class="fw-semibold small">{{ auth()->user()->name ?? 'Guest' }}</div>
                        <div class="text-muted small text-truncate" style="max-width:200px;">{{ auth()->user()->email ?? '' }}</div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>

            <div class="win-window-controls" aria-hidden="true">
                <span class="win-wc"><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M2 6h8"/></svg></span>
                <span class="win-wc"><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.2"><rect x="2" y="2" width="8" height="8"/></svg></span>
                <span class="win-wc close"><svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.2"><path d="m2 2 8 8M10 2l-8 8"/></svg></span>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <nav class="win-tabstrip" aria-label="Tabs">
        @foreach($tabs as $tab)
            <a href="{{ route($tab['route']) }}" class="win-tab {{ request()->routeIs($tab['match']) ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">{!! $tab['icon'] !!}</svg>
                {{ $tab['label'] }}
            </a>
        @endforeach
    </nav>

    {{-- Command bar --}}
    <div class="win-commandbar">
        <button class="win-cmd win-burger" id="navToggle" type="button" aria-label="Navigasi" style="display:none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
        @yield('commands')
        <div class="win-cmd-spacer"></div>
        <button class="win-cmd" type="button" data-view-toggle title="Ubah tampilan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            <span class="d-none d-lg-inline">Tampilan</span>
        </button>
        <button class="win-cmd" type="button" data-refresh title="Segarkan">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-2.64-6.36"/><path d="M21 4v5h-5"/></svg>
            <span class="d-none d-lg-inline">Segarkan</span>
        </button>
    </div>

    {{-- Body --}}
    <div class="win-body">
        <aside class="win-navpane" id="winNav">
            <div class="win-navgroup-title">Beranda</div>
            <a href="{{ route('home') }}" class="win-navitem {{ request()->routeIs('home') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/></svg>
                Home
            </a>
            <a href="{{ route('data-api.index') }}" class="win-navitem {{ request()->routeIs('data-api.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h6l2 2h8v9a2 2 0 0 1-2 2H4z"/></svg>
                Data Upload
            </a>

            <div class="win-navgroup-title">Manajemen</div>
            <a href="{{ route('api-management.index') }}" class="win-navitem {{ request()->routeIs('api-management.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="15" r="4"/><path d="m10.85 12.15 7.4-7.4"/><path d="M18 5.5 21 8.5"/></svg>
                API Keys
            </a>
            <a href="{{ route('user-management.index') }}" class="win-navitem {{ request()->routeIs('user-management.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                Users
            </a>
        </aside>

        <div class="win-overlay" id="winOverlay"></div>

        <div class="win-content">
            <div class="win-addressbar">
                <div class="win-crumbbar">
                    <a href="{{ route('home') }}" class="win-crumb">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/></svg>
                        Home
                    </a>
                    @hasSection('breadcrumb_extra')
                        <span class="win-crumb-sep">›</span>
                        @yield('breadcrumb_extra')
                    @else
                        <span class="win-crumb-sep">›</span>
                        <span class="win-crumb current">@yield('page_title', 'Dashboard')</span>
                    @endif
                </div>

                <div class="win-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="search" placeholder="Telusuri…" data-table-search="#explorerList tbody" aria-label="Telusuri">
                </div>
            </div>

            <div class="win-scroll">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Status bar --}}
    <div class="win-statusbar">
        <span>@yield('status_left', 'Siap')</span>
        <span class="spacer"></span>
        <span>{{ config('app.name', 'API2NAS') }}</span>
    </div>
</div>

{{-- Toasts --}}
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

{{-- Confirm modal --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content border-0">
            <div class="modal-body p-4 text-center">
                <div class="win-tile-icon mx-auto mb-3" style="background:rgba(196,43,28,.12);color:#c42b1c;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/><path d="M12 9v4M12 17h.01"/></svg>
                </div>
                <h5 class="fw-semibold mb-1">Konfirmasi</h5>
                <p class="text-muted small mb-0" id="confirmModalMessage">Yakin ingin melanjutkan?</p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmModalOk">Ya, lanjutkan</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>