<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'invoice_no',
        'user_id',
        'subtotal',
        'discount',
        'total',
        'payment_method',
        'status',
        'paid',
        'change',
    ];

    /** Transaksi yang sah dihitung (bukan yang dibatalkan). */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'selesai');
    }

    public function isCancelled(): bool
    {
        return $this->status === 'batal';
    }

    /** Label metode pembayaran untuk ditampilkan. */
    public function paymentLabel(): string
    {
        return match ($this->payment_method) {
            'qris' => 'QRIS',
            'transfer' => 'Transfer',
            default => 'Tunai',
        };
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
