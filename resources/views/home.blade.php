@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_sub', 'Ringkasan aktivitas penyimpanan Anda')

@section('content')
@php
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;
    $size = (float) $storageBytes;
    while ($size >= 1024 && $i < count($units) - 1) { $size /= 1024; $i++; }
    $storageHuman = round($size, ($size < 10 && $i > 0) ? 1 : 0).' '.$units[$i];
@endphp

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card reveal">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="15" r="4"/><path d="m10.85 12.15 7.4-7.4"/><path d="M18 5.5 21 8.5"/></svg>
                </span>
            </div>
            <div class="stat-value" data-count="{{ $apiKeys }}">0</div>
            <div class="stat-label">API Keys</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card reveal">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                </span>
            </div>
            <div class="stat-value" data-count="{{ $files }}">0</div>
            <div class="stat-label">Total File</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card reveal">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14a9 3 0 0 0 18 0V5"/><path d="M3 12a9 3 0 0 0 18 0"/></svg>
                </span>
            </div>
            <div class="stat-value">{{ $storageHuman }}</div>
            <div class="stat-label">Penyimpanan</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card reveal">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/></svg>
                </span>
            </div>
            <div class="stat-value" data-count="{{ $users }}">0</div>
            <div class="stat-label">Pengguna</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="app-card h-100 reveal">
            <div class="app-card-header">
                <h2 class="app-card-title">Aksi Cepat</h2>
            </div>
            <div class="app-card-body d-grid gap-2">
                <a href="{{ route('api-management.create') }}" class="btn btn-primary d-flex align-items-center gap-2 py-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                    Buat API Key
                </a>
                <a href="{{ route('data-api.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 py-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4"/><path d="m7 9 5-5 5 5"/><path d="M4 16v2.5A1.5 1.5 0 0 0 5.5 20h13a1.5 1.5 0 0 0 1.5-1.5V16"/></svg>
                    Lihat Data Upload
                </a>
                <a href="{{ route('user-management.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 py-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    Kelola Pengguna
                </a>

                <div class="mt-2 pt-3 border-top">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="small text-muted">Status API</span>
                        <span class="pill pill-success"><span class="pill-dot"></span>Healthy</span>
                    </div>
                    <div class="small text-muted mt-2">
                        <span class="cell-mono">GET /api/health</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="app-card h-100 reveal">
            <div class="app-card-header">
                <h2 class="app-card-title">Upload Terbaru</h2>
                <a href="{{ route('data-api.index') }}" class="small">Lihat semua</a>
            </div>
            <div class="app-table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>File</th>
                            <th>API Key</th>
                            <th>Waktu</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent as $item)
                            <tr>
                                <td class="cell-strong text-truncate" style="max-width:220px;">{{ $item->nama_file }}</td>
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
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                                        <div>Belum ada file yang diupload.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection