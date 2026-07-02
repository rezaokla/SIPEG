@extends('layouts.app')

@section('content')
<div class="card form-card">
    <h3>Edit User</h3>
    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf @method('PUT')
        <label>Nama</label>
        <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        <label>Role</label>
        <select name="role" required>
            <option value="admin" @selected(old('role', $user->role)==='admin')>Admin</option>
            <option value="pimpinan" @selected(old('role', $user->role)==='pimpinan')>Pimpinan</option>
            <option value="pegawai" @selected(old('role', $user->role)==='pegawai')>Pegawai</option>
        </select>
        <label>Password Baru (opsional)</label>
        <input type="password" name="password">
        <label>Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation">
        <div class="form-actions">
            <a href="{{ route('users.index') }}" class="btn secondary">Batal</a>
            <button type="submit" class="btn">Update</button>
        </div>
    </form>
</div>
@endsection
