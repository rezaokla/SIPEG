@extends('layouts.app')

@section('content')
<div class="card detail-card">
    <h3>Detail User</h3>
    <div class="detail-grid">
        <div><strong>Nama</strong><p>{{ $user->nama }}</p></div>
        <div><strong>Email</strong><p>{{ $user->email }}</p></div>
        <div><strong>Role</strong><p>{{ $user->role }}</p></div>
        <div><strong>Dibuat</strong><p>{{ $user->created_at?->format('d M Y H:i') }}</p></div>
    </div>
    <div class="form-actions">
        <a href="{{ route('users.index') }}" class="btn secondary">Kembali</a>
        <a href="{{ route('users.edit', $user) }}" class="btn">Edit</a>
    </div>
</div>
@endsection
