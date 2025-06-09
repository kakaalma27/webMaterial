<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\Sale;
use App\Exports\SalesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PaymentHistoryController extends Controller
{
public function salesHistory(Request $request)
{
    $query = Sale::with(['productable', 'payment']);

    // Search by product name
    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('productable', function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        });
    }

    // Filter by period: daily, weekly, monthly
    if ($request->filled('filter')) {
        $filter = $request->filter;
        $now = Carbon::now();

        if ($filter === 'daily') {
            // Filter transaksi tanggal hari ini
            $query->whereDate('created_at', $now->toDateString());
        } elseif ($filter === 'weekly') {
            // Filter transaksi dalam minggu ini (Senin sampai sekarang)
            $query->whereBetween('created_at', [
                $now->startOfWeek()->toDateString(),
                $now->endOfWeek()->toDateString()
            ]);
        } elseif ($filter === 'monthly') {
            // Filter transaksi di bulan ini
            $query->whereYear('created_at', $now->year)
                  ->whereMonth('created_at', $now->month);
        }
    }

    $sales = $query->latest()->paginate(10);

    return view('main.paymenthistory.index', compact('sales'));
}

public function exportExcel(Request $request)
{
    return Excel::download(new SalesExport($request->search, $request->filter), 'sales.xlsx');
}

public function exportPdf(Request $request)
{
    $query = Sale::with(['productable', 'payment']);

    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('productable', function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        });
    }

    if ($request->filled('filter')) {
        $now = Carbon::now();

        if ($request->filter == 'daily') {
            $query->whereDate('created_at', $now->toDateString());
        } elseif ($request->filter == 'weekly') {
            $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
        } elseif ($request->filter == 'monthly') {
            $query->whereMonth('created_at', $now->month)
                  ->whereYear('created_at', $now->year);
        }
    }

    $sales = $query->latest()->get();

    $pdf = PDF::loadView('exports.sales-pdf', compact('sales'));
    return $pdf->download('sales.pdf');
}
}
