@extends('layouts.app')
@section('title', 'Struk '.$sale->invoice_no)

@section('content')
    <div class="topbar no-print">
        <div>
            <h1>Transaksi Berhasil</h1>
            <div class="sub">{{ $sale->invoice_no }}</div>
        </div>
        <a href="{{ route('sales.create') }}" class="btn">+ Transaksi Baru</a>
    </div>

    <div class="card pad receipt">
        <div class="rhead">
            <strong style="font-size:18px">{{ config('app.name') }}</strong>
            <div class="muted" style="font-size:12px">Struk Pembelian</div>
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

        <div class="rrow" style="font-weight:700;font-size:16px"><span>Total</span><span>{{ rupiah($sale->total) }}</span></div>
        <div class="rrow"><span class="muted">Bayar</span><span>{{ rupiah($sale->paid) }}</span></div>
        <div class="rrow"><span class="muted">Kembalian</span><span>{{ rupiah($sale->change) }}</span></div>

        <div style="text-align:center;margin-top:16px" class="muted">Terima kasih 🙏</div>
    </div>

    <div class="print-actions">
        <button onclick="window.print()" class="btn">Cetak Struk</button>
        <a href="{{ route('reports.index') }}" class="btn ghost">Ke Laporan</a>
    </div>
@endsection
