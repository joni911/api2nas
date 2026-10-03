@extends('layouts.app')

@section('title', 'Pengguna')
@section('page_title', 'Users')

@section('commands')
    <a href="{{ route('user-management.create') }}" class="win-cmd primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
        Pengguna Baru
    </a>
@endsection

@section('status_left', $users->total().' item')

@section('content')
<div class="win-listwrap reveal">
    <table class="win-list" id="explorerList">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Dibuat</th>
                <th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        <div class="win-filecell">
                            <span style="display:grid;place-items:center;width:28px;height:28px;border-radius:50%;background:var(--win-accent);color:var(--win-accent-fg);font-size:.72rem;font-weight:600;flex:none;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            <span>{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="cell-mono">{{ $user->email }}</td>
                    <td class="cell-mono">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="{{ route('user-management.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('user-management.destroy', $user->id) }}" method="POST" data-confirm="Hapus pengguna “{{ $user->name }}”?" data-confirm-button="Hapus">
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
                        <div class="win-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            <div>Belum ada pengguna.</div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-3 text-center text-muted small" data-search-empty hidden>Tidak ada pengguna yang cocok.</div>
</div>

@if($users->hasPages())
    <div class="mt-3">{{ $users->links() }}</div>
@endif
@endsection