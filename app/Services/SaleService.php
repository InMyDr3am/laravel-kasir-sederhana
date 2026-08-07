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
    public function checkout(array $items, int $paid, int $userId, int $discount = 0, string $paymentMethod = 'tunai'): Sale
    {
        return DB::transaction(function () use ($items, $paid, $userId, $discount, $paymentMethod) {
            $subtotal = 0;
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

                $lineSubtotal = $product->price * $item['qty'];
                $subtotal += $lineSubtotal;

                $lines[] = [
                    'product' => $product,
                    'qty' => $item['qty'],
                    'subtotal' => $lineSubtotal,
                ];
            }

            if ($discount > $subtotal) {
                throw ValidationException::withMessages([
                    'discount' => 'Diskon tidak boleh melebihi subtotal.',
                ]);
            }

            $total = $subtotal - $discount;

            if ($paid < $total) {
                throw ValidationException::withMessages([
                    'paid' => 'Uang bayar kurang dari total belanja.',
                ]);
            }

            $sale = Sale::create([
                'invoice_no' => $this->generateInvoiceNo(),
                'user_id' => $userId,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $paymentMethod,
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

    /**
     * Batalkan transaksi: kembalikan stok tiap item lalu tandai batal.
     *
     * @throws ValidationException
     */
    public function void(Sale $sale): void
    {
        if ($sale->isCancelled()) {
            throw ValidationException::withMessages([
                'sale' => 'Transaksi ini sudah dibatalkan.',
            ]);
        }

        DB::transaction(function () use ($sale) {
            foreach ($sale->items as $item) {
                // withTrashed: produk bisa saja sudah dihapus (soft delete).
                Product::withTrashed()->find($item->product_id)?->increment('stock', $item->qty);
            }

            $sale->update(['status' => 'batal']);
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
