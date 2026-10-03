@extends('layouts.app')

@section('title', 'Detail API Key')
@section('page_title', 'Detail API Key')
@section('page_sub', $apiKey->name)

@section('content')
<div class="row g-3 mb-4">
    <div class="col-lg-5">
        <div class="app-card h-100 reveal">
            <div class="app-card-header">
                <h2 class="app-card-title">Informasi</h2>
                <span class="pill {{ $apiKey->is_active ? 'pill-success' : 'pill-danger' }}">
                    <span class="pill-dot"></span>{{ $apiKey->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
            <div class="app-card-body">
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted fw-normal">ID</dt>
                    <dd class="col-7 cell-mono">#{{ $apiKey->id }}</dd>
                    <dt class="col-5 text-muted fw-normal">Nama</dt>
                    <dd class="col-7 cell-strong">{{ $apiKey->name }}</dd>
                    <dt class="col-5 text-muted fw-normal">Total File</dt>
                    <dd class="col-7">{{ $apiKey->api_data_count }}</dd>
                    <dt class="col-5 text-muted fw-normal">Dibuat</dt>
                    <dd class="col-7 cell-mono">{{ $apiKey->created_at->format('d M Y H:i') }}</dd>
                </dl>

                <div class="mt-3">
                    <label class="form-label">API Key</label>
                    <div class="input-group">
                        <input type="text" class="form-control cell-mono" value="{{ $apiKey->api_key }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" data-copy="{{ $apiKey->api_key }}">Salin</button>
                    </div>
                </div>
            </div>
            <div class="app-card-foot d-flex gap-2">
                <a href="{{ route('api-management.index') }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
                <a href="{{ route('api-management.edit', $apiKey->id) }}" class="btn btn-primary btn-sm">Edit</a>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="app-card h-100 reveal">
            <div class="app-card-header">
                <h2 class="app-card-title">File dari API Key ini</h2>
            </div>
            <div class="app-table-wrap">
                <table class="app-table" id="apiFileTable">
                    <thead>
                        <tr>
                            <th>File</th>
                            <th>IP</th>
                            <th>Waktu</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($apiData as $data)
                            <tr>
                                <td class="cell-strong text-truncate" style="max-width:200px;">{{ $data->nama_file }}</td>
                                <td class="cell-mono">{{ $data->ip_address }}</td>
                                <td class="cell-mono">{{ $data->created_at->format('d M Y H:i') }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ $data->url }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">Buka</a>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-copy="{{ $data->url }}">Salin URL</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                                        <div>Belum ada file untuk API key ini.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($apiData->hasPages())
                <div class="app-card-foot">{{ $apiData->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection