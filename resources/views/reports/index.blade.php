@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
    <div class="topbar">
        <div>
            <h1>Laporan Penjualan</h1>
            <div class="sub">Rekap transaksi per periode</div>
        </div>
        <a href="{{ route('reports.export', ['from' => $from, 'to' => $to]) }}" class="btn ghost">⬇ Export CSV</a>
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

    <div class="grid" style="grid-template-columns:1fr 1fr;margin-bottom:20px">
        <div class="card pad">
            <h3 style="margin-bottom:10px">Produk Terlaris</h3>
            <table>
                <thead>
                    <tr><th>Produk</th><th class="num">Terjual</th><th class="num">Omzet</th></tr>
                </thead>
                <tbody>
                    @forelse ($topProducts as $p)
                        <tr>
                            <td><strong>{{ $p->name }}</strong></td>
                            <td class="num">{{ $p->qty }}</td>
                            <td class="num">{{ rupiah($p->revenue) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="muted" style="text-align:center;padding:20px">Belum ada penjualan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card pad">
            <h3 style="margin-bottom:10px">Rekap per Kasir</h3>
            <table>
                <thead>
                    <tr><th>Kasir</th><th class="num">Transaksi</th><th class="num">Omzet</th></tr>
                </thead>
                <tbody>
                    @forelse ($cashierRecap as $row)
                        <tr>
                            <td><strong>{{ $row->cashier?->name ?? '—' }}</strong></td>
                            <td class="num">{{ $row->count }}</td>
                            <td class="num">{{ rupiah($row->revenue) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="muted" style="text-align:center;padding:20px">Belum ada penjualan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th>Metode</th>
                    <th class="num">Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr @if ($sale->isCancelled()) style="opacity:.55" @endif>
                        <td>
                            <strong>{{ $sale->invoice_no }}</strong>
                            @if ($sale->isCancelled()) <span class="badge low">Batal</span> @endif
                        </td>
                        <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $sale->cashier->name }}</td>
                        <td><span class="badge">{{ $sale->paymentLabel() }}</span></td>
                        <td class="num">
                            @if ($sale->isCancelled())
                                <s>{{ rupiah($sale->total) }}</s>
                            @else
                                {{ rupiah($sale->total) }}
                            @endif
                        </td>
                        <td class="right"><a href="{{ route('sales.show', $sale) }}" class="btn ghost sm">Struk</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted" style="text-align:center;padding:30px">Tidak ada transaksi pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $sales->links() }}</div>
@endsection
