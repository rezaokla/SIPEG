<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pegawaiku' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <h2>Pegawaiku</h2>
            @auth
            <div class="user-box">
                <strong>{{ auth()->user()->nama }}</strong>
                <small>{{ auth()->user()->role }}</small>
            </div>
            @endauth
            <nav>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('users.index') }}">Users</a>
                    @endif
                    @if(in_array(auth()->user()->role, ['admin','pimpinan']))
                        <a href="{{ route('pegawai.index') }}">Pegawai</a>
                        <a href="{{ route('anggaran.index') }}">Anggaran</a>
                        <a href="{{ route('laporan.index') }}">Laporan</a>
                    @endif
                    <a href="{{ route('cuti.index') }}">Cuti</a>
                    <a href="{{ route('program.index') }}">Program</a>
                    <a href="{{ route('prestasi.index') }}">Prestasi</a>
                    <a href="{{ route('profil.index') }}">Profil</a>
                @endauth
            </nav>
        </aside>
        <main class="content">
            <header class="topbar">
                <h1>{{ $title ?? 'Pegawaiku' }}</h1>
                @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn danger">Logout</button>
                </form>
                @endauth
            </header>
            @if(session('success'))
                <div class="alert success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert error">{{ $errors->first() }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
