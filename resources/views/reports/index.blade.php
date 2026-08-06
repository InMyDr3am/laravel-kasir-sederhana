@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
    <div class="topbar">
        <div>
            <h1>Laporan Penjualan</h1>
            <div class="sub">Rekap transaksi per periode</div>
        </div>
    </div>

    <form method="GET" class="card pad" style="margin-bottom:20px">
        <div style="display:flex;gap:16px;align-items:flex-end;flex-wrap:wrap">
            <div class="field" style="margin:0">
                <label for="from">Dari</label>
                <input class="input" type="date" id="from" name="from" value="{{ $from }}">
            </div>
            <div class="field" style="margin:0">
                <label for="to">Sampai</label>
                <input class="input" type="date" id="to" name="to" value="{{ $to }}">
            </div>
            <button class="btn">Terapkan</button>
        </div>
    </form>

    <div class="grid stats" style="margin-bottom:20px">
        <div class="stat">
            <div class="k">Total Omzet</div>
            <div class="v">{{ rupiah($revenue) }}</div>
        </div>
        <div class="stat">
            <div class="k">Jumlah Transaksi</div>
            <div class="v">{{ $count }}</div>
        </div>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th class="num">Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td><strong>{{ $sale->invoice_no }}</strong></td>
                        <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $sale->cashier->name }}</td>
                        <td class="num">{{ rupiah($sale->total) }}</td>
                        <td class="right"><a href="{{ route('sales.show', $sale) }}" class="btn ghost sm">Struk</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted" style="text-align:center;padding:30px">Tidak ada transaksi pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $sales->links() }}</div>
@endsection
