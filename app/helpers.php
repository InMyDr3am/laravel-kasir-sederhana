<?php

if (! function_exists('setting')) {
    /**
     * Ambil pengaturan toko, dengan fallback default bila belum diisi.
     */
    function setting(string $key, ?string $default = null): ?string
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (! function_exists('rupiah')) {
    /**
     * Format angka menjadi rupiah, mis. 15000 -> "Rp 15.000".
     */
    function rupiah(int|float $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }
}
