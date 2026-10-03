@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('commands')
    <a href="{{ route('api-management.create') }}" class="win-cmd primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
        API Key Baru
    </a>
    <div class="win-cmd-sep"></div>
    <a href="{{ route('data-api.index') }}" class="win-cmd">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h6l2 2h8v9a2 2 0 0 1-2 2H4z"/></svg>
        Data Upload
    </a>
    <a href="{{ route('user-management.index') }}" class="win-cmd">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        Pengguna
    </a>
@endsection

@section('status_left', $files.' item')

@section('content')
@php
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;
    $size = (float) $storageBytes;
    while ($size >= 1024 && $i < count($units) - 1) { $size /= 1024; $i++; }
    $storageHuman = round($size, ($size < 10 && $i > 0) ? 1 : 0).' '.$units[$i];
@endphp

<div class="mb-2 px-1 text-muted small fw-semibold">Akses cepat</div>
<div class="row g-2 mb-4">
    <div class="col-6 col-xl-3">
        <a href="{{ route('api-management.index') }}" class="win-tile reveal">
            <span class="win-tile-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="15" r="4"/><path d="m10.85 12.15 7.4-7.4"/><path d="M18 5.5 21 8.5"/></svg>
            </span>
            <span>
                <span class="win-stat-value" data-count="{{ $apiKeys }}">0</span>
                <span class="d-block text-muted small">API Keys</span>
            </span>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a href="{{ route('data-api.index') }}" class="win-tile reveal">
            <span class="win-tile-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h6l2 2h8v9a2 2 0 0 1-2 2H4z"/></svg>
            </span>
            <span>
                <span class="win-stat-value" data-count="{{ $files }}">0</span>
                <span class="d-block text-muted small">Total File</span>
            </span>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <div class="win-tile reveal">
            <span class="win-tile-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14a9 3 0 0 0 18 0V5"/><path d="M3 12a9 3 0 0 0 18 0"/></svg>
            </span>
            <span>
                <span class="win-stat-value">{{ $storageHuman }}</span>
                <span class="d-block text-muted small">Penyimpanan</span>
            </span>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <a href="{{ route('user-management.index') }}" class="win-tile reveal">
            <span class="win-tile-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            </span>
            <span>
                <span class="win-stat-value" data-count="{{ $users }}">0</span>
                <span class="d-block text-muted small">Pengguna</span>
            </span>
        </a>
    </div>
</div>

<div class="mb-2 px-1 text-muted small fw-semibold">File terbaru</div>
<div class="win-listwrap reveal">
    <table class="win-list" id="explorerList">
        <thead>
            <tr>
                <th>Nama</th>
                <th>API Key</th>
                <th>Waktu</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recent as $item)
                @php($isImage = (bool) preg_match('/\.(jpg|jpeg|png|gif|webp|bmp|svg)$/i', $item->nama_file))
                <tr>
                    <td>
                        <div class="win-filecell">
                            @if($isImage)
                                <img src="{{ $item->url }}" alt="" class="win-thumb" loading="lazy">
                            @else
                                <span class="win-fileicon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                                </span>
                            @endif
                            <span class="text-truncate" style="max-width:260px;" title="{{ $item->nama_file }}">{{ $item->nama_file }}</span>
                        </div>
                    </td>
                    <td><span class="pill">{{ $item->apiKey->name ?? 'N/A' }}</span></td>
                    <td class="cell-mono">{{ $item->created_at->diffForHumans() }}</td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="{{ $item->url }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">Buka</a>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-copy="{{ $item->url }}" title="Salin URL">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr data-empty-row>
                    <td colspan="4">
                        <div class="win-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h6l2 2h8v9a2 2 0 0 1-2 2H4z"/></svg>
                            <div>Folder ini kosong. Belum ada file yang diupload.</div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-3 text-center text-muted small" data-search-empty hidden>Tidak ada file yang cocok.</div>
</div>
@endsection