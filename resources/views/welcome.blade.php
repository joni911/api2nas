@extends('layouts.guest')

@section('title', 'Backup & Public File API')
@section('meta_description', 'API2NAS adalah API penyimpanan file serbaguna: upload file sebagai base64 dan dapatkan link publik untuk melihatnya. Dilengkapi manajemen API key dan pelacakan penggunaan.')
@section('meta_keywords', 'api image storage, file backup, cloud storage, api upload, base64 upload, file hosting')

@push('seo')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebApplication",
    "name": "API2NAS",
    "description": "Secure API Image Storage & Backup System",
    "url": "{{ url('/') }}",
    "applicationCategory": "StorageApplication",
    "operatingSystem": "Web",
    "offers": {
        "@@type": "Offer",
        "price": "0",
        "priceCurrency": "USD"
    }
}
</script>
@endpush

@section('content')
<section class="landing-hero">
    <div class="container py-5">
        <div class="row align-items-center g-4 py-lg-4">
            <div class="col-lg-7">
                <span class="landing-eyebrow mb-3">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m13 2-3 7h6l-3 7"/></svg>
                    REST API v1.0.0
                </span>
                <h1 class="landing-title mb-3">API Image Storage &amp; Backup System</h1>
                <p class="lead text-win-muted mb-4">
                    Kirim file dalam bentuk base64, simpan sebagai backup, dan dapatkan
                    link publik yang bisa langsung dibuka. Semua akses tercatat rapi per API key.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    @auth
                        <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2">Buka Dashboard</a>
                        <a href="{{ route('api-management.index') }}" class="btn btn-outline-secondary px-4 py-2">Manajemen API Key</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary px-4 py-2">Masuk</a>
                    @endauth
                    <a href="#quickstart" class="btn btn-link px-3 py-2">Lihat Dokumentasi</a>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="terminal">
                    <div class="terminal-bar">
                        <span class="dots"><i style="background:#ff5f57;"></i><i style="background:#febc2e;"></i><i style="background:#28c840;"></i></span>
                        <span>POST /api/data2nas</span>
                        <span style="color:#6ee7b7;">201 Created</span>
                    </div>
                    <pre><span class="tok-post">POST</span> /api/data2nas
Content-Type: application/json

{
  <span class="tok-key">"file"</span>: <span class="tok-str">"&lt;base64&gt;"</span>,
  <span class="tok-key">"nama_sistem"</span>: <span class="tok-str">"foto.jpg"</span>,
  <span class="tok-key">"id_tabel"</span>: <span class="tok-str">"123"</span>,
  <span class="tok-key">"tabel_name"</span>: <span class="tok-str">"users"</span>,
  <span class="tok-key">"apikey"</span>: <span class="tok-str">"api_xxx"</span>
}</pre>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="landing-section py-5">
    <div class="container">
        <div class="row g-3 text-center">
            <div class="col-md-4">
                <div class="win-listwrap h-100 p-4 text-center">
                    <span class="win-tile-icon mx-auto mb-3">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4"/><path d="m7 9 5-5 5 5"/><path d="M4 16v2.5A1.5 1.5 0 0 0 5.5 20h13a1.5 1.5 0 0 0 1.5-1.5V16"/></svg>
                    </span>
                    <h2 class="h6 fw-semibold mb-2">Upload Base64</h2>
                    <p class="text-win-muted small mb-0">Endpoint <code class="inline">POST /api/data2nas</code> menerima file ter-encode base64, termasuk format data-URI.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="win-listwrap h-100 p-4 text-center">
                    <span class="win-tile-icon green mx-auto mb-3">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.5.5l3-3a5 5 0 0 0-7-7l-1.7 1.7"/><path d="M14 11a5 5 0 0 0-7.5-.5l-3 3a5 5 0 0 0 7 7l1.7-1.7"/></svg>
                    </span>
                    <h2 class="h6 fw-semibold mb-2">Link Publik</h2>
                    <p class="text-win-muted small mb-0">Setiap file otomatis mendapat URL publik yang dapat langsung dibuka atau diunduh.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="win-listwrap h-100 p-4 text-center">
                    <span class="win-tile-icon amber mx-auto mb-3">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="15" r="4"/><path d="m10.85 12.15 7.4-7.4"/><path d="M18 5.5 21 8.5"/></svg>
                    </span>
                    <h2 class="h6 fw-semibold mb-2">Manajemen API Key</h2>
                    <p class="text-win-muted small mb-0">Buat, aktif/nonaktifkan, dan pantau penggunaan tiap API key langsung dari dashboard.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="quickstart" class="landing-section alt py-5">
    <div class="container">
        <div class="row justify-content-center text-center mb-4">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-1">Mulai Cepat</h2>
                <p class="text-win-muted mb-0">Tiga endpoint utama untuk mengakses sistem.</p>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="win-listwrap h-100 p-4">
                    <h3 class="h6 fw-semibold mb-1">Health Check</h3>
                    <p class="small text-win-muted">Cek status sistem.</p>
                    <div class="terminal"><pre class="mb-0"><span class="tok-get">GET</span> /api/health</pre></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="win-listwrap h-100 p-4">
                    <h3 class="h6 fw-semibold mb-1">Upload File</h3>
                    <p class="small text-win-muted">Kirim file sebagai base64.</p>
                    <div class="terminal"><pre class="mb-0"><span class="tok-post">POST</span> /api/data2nas</pre></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="win-listwrap h-100 p-4">
                    <h3 class="h6 fw-semibold mb-1">Ambil File</h3>
                    <p class="small text-win-muted">Ambil metadata &amp; URL berdasarkan ID.</p>
                    <div class="terminal"><pre class="mb-0"><span class="tok-get">GET</span> /api/data2nas/{id}</pre></div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="landing-footer py-4">
    <div class="container d-flex flex-wrap justify-content-between small">
        <span>&copy; {{ date('Y') }} {{ config('app.name', 'API2NAS') }}</span>
        <span>Laravel v{{ Illuminate\Foundation\Application::VERSION }} &middot; PHP v{{ PHP_VERSION }}</span>
    </div>
</footer>
@endsection