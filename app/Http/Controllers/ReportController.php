<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        [$from, $to] = $this->range($request);

        $summary = $this->completedInRange($from, $to)
            ->selectRaw('COUNT(*) as count, COALESCE(SUM(total), 0) as revenue')
            ->first();

        return view('reports.index', [
            'sales' => Sale::with('cashier')
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'revenue' => (int) $summary->revenue,
            'count' => (int) $summary->count,
            'topProducts' => $this->topProducts($from, $to),
            'cashierRecap' => $this->cashierRecap($from, $to),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        [$from, $to] = $this->range($request);

        $sales = Sale::with('cashier')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->latest()
            ->get();

        $filename = "laporan-{$from->toDateString()}-sd-{$to->toDateString()}.csv";

        return response()->streamDownload(function () use ($sales) {
            $out = fopen('php://output', 'w');
            fprintf($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8 dengan benar
            fputcsv($out, ['Invoice', 'Tanggal', 'Kasir', 'Metode', 'Status', 'Subtotal', 'Diskon', 'Total']);

            foreach ($sales as $sale) {
                fputcsv($out, [
                    $sale->invoice_no,
                    $sale->created_at->format('Y-m-d H:i'),
                    $sale->cashier->name,
                    $sale->paymentLabel(),
                    $sale->isCancelled() ? 'Batal' : 'Selesai',
                    $sale->subtotal,
                    $sale->discount,
                    $sale->total,
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** @return array{0: \Illuminate\Support\Carbon, 1: \Illuminate\Support\Carbon} */
    private function range(Request $request): array
    {
        return [
            $request->date('from') ?? today(),
            $request->date('to') ?? today(),
        ];
    }

    private function completedInRange($from, $to)
    {
        return Sale::completed()
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);
    }

    private function topProducts($from, $to)
    {
        return SaleItem::query()
            ->selectRaw('product_id, name, SUM(qty) as qty, SUM(subtotal) as revenue')
            ->whereHas('sale', fn ($q) => $q->completed()
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to))
            ->groupBy('product_id', 'name')
            ->orderByDesc('qty')
            ->take(10)
            ->get();
    }

    private function cashierRecap($from, $to)
    {
        return $this->completedInRange($from, $to)
            ->selectRaw('user_id, COUNT(*) as count, COALESCE(SUM(total), 0) as revenue')
            ->with('cashier')
            ->groupBy('user_id')
            ->orderByDesc('revenue')
            ->get();
    }
}
