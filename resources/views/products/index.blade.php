@extends('layouts.app')
@section('title', 'Produk')

@section('content')
    <div class="topbar">
        <div>
            <h1>Produk</h1>
            <div class="sub">Kelola daftar produk & stok</div>
        </div>
        <button type="button" class="btn" onclick="openModal('prod-new')">+ Produk</button>
    </div>

    <form method="GET" class="between" style="margin-bottom:16px;max-width:360px">
        <input class="input" type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau SKU…">
        <button class="btn ghost">Cari</button>
    </form>

    <div class="card">
        <table>
            @php
                $sortUrl = fn ($col) => request()->fullUrlWithQuery([
                    'sort' => $col,
                    'direction' => ($sort === $col && $direction === 'asc') ? 'desc' : 'asc',
                    'page' => 1,
                ]);
                $arrow = fn ($col) => $sort === $col ? ($direction === 'asc' ? '↑' : '↓') : '';
            @endphp
            <thead>
                <tr>
                    <th><a class="th-sort" href="{{ $sortUrl('sku') }}">SKU <span class="th-arrow">{{ $arrow('sku') }}</span></a></th>
                    <th><a class="th-sort" href="{{ $sortUrl('name') }}">Nama <span class="th-arrow">{{ $arrow('name') }}</span></a></th>
                    <th>Kategori</th>
                    <th class="num"><a class="th-sort" href="{{ $sortUrl('price') }}"><span class="th-arrow">{{ $arrow('price') }}</span> Harga</a></th>
                    <th class="num"><a class="th-sort" href="{{ $sortUrl('stock') }}"><span class="th-arrow">{{ $arrow('stock') }}</span> Stok</a></th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td class="muted">{{ $product->sku }}</td>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->category ?: '—' }}</td>
                        <td class="num">{{ rupiah($product->price) }}</td>
                        <td class="num">{{ number_format($product->stock, 0, ',', '.') }}</td>
                        <td>
                            @if ($product->is_active)
                                <span class="badge">Aktif</span>
                            @else
                                <span class="badge" style="opacity:.6">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                <button type="button" class="btn ghost sm" onclick="openModal('prod-{{ $product->id }}')">Edit</button>
                                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn danger sm">Hapus</button>
                                </form>
                            </div>
                            @include('products._modal', [
                                'modalId' => 'prod-'.$product->id,
                                'title' => 'Edit Produk',
                                'action' => route('products.update', $product),
                                'method' => 'PUT',
                            ])
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted" style="text-align:center;padding:30px">Belum ada produk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $products->links() }}</div>

    @include('products._modal', [
        'modalId' => 'prod-new',
        'title' => 'Produk Baru',
        'action' => route('products.store'),
        'method' => 'POST',
        'product' => new \App\Models\Product(['is_active' => true]),
    ])

    @if ($errors->any() && old('_modal'))
        @push('scripts')
            <script>document.addEventListener('DOMContentLoaded', () => openModal(@json(old('_modal'))));</script>
        @endpush
    @endif
@endsection
