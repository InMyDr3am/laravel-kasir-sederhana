<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = today();

        $salesToday = Sale::whereDate('created_at', $today);

        return view('dashboard', [
            'revenueToday' => (int) (clone $salesToday)->sum('total'),
            'countToday' => (clone $salesToday)->count(),
            'productCount' => Product::active()->count(),
            'lowStock' => Product::active()->where('stock', '<=', 5)
                ->orderBy('stock')->take(5)->get(),
            'recentSales' => Sale::with('cashier')->latest()->take(5)->get(),
        ]);
    }
}
