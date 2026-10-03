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
<section class="bg-light border-bottom">
    <div class="container py-5">
        <div class="row align-items-center g-4 py-lg-4">
            <div class="col-lg-7">
                <span class="badge text-bg-primary mb-3">REST API v1.0.0</span>
                <h1 class="display-5 fw-bold mb-3">API Image Storage &amp; Backup System</h1>
                <p class="lead text-muted mb-4">
                    Kirim file dalam bentuk base64, simpan sebagai backup, dan dapatkan
                    link publik yang bisa langsung dibuka. Semua akses tercatat rapi per API key.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    @auth
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-4">Buka Dashboard</a>
                        <a href="{{ route('api-management.index') }}" class="btn btn-outline-secondary btn-lg px-4">Manajemen API Key</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">Masuk</a>
                    @endauth
                    <a href="#quickstart" class="btn btn-link btn-lg px-3">Lihat Dokumentasi</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white font-monospace small d-flex justify-content-between">
                        <span>POST /api/data2nas</span>
                        <span class="text-success">201 Created</span>
                    </div>
                    <div class="card-body bg-black bg-opacity-75 text-light font-monospace small mb-0">
<pre class="mb-0 text-light">{
  "file": "&lt;base64&gt;",
  "nama_sistem": "foto.jpg",
  "id_tabel": "123",
  "tabel_name": "users",
  "apikey": "api_xxx"
}</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container py-5">
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary mb-3" style="width:56px;height:56px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/><path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/></svg>
                    </div>
                    <h2 class="h5 fw-semibold">Upload Base64</h2>
                    <p class="text-muted mb-0">Endpoint <code>POST /api/data2nas</code> menerima file ter-encode base64, termasuk format data-URI.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 text-success mb-3" style="width:56px;height:56px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16"><path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/><path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z"/></svg>
                    </div>
                    <h2 class="h5 fw-semibold">Link Publik</h2>
                    <p class="text-muted mb-0">Setiap file otomatis mendapat URL publik yang dapat langsung dibuka atau diunduh.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10 text-warning mb-3" style="width:56px;height:56px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2Zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7Z"/><path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1Z"/></svg>
                    </div>
                    <h2 class="h5 fw-semibold">Manajemen API Key</h2>
                    <p class="text-muted mb-0">Buat, aktif/nonaktifkan, dan pantau penggunaan tiap API key langsung dari dashboard.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="quickstart" class="bg-light border-top py-5">
    <div class="container">
        <div class="row justify-content-center text-center mb-4">
            <div class="col-lg-8">
                <h2 class="fw-bold">Mulai Cepat</h2>
                <p class="text-muted">Tiga endpoint utama untuk mengakses sistem.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h3 class="h6 fw-semibold">Health Check</h3>
                        <p class="small text-muted">Cek status sistem.</p>
                        <pre class="bg-dark text-light rounded p-3 small mb-0"><span class="text-warning">GET</span> /api/health</pre>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h3 class="h6 fw-semibold">Upload File</h3>
                        <p class="small text-muted">Kirim file sebagai base64.</p>
                        <pre class="bg-dark text-light rounded p-3 small mb-0"><span class="text-success">POST</span> /api/data2nas</pre>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h3 class="h6 fw-semibold">Ambil File</h3>
                        <p class="small text-muted">Ambil metadata &amp; URL berdasarkan ID.</p>
                        <pre class="bg-dark text-light rounded p-3 small mb-0"><span class="text-info">GET</span> /api/data2nas/{id}</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="py-4">
    <div class="container d-flex flex-wrap justify-content-between small text-muted">
        <span>&copy; {{ date('Y') }} {{ config('app.name', 'API2NAS') }}</span>
        <span>Laravel v{{ Illuminate\Foundation\Application::VERSION }} &middot; PHP v{{ PHP_VERSION }}</span>
    </div>
</footer>
@endsection