<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kasir') — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <div class="brand">
            Kasir
            <small>{{ config('app.name') }}</small>
        </div>

        @php $isAdmin = auth()->user()->isAdmin(); @endphp
        <nav class="nav">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('sales.create') }}" class="{{ request()->routeIs('sales.create') ? 'active' : '' }}">Penjualan</a>
            <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">Laporan</a>

            @if ($isAdmin)
                <div class="nav-label">Admin</div>
                <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Produk</a>
                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">User</a>
            @endif
        </nav>

        <div class="sidebar-foot">
            <div class="who">
                <strong>{{ auth()->user()->name }}</strong>
                <span>{{ auth()->user()->role }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn ghost sm block" style="color:#fff;border-color:rgba(255,255,255,.2)">Keluar</button>
            </form>
        </div>
    </aside>

    <main class="main">
        @if (session('status'))
            <div class="alert ok">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="alert err">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>
<script>
    function openModal(id) {
        document.getElementById(id)?.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(target) {
        const m = typeof target === 'string' ? document.getElementById(target) : target.closest('.modal-backdrop');
        m?.classList.remove('open');
        document.body.style.overflow = '';
    }
    document.addEventListener('click', e => { if (e.target.classList.contains('modal-backdrop')) closeModal(e.target); });
    document.addEventListener('keydown', e => {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('.modal-backdrop.open').forEach(m => m.classList.remove('open'));
        document.body.style.overflow = '';
    });
</script>
@stack('scripts')
</body>
</html>
