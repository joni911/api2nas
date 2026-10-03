@extends('layouts.app')

@section('title', 'API Keys')
@section('page_title', 'API Keys')
@section('page_sub', 'Kelola kunci akses API Anda')

@section('content')
<div class="app-card reveal">
    <div class="app-card-header">
        <h2 class="app-card-title">Daftar API Key</h2>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <div class="input-group">
                <span class="input-group-text bg-transparent"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
                <input type="search" id="apiKeySearch" class="form-control" placeholder="Cari API key…" data-table-search="#apiKeyTable tbody">
                <button class="btn btn-outline-secondary" type="button" data-search-clear aria-label="Bersihkan">×</button>
            </div>
            <a href="{{ route('api-management.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                Buat API Key
            </a>
            <a href="{{ route('data-api.index') }}" class="btn btn-outline-secondary">Lihat Data</a>
        </div>
    </div>

    <div class="app-table-wrap">
        <table class="app-table" id="apiKeyTable">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>API Key</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($apiKeys as $key)
                    <tr>
                        <td class="cell-strong">
                            {{ $key->name }}
                            <div class="cell-mono">#{{ $key->id }}</div>
                        </td>
                        <td>
                            <div class="d-inline-flex align-items-center gap-1">
                                <span class="cell-mono">{{ \Illuminate\Support\Str::limit($key->api_key, 18) }}</span>
                                <button type="button" class="btn btn-sm btn-outline-secondary border-0" data-copy="{{ $key->api_key }}" title="Salin API key">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                </button>
                            </div>
                        </td>
                        <td>
                            <span class="pill {{ $key->is_active ? 'pill-success' : 'pill-danger' }}">
                                <span class="pill-dot"></span>{{ $key->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="cell-mono">{{ $key->created_at->format('d M Y H:i') }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('api-management.show', $key->id) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                                <a href="{{ route('api-management.edit', $key->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('api-management.destroy', $key->id) }}" method="POST" data-confirm="Hapus API key “{{ $key->name }}”? File terkait juga akan terhapus." data-confirm-button="Hapus API Key">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-empty-row>
                        <td colspan="5">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="15" r="4"/><path d="m10.85 12.15 7.4-7.4"/></svg>
                                <div>Belum ada API key. Buat yang pertama untuk mulai upload.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($apiKeys->hasPages())
        <div class="app-card-foot">{{ $apiKeys->links() }}</div>
    @endif
</div>
@endsection