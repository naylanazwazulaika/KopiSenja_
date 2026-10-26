<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());
        $type = $request->input('type', 'sales'); // sales, purchases, financial

        $salesQuery = Sale::where('status', 'completed')
            ->whereDate('sold_at', '>=', $startDate)
            ->whereDate('sold_at', '<=', $endDate);

        $purchasesQuery = Purchase::where('status', 'completed')
            ->whereDate('purchased_at', '>=', $startDate)
            ->whereDate('purchased_at', '<=', $endDate);

        $totalSales = (clone $salesQuery)->sum('total');
        $salesCount = (clone $salesQuery)->count();

        $totalPurchases = (clone $purchasesQuery)->sum('total');
        $purchasesCount = (clone $purchasesQuery)->count();

        $grossProfit = $totalSales - $totalPurchases;

        $sales = (clone $salesQuery)->with(['user', 'items.product'])->latest('sold_at')->paginate(15, ['*'], 'sales_page')->withQueryString();
        $purchases = (clone $purchasesQuery)->with(['supplier', 'user', 'items.product'])->latest('purchased_at')->paginate(15, ['*'], 'purchases_page')->withQueryString();

        return view('reports.index', compact(
            'startDate',
            'endDate',
            'type',
            'totalSales',
            'salesCount',
            'totalPurchases',
            'purchasesCount',
            'grossProfit',
            'sales',
            'purchases'
        ));
    }

    public function print(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        $sales = Sale::where('status', 'completed')
            ->whereDate('sold_at', '>=', $startDate)
            ->whereDate('sold_at', '<=', $endDate)
            ->with(['user', 'items.product'])
            ->latest('sold_at')
            ->get();

        $purchases = Purchase::where('status', 'completed')
            ->whereDate('purchased_at', '>=', $startDate)
            ->whereDate('purchased_at', '<=', $endDate)
            ->with(['supplier', 'user', 'items.product'])
            ->latest('purchased_at')
            ->get();

        $totalSales = $sales->sum('total');
        $totalPurchases = $purchases->sum('total');
        $netProfit = $totalSales - $totalPurchases;

        return view('reports.print', compact('startDate', 'endDate', 'sales', 'purchases', 'totalSales', 'totalPurchases', 'netProfit'));
    }
}
