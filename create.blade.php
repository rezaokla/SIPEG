@extends('layouts.app')

@section('content')
<div class="card form-card">
    <h3>Tambah User</h3>
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <label>Nama</label>
        <input type="text" name="nama" value="{{ old('nama') }}" required>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
        <label>Role</label>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="pimpinan">Pimpinan</option>
            <option value="pegawai">Pegawai</option>
        </select>
        <label>Password</label>
        <input type="password" name="password" required>
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required>
        <div class="form-actions">
            <a href="{{ route('users.index') }}" class="btn secondary">Batal</a>
            <button type="submit" class="btn">Simpan</button>
        </div>
    </form>
</div>
@endsection
