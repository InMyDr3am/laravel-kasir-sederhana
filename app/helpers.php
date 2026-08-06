<?php

if (! function_exists('rupiah')) {
    /**
     * Format angka menjadi rupiah, mis. 15000 -> "Rp 15.000".
     */
    function rupiah(int|float $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }
}
