@extends('layouts.app')

@section('title', 'Detail File')
@section('page_title', 'Detail File')
@section('page_sub', $apiData->nama_file)

@section('content')
<div class="row g-3">
    <div class="col-lg-7">
        <div class="app-card reveal">
            <div class="app-card-header">
                <h2 class="app-card-title">Pratinjau</h2>
                <a href="{{ route('data-api.index') }}" class="small">← Kembali</a>
            </div>
            <div class="app-card-body text-center">
                @if(preg_match('/\.(jpg|jpeg|png|gif|webp|bmp|svg)$/i', $apiData->nama_file))
                    <a href="{{ $apiData->url }}" target="_blank" rel="noopener">
                        <img src="{{ $apiData->url }}" alt="{{ $apiData->nama_file }}" class="img-fluid" style="max-height:460px;border-radius:var(--app-radius-sm);border:1px solid var(--app-border);">
                    </a>
                @else
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                        <div>Pratinjau tidak tersedia untuk tipe file ini.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="app-card h-100 reveal">
            <div class="app-card-header">
                <h2 class="app-card-title">Metadata</h2>
            </div>
            <div class="app-card-body">
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted fw-normal">ID</dt>
                    <dd class="col-7 cell-mono">#{{ $apiData->id }}</dd>

                    <dt class="col-5 text-muted fw-normal">Nama File</dt>
                    <dd class="col-7 cell-strong text-break">{{ $apiData->nama_file }}</dd>

                    <dt class="col-5 text-muted fw-normal">API Key</dt>
                    <dd class="col-7"><span class="pill">{{ $apiData->apiKey->name ?? 'N/A' }}</span></dd>

                    <dt class="col-5 text-muted fw-normal">IP Address</dt>
                    <dd class="col-7 cell-mono">{{ $apiData->ip_address }}</dd>

                    <dt class="col-5 text-muted fw-normal">ID Tabel</dt>
                    <dd class="col-7 cell-mono">{{ $apiData->id_tabel ?? '-' }}</dd>

                    <dt class="col-5 text-muted fw-normal">Nama Tabel</dt>
                    <dd class="col-7 cell-mono">{{ $apiData->tabel_name ?? '-' }}</dd>

                    <dt class="col-5 text-muted fw-normal">Dibuat</dt>
                    <dd class="col-7 cell-mono">{{ $apiData->created_at->format('d M Y H:i:s') }}</dd>
                </dl>

                <div class="mt-3">
                    <label class="form-label">URL Publik</label>
                    <div class="input-group">
                        <input type="text" class="form-control cell-mono" value="{{ $apiData->url }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" data-copy="{{ $apiData->url }}">Salin</button>
                    </div>
                </div>
            </div>
            <div class="app-card-foot d-flex flex-wrap gap-2">
                <a href="{{ $apiData->url }}" target="_blank" rel="noopener" class="btn btn-primary btn-sm">Buka File</a>
                <a href="{{ route('data-api.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
                <form action="{{ route('data-api.destroy', $apiData->id) }}" method="POST" class="ms-auto" data-confirm="Hapus file “{{ $apiData->nama_file }}”? File fisik juga akan dihapus." data-confirm-button="Hapus File">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection