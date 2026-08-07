@extends('layouts.app')
@section('title', 'Struk '.$sale->invoice_no)

@php $canVoid = auth()->user()->isAdmin() || $sale->user_id === auth()->id(); @endphp
@section('content')
    <div class="topbar no-print">
        <div>
            <h1>{{ $sale->isCancelled() ? 'Transaksi Dibatalkan' : 'Transaksi Berhasil' }}</h1>
            <div class="sub">{{ $sale->invoice_no }}</div>
        </div>
        <a href="{{ route('sales.create') }}" class="btn">+ Transaksi Baru</a>
    </div>

    <div class="card pad receipt">
        <div class="rhead">
            <strong style="font-size:18px">{{ setting('store_name', config('app.name')) }}</strong>
            @if (setting('store_address'))
                <div class="muted" style="font-size:12px">{{ setting('store_address') }}</div>
            @endif
            @if (setting('store_phone'))
                <div class="muted" style="font-size:12px">{{ setting('store_phone') }}</div>
            @endif
            @if ($sale->isCancelled())
                <div class="badge low" style="margin-top:8px">DIBATALKAN</div>
            @endif
        </div>

        <div class="rrow"><span class="muted">No. Invoice</span><span>{{ $sale->invoice_no }}</span></div>
        <div class="rrow"><span class="muted">Tanggal</span><span>{{ $sale->created_at->format('d/m/Y H:i') }}</span></div>
        <div class="rrow"><span class="muted">Kasir</span><span>{{ $sale->cashier->name }}</span></div>

        <div class="ritems">
            @foreach ($sale->items as $item)
                <div class="rrow">
                    <span>{{ $item->name }} <span class="muted">×{{ $item->qty }}</span></span>
                    <span>{{ rupiah($item->subtotal) }}</span>
                </div>
                <div class="muted" style="font-size:12px;margin:-2px 0 6px">@ {{ rupiah($item->price) }}</div>
            @endforeach
        </div>

        <div class="rrow"><span class="muted">Subtotal</span><span>{{ rupiah($sale->subtotal) }}</span></div>
        @if ($sale->discount > 0)
            <div class="rrow"><span class="muted">Diskon</span><span>− {{ rupiah($sale->discount) }}</span></div>
        @endif
        <div class="rrow" style="font-weight:700;font-size:16px"><span>Total</span><span>{{ rupiah($sale->total) }}</span></div>
        <div class="rrow"><span class="muted">Metode</span><span>{{ $sale->paymentLabel() }}</span></div>
        <div class="rrow"><span class="muted">Bayar</span><span>{{ rupiah($sale->paid) }}</span></div>
        <div class="rrow"><span class="muted">Kembalian</span><span>{{ rupiah($sale->change) }}</span></div>

        <div style="text-align:center;margin-top:16px" class="muted">{{ setting('receipt_footer', 'Terima kasih 🙏') }}</div>
    </div>

    <div class="print-actions">
        <button onclick="window.print()" class="btn">Cetak Struk</button>
        <a href="{{ route('reports.index') }}" class="btn ghost">Ke Laporan</a>
        @if (! $sale->isCancelled() && $canVoid)
            <form method="POST" action="{{ route('sales.void', $sale) }}"
                  onsubmit="return confirm('Batalkan transaksi ini? Stok akan dikembalikan.')">
                @csrf
                <button type="submit" class="btn danger">Batalkan Transaksi</button>
            </form>
        @endif
    </div>
@endsection
