@extends('layouts.app')
@section('title', 'Pengaturan Toko')

@section('content')
    <div class="topbar">
        <div>
            <h1>Pengaturan Toko</h1>
            <div class="sub">Identitas toko yang tampil di struk</div>
        </div>
    </div>

    <div class="card pad" style="max-width:560px">
        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="store_name">Nama Toko</label>
                <input class="input" id="store_name" name="store_name" value="{{ old('store_name', setting('store_name')) }}" required>
                @error('store_name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label for="store_address">Alamat</label>
                <input class="input" id="store_address" name="store_address" value="{{ old('store_address', setting('store_address')) }}" placeholder="opsional">
                @error('store_address') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label for="store_phone">Telepon</label>
                <input class="input" id="store_phone" name="store_phone" value="{{ old('store_phone', setting('store_phone')) }}" placeholder="opsional">
                @error('store_phone') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label for="receipt_footer">Catatan Kaki Struk</label>
                <input class="input" id="receipt_footer" name="receipt_footer" value="{{ old('receipt_footer', setting('receipt_footer')) }}" placeholder="mis. Terima kasih atas kunjungan Anda">
                @error('receipt_footer') <div class="error">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn" style="margin-top:6px">Simpan Pengaturan</button>
        </form>
    </div>
@endsection
