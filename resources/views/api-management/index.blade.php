@extends('layouts.app')

@section('title', 'API Keys')
@section('page_title', 'API Keys')

@section('commands')
    <a href="{{ route('api-management.create') }}" class="win-cmd primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
        Baru
    </a>
    <a href="{{ route('data-api.index') }}" class="win-cmd">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4"/><path d="m7 9 5-5 5 5"/><path d="M4 16v2.5A1.5 1.5 0 0 0 5.5 20h13a1.5 1.5 0 0 0 1.5-1.5V16"/></svg>
        View Data
    </a>
@endsection

@section('status_left', $apiKeys->total().' item')

@section('content')
<div class="win-listwrap reveal">
    <table class="win-list" id="explorerList">
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
                    <td>
                        <div class="win-filecell">
                            <span class="win-fileicon folder">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h6l2 2h8v9a2 2 0 0 1-2 2H4z"/></svg>
                            </span>
                            <span>
                                <span class="d-block" title="{{ $key->name }}">{{ $key->name }}</span>
                                <span class="cell-mono">#{{ $key->id }}</span>
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="d-inline-flex align-items-center gap-1">
                            <span class="cell-mono">{{ \Illuminate\Support\Str::limit($key->api_key, 20) }}</span>
                            <button type="button" class="btn btn-sm btn-outline-secondary border-0 py-0" data-copy="{{ $key->api_key }}" title="Salin API key">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            </button>
                        </div>
                    </td>
                    <td>
                        <span class="pill {{ $key->is_active ? 'pill-success' : 'pill-danger' }}">
                            <span class="pill-dot"></span>{{ $key->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="cell-mono">{{ $key->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="{{ route('api-management.show', $key->id) }}" class="btn btn-sm btn-outline-secondary">Buka</a>
                            <a href="{{ route('api-management.edit', $key->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('api-management.destroy', $key->id) }}" method="POST" data-confirm="Hapus API key “{{ $key->name }}”? File terkait juga akan terhapus." data-confirm-button="Hapus">
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
                        <div class="win-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h6l2 2h8v9a2 2 0 0 1-2 2H4z"/></svg>
                            <div>Folder ini kosong. Buat API key pertama Anda.</div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-3 text-center text-muted small" data-search-empty hidden>Tidak ada API key yang cocok.</div>
</div>

@if($apiKeys->hasPages())
    <div class="mt-3">{{ $apiKeys->links() }}</div>
@endif
@endsection