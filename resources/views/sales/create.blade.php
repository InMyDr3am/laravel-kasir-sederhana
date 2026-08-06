@extends('layouts.app')
@section('title', 'Penjualan')

@section('content')
    <div class="topbar">
        <div>
            <h1>Penjualan</h1>
            <div class="sub">Pilih produk untuk menambah ke keranjang</div>
        </div>
    </div>

    @error('items') <div class="alert err">{{ $message }}</div> @enderror
    @error('paid') <div class="alert err">{{ $message }}</div> @enderror

    <div class="pos">
        <div>
            <input class="input" id="search" placeholder="Cari produk…" style="margin-bottom:14px" autofocus>
            <div class="pos-products" id="productGrid">
                @forelse ($products as $p)
                    <button type="button" class="pcard"
                            data-id="{{ $p->id }}"
                            data-name="{{ $p->name }}"
                            data-price="{{ $p->price }}"
                            data-stock="{{ $p->stock }}"
                            data-search="{{ Str::lower($p->name.' '.$p->sku.' '.$p->category) }}"
                            onclick="addToCart(this.dataset)">
                        <div class="pn">{{ $p->name }}</div>
                        <div class="pc">{{ $p->category ?: $p->sku }}</div>
                        <div class="pp">{{ rupiah($p->price) }}</div>
                        <div class="ps">Stok {{ $p->stock }}</div>
                    </button>
                @empty
                    <p class="muted">Tidak ada produk siap jual (cek stok / status aktif).</p>
                @endforelse
            </div>
        </div>

        <div class="card pad cart">
            <h3 style="margin-bottom:10px">Keranjang</h3>
            <div class="cart-items" id="cartItems">
                <div class="cart-empty" id="cartEmpty">Keranjang kosong</div>
            </div>

            <div class="totals">
                <div class="line grand"><span>Total</span><span id="grandTotal">Rp 0</span></div>
            </div>

            <form method="POST" action="{{ route('sales.store') }}" id="checkoutForm" style="margin-top:14px">
                @csrf
                <div id="itemInputs"></div>
                <div class="field">
                    <label for="paid">Uang Bayar (Rp)</label>
                    <input class="input" type="number" min="0" id="paid" name="paid" value="0" oninput="renderChange()">
                </div>
                <div class="totals" style="margin-top:0;border-top:none;padding-top:0">
                    <div class="line"><span>Kembalian</span><span id="changeView">Rp 0</span></div>
                </div>
                <div style="display:flex;gap:8px;margin-top:6px">
                    <button type="button" class="btn ghost sm" onclick="clearCart()">Kosongkan</button>
                    <button type="submit" class="btn block" id="payBtn" disabled>Bayar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
const cart = new Map();

function addToCart(ds) {
    const id = parseInt(ds.id, 10);
    const p = { id, name: ds.name, price: parseInt(ds.price, 10), stock: parseInt(ds.stock, 10) };
    const line = cart.get(id) || { ...p, qty: 0 };
    if (line.qty + 1 > p.stock) { alert('Stok ' + p.name + ' tidak cukup.'); return; }
    line.qty += 1;
    cart.set(id, line);
    render();
}

function setQty(id, delta) {
    const line = cart.get(id);
    if (!line) return;
    const next = line.qty + delta;
    if (next <= 0) { cart.delete(id); }
    else if (next > line.stock) { alert('Stok tidak cukup.'); return; }
    else { line.qty = next; }
    render();
}

function removeLine(id) { cart.delete(id); render(); }
function clearCart() { cart.clear(); render(); }

function render() {
    const wrap = document.getElementById('cartItems');
    const inputs = document.getElementById('itemInputs');
    wrap.innerHTML = '';
    inputs.innerHTML = '';
    let total = 0, i = 0;

    if (cart.size === 0) {
        wrap.innerHTML = '<div class="cart-empty">Keranjang kosong</div>';
    }

    for (const line of cart.values()) {
        const sub = line.price * line.qty;
        total += sub;
        const row = document.createElement('div');
        row.className = 'cart-row';
        row.innerHTML = `
            <div>
                <div class="cn">${escapeHtml(line.name)}</div>
                <div class="cp">${formatRp(line.price)}</div>
            </div>
            <div class="right">
                <div class="qty">
                    <button type="button" onclick="setQty(${line.id}, -1)">−</button>
                    <span>${line.qty}</span>
                    <button type="button" onclick="setQty(${line.id}, 1)">+</button>
                </div>
                <div class="cart-sub" style="margin-top:4px">${formatRp(sub)}</div>
                <button type="button" class="link-x" onclick="removeLine(${line.id})">hapus</button>
            </div>`;
        wrap.appendChild(row);

        inputs.insertAdjacentHTML('beforeend',
            `<input type="hidden" name="items[${i}][product_id]" value="${line.id}">
             <input type="hidden" name="items[${i}][qty]" value="${line.qty}">`);
        i++;
    }

    document.getElementById('grandTotal').textContent = formatRp(total);
    document.getElementById('payBtn').disabled = cart.size === 0;
    window._total = total;
    renderChange();
}

function renderChange() {
    const paid = parseInt(document.getElementById('paid').value || '0', 10);
    const change = paid - (window._total || 0);
    const el = document.getElementById('changeView');
    el.textContent = formatRp(change);
    el.className = change < 0 ? 'change-neg' : 'change-pos';
}

function formatRp(n) {
    return 'Rp ' + Math.round(n).toLocaleString('id-ID');
}
function escapeHtml(s) {
    return s.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}

document.getElementById('search').addEventListener('input', function (e) {
    const q = e.target.value.toLowerCase().trim();
    document.querySelectorAll('#productGrid .pcard').forEach(card => {
        card.style.display = card.dataset.search.includes(q) ? '' : 'none';
    });
});
</script>
@endpush
