<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dispatcher to role-specific dashboard
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isStaff()) {
            return redirect()->route('staff.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    }

    /**
     * 13. DASHBOARD ADMIN
     */
    public function adminDashboard()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // 1. Master Data Stats
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $totalStaff = User::where('role', 'staff')->count();
        $totalCustomers = User::whereIn('role', ['customer', 'pelanggan'])->count();

        // 2. Transaction Stats (Completed / Valid only for revenue)
        $completedSalesQuery = Sale::where('status', 'completed');
        $totalSalesCount = Sale::where('status', '!=', 'cancelled')->count();
        $totalPurchasesCount = Purchase::where('status', '!=', 'cancelled')->count();
        $totalCustomerOrders = Sale::whereIn('order_type', ['dine_in', 'take_away'])->count();

        $salesToday = (clone $completedSalesQuery)->whereDate('sold_at', $today)->sum('total');
        $salesThisMonth = (clone $completedSalesQuery)->where('sold_at', '>=', $startOfMonth)->sum('total');
        $salesAllTime = (clone $completedSalesQuery)->sum('total');

        $purchasesThisMonth = Purchase::where('status', 'completed')->where('purchased_at', '>=', $startOfMonth)->sum('total');

        // 3. Low Stock Alert
        $lowStockProducts = Product::with('category')->lowStock()->orderBy('stock', 'asc')->get();
        $lowStockCount = $lowStockProducts->count();

        // 4. Order Status Summary
        $orderStatusCounts = [
            'pending' => Sale::where('status', 'pending')->count(),
            'confirmed' => Sale::where('status', 'confirmed')->count(),
            'preparing' => Sale::where('status', 'preparing')->count(),
            'ready' => Sale::where('status', 'ready')->count(),
            'completed' => Sale::where('status', 'completed')->count(),
            'cancelled' => Sale::where('status', 'cancelled')->count(),
        ];

        // 5. Recent Transactions
        $recentSales = Sale::with(['user', 'items.product'])->latest('sold_at')->take(5)->get();
        $recentPurchases = Purchase::with(['supplier', 'user', 'items.product'])->latest('purchased_at')->take(5)->get();

        // 6. Top Selling Products
        $driver = DB::connection()->getDriverName();
        $groupFields = ($driver === 'sqlite') ? 'products.id' : 'products.id, products.category_id, products.sku, products.name, products.description, products.purchase_price, products.selling_price, products.stock, products.minimum_stock, products.image, products.is_active, products.deleted_at, products.created_at, products.updated_at';

        $topSellingProducts = Product::select('products.*', DB::raw('COALESCE(SUM(sale_items.quantity), 0) as total_sold'))
            ->join('sale_items', 'products.id', '=', 'sale_items.product_id')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->where('sales.status', 'completed')
            ->groupByRaw($groupFields)
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // 7. Monthly Sales Chart (Last 6 Months, MySQL & SQLite compatible)
        $monthSql = ($driver === 'sqlite') ? "strftime('%Y-%m', sold_at)" : "DATE_FORMAT(sold_at, '%Y-%m')";
        $monthlySales = Sale::where('status', 'completed')
            ->where('sold_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->select(
                DB::raw("{$monthSql} as month"),
                DB::raw("SUM(total) as revenue"),
                DB::raw("COUNT(id) as count")
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // 8. AI Demand Forecasting Engine for Top Selling Products
        $aiForecasts = $topSellingProducts->map(function ($product) {
            $soldLast30Days = DB::table('sale_items')
                ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
                ->where('sale_items.product_id', $product->id)
                ->where('sales.status', 'completed')
                ->where('sales.sold_at', '>=', Carbon::now()->subDays(30))
                ->sum('sale_items.quantity') ?: max(1, $product->total_sold);

            $dailyVelocity = round($soldLast30Days / 30, 2);
            $dailyVelocity = max(0.1, $dailyVelocity);
            $daysToStockout = (int) round($product->stock / $dailyVelocity);
            $forecastNextMonth = (int) round($dailyVelocity * 30 * 1.15); // +15% demand buffer
            $suggestedRestock = max(0, ($forecastNextMonth + $product->minimum_stock) - $product->stock);

            $urgency = 'OPTIMAL';
            $urgencyColor = 'emerald';
            if ($product->stock <= 0) {
                $urgency = 'HABIS';
                $urgencyColor = 'red';
            } elseif ($daysToStockout <= 3) {
                $urgency = 'KRITIS';
                $urgencyColor = 'red';
            } elseif ($daysToStockout <= 7) {
                $urgency = 'PERHATIAN';
                $urgencyColor = 'amber';
            }

            return [
                'product' => $product,
                'daily_velocity' => $dailyVelocity,
                'days_to_stockout' => $daysToStockout,
                'forecast_next_month' => $forecastNextMonth,
                'suggested_restock' => $suggestedRestock,
                'urgency' => $urgency,
                'urgency_color' => $urgencyColor,
                'confidence_score' => min(98, max(75, 80 + ($product->total_sold * 2))),
            ];
        });

        return view('dashboard.admin', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'totalStaff',
            'totalCustomers',
            'totalSalesCount',
            'totalPurchasesCount',
            'totalCustomerOrders',
            'salesToday',
            'salesThisMonth',
            'salesAllTime',
            'purchasesThisMonth',
            'lowStockCount',
            'lowStockProducts',
            'orderStatusCounts',
            'recentSales',
            'recentPurchases',
            'topSellingProducts',
            'monthlySales',
            'aiForecasts'
        ));
    }

    /**
     * 14. DASHBOARD STAFF
     */
    public function staffDashboard()
    {
        $today = Carbon::today();

        // Transaksi hari ini
        $transactionsTodayCount = Sale::whereDate('sold_at', $today)->count();
        $salesTodayTotal = Sale::where('status', 'completed')->whereDate('sold_at', $today)->sum('total');

        // Pesanan pelanggan terbaru & antrean
        $pendingOrders = Sale::with(['user', 'items.product'])
            ->where('status', 'pending')
            ->oldest('sold_at')
            ->get();

        $preparingOrders = Sale::with(['user', 'items.product'])
            ->whereIn('status', ['confirmed', 'preparing'])
            ->oldest('sold_at')
            ->get();

        $readyOrders = Sale::with(['user', 'items.product'])
            ->where('status', 'ready')
            ->oldest('sold_at')
            ->get();

        $recentSales = Sale::with(['user', 'items.product'])
            ->latest('sold_at')
            ->take(8)
            ->get();

        $lowStockProducts = Product::with('category')->lowStock()->orderBy('stock', 'asc')->get();

        return view('dashboard.staff', compact(
            'transactionsTodayCount',
            'salesTodayTotal',
            'pendingOrders',
            'preparingOrders',
            'readyOrders',
            'recentSales',
            'lowStockProducts'
        ));
    }

    /**
     * 15. DASHBOARD PELANGGAN
     */
    public function customerDashboard()
    {
        $user = auth()->user();

        // Pesanan aktif saat ini (belum selesai & belum dibatalkan)
        $activeOrders = Sale::with(['items.product'])
            ->where('user_id', $user->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->latest('sold_at')
            ->get();

        // Riwayat pesanan terakhir
        $recentOrders = Sale::with(['items.product'])
            ->where('user_id', $user->id)
            ->latest('sold_at')
            ->take(5)
            ->get();

        $totalOrdersCount = Sale::where('user_id', $user->id)->count();
        $totalSpent = Sale::where('user_id', $user->id)->where('status', 'completed')->sum('total');

        // Produk rekomendasi / terfavorit
        $featuredProducts = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->take(4)
            ->get();

        return view('dashboard.customer', compact(
            'user',
            'activeOrders',
            'recentOrders',
            'totalOrdersCount',
            'totalSpent',
            'featuredProducts'
        ));
    }
}
