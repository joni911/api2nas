@extends('layouts.app')

@section('title', 'Data Upload')
@section('page_title', 'Data Upload')

@section('commands')
    <span class="win-cmd" style="cursor:default;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M6 12h12M10 18h4"/></svg>
        Filter
    </span>
    <select class="form-select" style="width:auto;min-height:34px;" data-table-filter="#explorerList tbody" aria-label="Filter API key">
        <option value="">Semua API Key</option>
        @foreach($apiKeys as $key)
            <option value="{{ $key->id }}">{{ $key->name }}</option>
        @endforeach
    </select>
@endsection

@section('status_left', $apiDatas->total().' item')

@section('content')
<div class="win-listwrap reveal">
    <table class="win-list" id="explorerList">
        <thead>
            <tr>
                <th>Nama</th>
                <th>API Key</th>
                <th>IP</th>
                <th>Tanggal</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($apiDatas as $data)
                @php($isImage = (bool) preg_match('/\.(jpg|jpeg|png|gif|webp|bmp|svg)$/i', $data->nama_file))
                <tr data-filter-value="{{ $data->api_id }}">
                    <td>
                        <div class="win-filecell">
                            @if($isImage)
                                <img src="{{ $data->url }}" alt="" class="win-thumb" loading="lazy">
                            @else
                                <span class="win-fileicon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                                </span>
                            @endif
                            <span class="text-truncate" style="max-width:260px;" title="{{ $data->nama_file }}">{{ $data->nama_file }}</span>
                        </div>
                    </td>
                    <td><span class="pill">{{ $data->apiKey->name ?? 'N/A' }}</span></td>
                    <td class="cell-mono">{{ $data->ip_address }}</td>
                    <td class="cell-mono">{{ $data->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="{{ route('data-api.show', $data->id) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-copy="{{ $data->url }}" title="Salin URL">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            </button>
                            <form action="{{ route('data-api.destroy', $data->id) }}" method="POST" data-confirm="Hapus file “{{ $data->nama_file }}”? File fisik juga akan dihapus." data-confirm-button="Hapus">
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
                            <div>Folder ini kosong. Kirim file lewat <span class="cell-mono">POST /api/data2nas</span>.</div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-3 text-center text-muted small" data-search-empty hidden>Tidak ada file yang cocok.</div>
</div>

@if(method_exists($apiDatas, 'hasPages') && $apiDatas->hasPages())
    <div class="mt-3">{{ $apiDatas->links() }}</div>
@endif
@endsection