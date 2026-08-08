<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        // Allowlist kolom yang boleh disortir (cegah SQL injection lewat query string).
        $sortable = ['sku', 'name', 'price', 'stock'];
        $sort = in_array($request->get('sort'), $sortable, true) ? $request->get('sort') : 'name';
        $direction = $request->get('direction') === 'desc' ? 'desc' : 'asc';

        $products = Product::query()
            ->when($request->string('q')->trim(), function ($query, $q) {
                $query->where(fn ($sub) => $sub
                    ->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%"));
            })
            ->orderBy($sort, $direction)
            ->paginate(12)
            ->withQueryString();

        return view('products.index', compact('products', 'sort', 'direction'));
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return redirect()->route('products.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()->route('products.index')->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')->with('status', 'Produk berhasil dihapus.');
    }
}
