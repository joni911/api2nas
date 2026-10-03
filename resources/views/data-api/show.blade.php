@extends('layouts.app')

@section('title', 'Detail File')
@section('page_title', $apiData->nama_file)

@section('breadcrumb_extra')
    <a href="{{ route('data-api.index') }}" class="win-crumb">Data Upload</a>
    <span class="win-crumb-sep">›</span>
    <span class="win-crumb current text-truncate" style="max-width:240px;">{{ $apiData->nama_file }}</span>
@endsection

@section('commands')
    <a href="{{ route('data-api.index') }}" class="win-cmd">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Kembali
    </a>
    <a href="{{ $apiData->url }}" target="_blank" rel="noopener" class="win-cmd primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><path d="M15 3h6v6M10 14 21 3"/></svg>
        Buka
    </a>
    <button type="button" class="win-cmd" data-copy="{{ $apiData->url }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
        Salin URL
    </button>
@endsection

@section('status_left', '1 item dipilih')

@section('content')
<div class="row g-3">
    <div class="col-lg-7">
        <div class="win-listwrap p-3 text-center reveal">
            @if(preg_match('/\.(jpg|jpeg|png|gif|webp|bmp|svg)$/i', $apiData->nama_file))
                <a href="{{ $apiData->url }}" target="_blank" rel="noopener">
                    <img src="{{ $apiData->url }}" alt="{{ $apiData->nama_file }}" style="max-height:460px;max-width:100%;border-radius:var(--win-radius-sm);border:1px solid var(--win-border);">
                </a>
            @else
                <div class="win-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                    <div>Pratinjau tidak tersedia untuk tipe file ini.</div>
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-5">
        <div class="win-listwrap p-3 h-100 reveal">
            <h2 class="h6 fw-semibold mb-3">Properti</h2>
            <dl class="row mb-0 small">
                <dt class="col-5 fw-normal text-muted">ID</dt>
                <dd class="col-7 cell-mono">#{{ $apiData->id }}</dd>
                <dt class="col-5 fw-normal text-muted">Nama</dt>
                <dd class="col-7 text-break">{{ $apiData->nama_file }}</dd>
                <dt class="col-5 fw-normal text-muted">API Key</dt>
                <dd class="col-7"><span class="pill">{{ $apiData->apiKey->name ?? 'N/A' }}</span></dd>
                <dt class="col-5 fw-normal text-muted">IP Address</dt>
                <dd class="col-7 cell-mono">{{ $apiData->ip_address }}</dd>
                <dt class="col-5 fw-normal text-muted">ID Tabel</dt>
                <dd class="col-7 cell-mono">{{ $apiData->id_tabel ?? '-' }}</dd>
                <dt class="col-5 fw-normal text-muted">Nama Tabel</dt>
                <dd class="col-7 cell-mono">{{ $apiData->tabel_name ?? '-' }}</dd>
                <dt class="col-5 fw-normal text-muted">Dibuat</dt>
                <dd class="col-7 cell-mono">{{ $apiData->created_at->format('d/m/Y H:i:s') }}</dd>
            </dl>

            <form action="{{ route('data-api.destroy', $apiData->id) }}" method="POST" class="mt-3" data-confirm="Hapus file “{{ $apiData->nama_file }}”? File fisik juga akan dihapus." data-confirm-button="Hapus">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">Hapus File</button>
            </form>
        </div>
    </div>
</div>
@endsection