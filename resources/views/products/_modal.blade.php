@php
    // Hanya modal yang gagal validasi ini yang memakai old() & menampilkan error,
    // supaya input tidak "bocor" ke modal baris lain.
    $isErr = old('_modal') === $modalId;
    $val = fn ($field, $default) => $isErr ? old($field, $default) : $default;
@endphp
<div class="modal-backdrop" id="{{ $modalId }}">
    <div class="modal">
        <div class="modal-head">
            <h3>{{ $title }}</h3>
            <button type="button" class="modal-x" onclick="closeModal(this)">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ $action }}">
                @csrf
                @if ($method === 'PUT') @method('PUT') @endif
                <input type="hidden" name="_modal" value="{{ $modalId }}">

                <div class="form-grid">
                    <div class="field">
                        <label>SKU / Kode</label>
                        <input class="input" name="sku" value="{{ $val('sku', $product->sku) }}" required>
                        @if ($isErr) @error('sku') <div class="error">{{ $message }}</div> @enderror @endif
                    </div>
                    <div class="field">
                        <label>Kategori</label>
                        <input class="input" name="category" value="{{ $val('category', $product->category) }}" placeholder="opsional">
                    </div>
                </div>

                <div class="field">
                    <label>Nama Produk</label>
                    <input class="input" name="name" value="{{ $val('name', $product->name) }}" required>
                    @if ($isErr) @error('name') <div class="error">{{ $message }}</div> @enderror @endif
                </div>

                <div class="form-grid">
                    <div class="field">
                        @php $priceVal = $val('price', $product->price); @endphp
                        <label>Harga (Rp)</label>
                        <input class="input" type="text" inputmode="numeric" required
                               value="{{ ($priceVal !== null && $priceVal !== '') ? number_format((int) $priceVal, 0, ',', '.') : '' }}"
                               oninput="formatThousands(this)" data-target="price-{{ $modalId }}">
                        <input type="hidden" name="price" id="price-{{ $modalId }}" value="{{ $priceVal }}">
                        @if ($isErr) @error('price') <div class="error">{{ $message }}</div> @enderror @endif
                    </div>
                    <div class="field">
                        <label>Stok</label>
                        <input class="input" type="number" min="0" name="stock" value="{{ $val('stock', $product->stock ?? 0) }}" required>
                        @if ($isErr) @error('stock') <div class="error">{{ $message }}</div> @enderror @endif
                    </div>
                </div>

                <div class="field checkline">
                    <input type="checkbox" id="{{ $modalId }}-active" name="is_active" value="1" {{ $val('is_active', $product->is_active) ? 'checked' : '' }}>
                    <label for="{{ $modalId }}-active" style="margin:0">Produk aktif (bisa dijual)</label>
                </div>

                <div class="modal-foot">
                    <button type="button" class="btn ghost" onclick="closeModal(this)">Batal</button>
                    <button type="submit" class="btn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
