@extends('layouts.app')

@section('title', 'Pengguna')
@section('page_title', 'User Management')
@section('page_sub', 'Kelola akun yang dapat mengakses sistem')

@section('content')
<div class="app-card reveal">
    <div class="app-card-header">
        <h2 class="app-card-title">Daftar Pengguna</h2>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <div class="input-group">
                <span class="input-group-text bg-transparent"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg></span>
                <input type="search" id="userSearch" class="form-control" placeholder="Cari pengguna…" data-table-search="#userTable tbody">
                <button class="btn btn-outline-secondary" type="button" data-search-clear aria-label="Bersihkan">×</button>
            </div>
            <a href="{{ route('user-management.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                Buat Pengguna
            </a>
        </div>
    </div>

    <div class="app-table-wrap">
        <table class="app-table" id="userTable">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Email</th>
                    <th>Dibuat</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="app-avatar" style="width:34px;height:34px;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                <span class="cell-strong">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="cell-mono">{{ $user->email }}</td>
                        <td class="cell-mono">{{ $user->created_at->format('d M Y H:i') }}</td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('user-management.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('user-management.destroy', $user->id) }}" method="POST" data-confirm="Hapus pengguna “{{ $user->name }}”? Tindakan ini tidak dapat dibatalkan." data-confirm-button="Hapus Pengguna">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr data-empty-row>
                        <td colspan="4">
                            <div class="empty-state">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                <div>Belum ada pengguna.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="app-card-foot">{{ $users->links() }}</div>
    @endif
</div>
@endsection