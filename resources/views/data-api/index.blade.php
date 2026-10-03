@extends('layouts.app')

@section('title', 'Data Upload')
@section('page_title', 'Data Upload')
@section('page_sub', 'Semua file yang dikirim melalui API')

@section('content')
<div class="app-card reveal">
    <div class="app-card-header">
        <h2 class="app-card-title">Daftar File</h2>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <select class="form-select" style="width:auto;" data-table-filter="#dataTable tbody" aria-label="Filter API key">
                <option value="">Semua API Key</option>
                @foreach($apiKeys as $key)
                    <option value="{{ $key->id }}">{{ $key->name }}</option>
                @endforeach
            </select>
            <div class="input-group">
                <span class="input-group-text bg-transparent"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
                <input type="search" id="dataSearch" class="form-control" placeholder="Cari file…" data-table-search="#dataTable tbody">
                <button class="btn btn-outline-secondary" type="button" data-search-clear aria-label="Bersihkan">×</button>
            </div>
        </div>
    </div>

    <div class="app-table-wrap">
        <table class="app-table" id="dataTable">
            <thead>
                <tr>
                    <th>File</th>
                    <th>API Key</th>
                    <th>IP</th>
                    <th>Waktu</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($apiDatas as $data)
                    @php($isImage = (bool) preg_match('/\.(jpg|jpeg|png|gif|webp|bmp|svg)$/i', $data->nama_file))
                    <tr data-filter-value="{{ $data->api_id }}">
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-grid" style="width:40px;height:40px;border-radius:10px;overflow:hidden;flex:none;background:var(--app-surface-2);place-items:center;">
                                    @if($isImage)
                                        <img src="{{ $data->url }}" alt="" loading="lazy" style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--app-muted)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                                    @endif
                                </span>
                                <div class="min-w-0">
                                    <div class="cell-strong text-truncate" style="max-width:240px;">{{ $data->nama_file }}</div>
                                    <div class="cell-mono">#{{ $data->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="pill">{{ $data->apiKey->name ?? 'N/A' }}</span></td>
                        <td class="cell-mono">{{ $data->ip_address }}</td>
                        <td class="cell-mono">{{ $data->created_at->format('d M Y H:i') }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('data-api.show', $data->id) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-copy="{{ $data->url }}" title="Salin URL">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                </button>
                                <form action="{{ route('data-api.destroy', $data->id) }}" method="POST" data-confirm="Hapus file “{{ $data->nama_file }}”? File fisik juga akan dihapus." data-confirm-button="Hapus File">
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
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4"/><path d="m7 9 5-5 5 5"/><path d="M4 16v2.5A1.5 1.5 0 0 0 5.5 20h13a1.5 1.5 0 0 0 1.5-1.5V16"/></svg>
                                <div>Belum ada data. Kirim file lewat endpoint <span class="cell-mono">POST /api/data2nas</span>.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3 text-center text-muted small" data-search-empty hidden>Tidak ada file yang cocok dengan pencarian.</div>
    </div>

    @if(method_exists($apiDatas, 'hasPages') && $apiDatas->hasPages())
        <div class="app-card-foot">{{ $apiDatas->links() }}</div>
    @endif
</div>
@endsection