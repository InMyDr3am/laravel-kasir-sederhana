<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleService
{
    /**
     * Proses satu transaksi penjualan.
     *
     * @param  array<int, array{product_id:int, qty:int}>  $items
     * @throws ValidationException
     */
    public function checkout(array $items, int $paid, int $userId): Sale
    {
        return DB::transaction(function () use ($items, $paid, $userId) {
            $total = 0;
            $lines = [];

            foreach ($items as $item) {
                // Lock baris produk agar stok tidak balapan antar kasir.
                $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();

                if (! $product || ! $product->is_active) {
                    throw ValidationException::withMessages([
                        'items' => "Produk tidak ditemukan atau tidak aktif.",
                    ]);
                }

                if ($item['qty'] > $product->stock) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->name} tidak cukup (sisa {$product->stock}).",
                    ]);
                }

                $subtotal = $product->price * $item['qty'];
                $total += $subtotal;

                $lines[] = [
                    'product' => $product,
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal,
                ];
            }

            if ($paid < $total) {
                throw ValidationException::withMessages([
                    'paid' => 'Uang bayar kurang dari total belanja.',
                ]);
            }

            $sale = Sale::create([
                'invoice_no' => $this->generateInvoiceNo(),
                'user_id' => $userId,
                'total' => $total,
                'paid' => $paid,
                'change' => $paid - $total,
            ]);

            foreach ($lines as $line) {
                $product = $line['product'];

                $sale->items()->create([
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'qty' => $line['qty'],
                    'subtotal' => $line['subtotal'],
                ]);

                $product->decrement('stock', $line['qty']);
            }

            return $sale;
        });
    }

    private function generateInvoiceNo(): string
    {
        $prefix = 'INV-'.now()->format('Ymd').'-';
        $lastToday = Sale::where('invoice_no', 'like', $prefix.'%')->max('invoice_no');
        $seq = $lastToday ? ((int) substr($lastToday, -4)) + 1 : 1;

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
