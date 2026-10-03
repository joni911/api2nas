<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#f6f7f9" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#08090a" media="(prefers-color-scheme: dark)">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel')) - {{ config('app.name', 'API2NAS') }}</title>

    <meta name="description" content="@yield('meta_description', 'API image storage system for backup and public file access')">
    <meta name="robots" content="noindex, nofollow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Prevent theme flash: apply stored theme before first paint --}}
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
    <div class="app-shell">
        {{-- Sidebar --}}
        <aside class="app-sidebar" id="appSidebar">
            <a href="{{ url('/') }}" class="app-brand">
                <span class="app-logo">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg>
                </span>
                <span>{{ config('app.name', 'API2NAS') }}</span>
            </a>

            <nav class="app-nav">
                <div class="app-nav-label">Menu</div>
                <a href="{{ route('home') }}" class="app-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('api-management.index') }}" class="app-nav-link {{ request()->routeIs('api-management.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="15" r="4"/><path d="m10.85 12.15 7.4-7.4"/><path d="M18 5.5 21 8.5"/><path d="M15.5 8 18 10.5"/></svg>
                    <span>API Keys</span>
                </a>
                <a href="{{ route('data-api.index') }}" class="app-nav-link {{ request()->routeIs('data-api.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4"/><path d="m7 9 5-5 5 5"/><path d="M4 16v2.5A1.5 1.5 0 0 0 5.5 20h13a1.5 1.5 0 0 0 1.5-1.5V16"/></svg>
                    <span>Data Upload</span>
                </a>
            </nav>

            <div class="app-sidebar-foot">
                <a href="{{ route('user-management.index') }}" class="app-nav-link {{ request()->routeIs('user-management.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span>Users</span>
                </a>
                <div class="app-userchip mt-2">
                    <span class="app-avatar">{{ strtoupper(substr(auth()->user()->name ?? '?', 0, 1)) }}</span>
                    <span class="text-truncate small">{{ auth()->user()->name ?? 'Guest' }}</span>
                </div>
            </div>
        </aside>

        <div class="app-overlay" id="appOverlay"></div>

        {{-- Main --}}
        <div class="app-main">
            <header class="app-topbar">
                <button class="icon-btn app-burger" id="sidebarToggle" type="button" aria-label="Buka menu">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
                <div class="me-auto min-w-0">
                    <h1 class="app-page-title text-truncate">@yield('page_title', 'Dashboard')</h1>
                    @hasSection('page_sub')
                        <p class="app-page-sub text-truncate">@yield('page_sub')</p>
                    @endif
                </div>

                <button class="icon-btn" type="button" data-theme-toggle aria-label="Ganti tema" title="Ganti tema">
                    <svg class="theme-icon-light" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
                </button>

                <div class="dropdown">
                    <button class="icon-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menu pengguna">
                        <span class="app-avatar" style="width:26px;height:26px;font-size:.72rem;">{{ strtoupper(substr(auth()->user()->name ?? '?', 0, 1)) }}</span>
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
            </header>

            <main class="app-content">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Toasts --}}
    <div class="app-toasts" id="appToastContainer" aria-live="polite">
        @foreach (['success' => 'success', 'status' => 'success', 'error' => 'danger', 'info' => 'info'] as $key => $variant)
            @if (session()->has($key))
                <div class="app-toast {{ $variant }}" data-autodismiss>
                    <span class="dot"></span>
                    <div class="small">{{ session($key) }}</div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- Confirm modal --}}
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius:var(--app-radius);">
                <div class="modal-body p-4 text-center">
                    <div class="stat-icon mx-auto mb-3" style="background:rgba(239,68,68,.12);color:#ef4444;">
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