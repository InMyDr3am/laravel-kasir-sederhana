@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="topbar">
        <div>
            <h1>Dashboard</h1>
            <div class="sub">{{ now()->translatedFormat('l, d F Y') }}</div>
        </div>
        <a href="{{ route('sales.create') }}" class="btn">+ Transaksi Baru</a>
    </div>

    <div class="grid stats">
        <div class="stat">
            <div class="k">Omzet Hari Ini</div>
            <div class="v">{{ rupiah($revenueToday) }}</div>
        </div>
        <div class="stat">
            <div class="k">Transaksi Hari Ini</div>
            <div class="v">{{ $countToday }}</div>
        </div>
        <div class="stat">
            <div class="k">Produk Aktif</div>
            <div class="v">{{ $productCount }}</div>
        </div>
    </div>

    <div class="grid" style="grid-template-columns: 1fr 1fr; margin-top: 20px;">
        <div class="card pad">
            <div class="between" style="margin-bottom: 10px;">
                <h3>Transaksi Terakhir</h3>
                <a href="{{ route('reports.index') }}" class="muted" style="font-size:13px">Lihat semua →</a>
            </div>
            @forelse ($recentSales as $sale)
                <div class="between" style="padding:10px 0;border-bottom:1px solid var(--line)">
                    <div>
                        <a href="{{ route('sales.show', $sale) }}"><strong>{{ $sale->invoice_no }}</strong></a>
                        <div class="muted" style="font-size:12px">{{ $sale->created_at->format('d/m H:i') }} · {{ $sale->cashier->name }}</div>
                    </div>
                    <div class="cart-sub">{{ rupiah($sale->total) }}</div>
                </div>
            @empty
                <p class="muted">Belum ada transaksi.</p>
            @endforelse
        </div>

        <div class="card pad">
            <h3 style="margin-bottom: 10px;">Stok Menipis</h3>
            @forelse ($lowStock as $product)
                <div class="between" style="padding:10px 0;border-bottom:1px solid var(--line)">
                    <div>
                        <strong>{{ $product->name }}</strong>
                        <div class="muted" style="font-size:12px">{{ $product->sku }}</div>
                    </div>
                    <span class="badge low">Sisa {{ number_format($product->stock, 0, ',', '.') }}</span>
                </div>
            @empty
                <p class="muted">Semua stok aman.</p>
            @endforelse
        </div>
    </div>
@endsection
