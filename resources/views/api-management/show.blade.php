@extends('layouts.app')

@section('title', 'Detail API Key')
@section('page_title', $apiKey->name)

@section('breadcrumb_extra')
    <a href="{{ route('api-management.index') }}" class="win-crumb">API Keys</a>
    <span class="win-crumb-sep">›</span>
    <span class="win-crumb current">{{ $apiKey->name }}</span>
@endsection

@section('commands')
    <a href="{{ route('api-management.index') }}" class="win-cmd">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Kembali
    </a>
    <a href="{{ route('api-management.edit', $apiKey->id) }}" class="win-cmd primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
        Edit
    </a>
@endsection

@section('status_left', $apiKey->api_data_count.' item')

@section('content')
<div class="row g-3 mb-3">
    <div class="col-lg-5">
        <div class="win-listwrap p-3 h-100 reveal">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="win-filecell">
                    <span class="win-fileicon folder">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h6l2 2h8v9a2 2 0 0 1-2 2H4z"/></svg>
                    </span>
                    <strong>{{ $apiKey->name }}</strong>
                </span>
                <span class="pill {{ $apiKey->is_active ? 'pill-success' : 'pill-danger' }}">
                    <span class="pill-dot"></span>{{ $apiKey->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
            <dl class="row mb-0 small">
                <dt class="col-5 fw-normal text-muted">ID</dt>
                <dd class="col-7 cell-mono">#{{ $apiKey->id }}</dd>
                <dt class="col-5 fw-normal text-muted">Total File</dt>
                <dd class="col-7">{{ $apiKey->api_data_count }}</dd>
                <dt class="col-5 fw-normal text-muted">Dibuat</dt>
                <dd class="col-7 cell-mono">{{ $apiKey->created_at->format('d/m/Y H:i') }}</dd>
            </dl>
            <div class="mt-3">
                <label class="form-label">API Key</label>
                <div class="input-group">
                    <input type="text" class="form-control cell-mono" value="{{ $apiKey->api_key }}" readonly>
                    <button class="btn btn-outline-secondary" type="button" data-copy="{{ $apiKey->api_key }}">Salin</button>
                </div>
            </div>
            <form action="{{ route('api-management.destroy', $apiKey->id) }}" method="POST" class="mt-3" data-confirm="Hapus API key “{{ $apiKey->name }}”? File terkait juga akan terhapus." data-confirm-button="Hapus">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">Hapus API Key</button>
            </form>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="win-listwrap h-100 reveal">
            <table class="win-list" id="explorerList">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>IP</th>
                        <th>Waktu</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($apiData as $data)
                        <tr>
                            <td>
                                <div class="win-filecell">
                                    <span class="win-fileicon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                                    </span>
                                    <span class="text-truncate" style="max-width:220px;" title="{{ $data->nama_file }}">{{ $data->nama_file }}</span>
                                </div>
                            </td>
                            <td class="cell-mono">{{ $data->ip_address }}</td>
                            <td class="cell-mono">{{ $data->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ $data->url }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">Buka</a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-copy="{{ $data->url }}">Salin</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr data-empty-row>
                            <td colspan="4">
                                <div class="win-empty">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h6l2 2h8v9a2 2 0 0 1-2 2H4z"/></svg>
                                    <div>Folder ini kosong.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($apiData->hasPages())
    <div class="mt-3">{{ $apiData->links() }}</div>
@endif
@endsection