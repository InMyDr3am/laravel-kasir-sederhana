<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->date('from') ?? today();
        $to = $request->date('to') ?? today();

        $query = Sale::with('cashier')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->latest();

        $sales = $query->paginate(15)->withQueryString();

        $summary = Sale::completed()
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->selectRaw('COUNT(*) as count, COALESCE(SUM(total), 0) as revenue')
            ->first();

        return view('reports.index', [
            'sales' => $sales,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'revenue' => (int) $summary->revenue,
            'count' => (int) $summary->count,
        ]);
    }
}
