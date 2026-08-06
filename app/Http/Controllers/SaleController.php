<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function __construct(private readonly SaleService $sales)
    {
    }

    public function create(): View
    {
        $products = Product::active()
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get(['id', 'sku', 'name', 'category', 'price', 'stock']);

        return view('sales.create', compact('products'));
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $sale = $this->sales->checkout(
            items: $request->validated('items'),
            paid: (int) $request->validated('paid'),
            userId: $request->user()->id,
        );

        return redirect()->route('sales.show', $sale)->with('status', 'Transaksi berhasil disimpan.');
    }

    public function show(Sale $sale): View
    {
        $sale->load('items', 'cashier');

        return view('sales.receipt', compact('sale'));
    }
}
