@extends('layouts.app')

@section('content')
<div class="card">
    <div class="toolbar">
        <form method="GET" class="filter-row">
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Cari nama, email, atau role">
            <select name="role">
                <option value="">Semua Role</option>
                <option value="admin" @selected(request('role')==='admin')>Admin</option>
                <option value="pimpinan" @selected(request('role')==='pimpinan')>Pimpinan</option>
                <option value="pegawai" @selected(request('role')==='pegawai')>Pegawai</option>
            </select>
            <button type="submit" class="btn">Filter</button>
        </form>
        <a href="{{ route('users.create') }}" class="btn">+ Tambah User</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->nama }}</td>
                <td>{{ $user->email }}</td>
                <td><span class="badge">{{ $user->role }}</span></td>
                <td>{{ $user->created_at?->format('d M Y') }}</td>
                <td class="actions">
                    <a href="{{ route('users.show', $user) }}">Detail</a>
                    <a href="{{ route('users.edit', $user) }}">Edit</a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="link-btn danger-text">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5">Belum ada data user.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-wrap">{{ $users->links() }}</div>
</div>
@endsection
