<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    /**
     * Tampilkan seluruh transaksi penjualan (Internal POS & Pesanan Pelanggan)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $orderType = $request->input('order_type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Sale::with(['user', 'staff', 'items.product.category']);

        // Jika Pelanggan, hanya dapat melihat transaksi pesanannya sendiri
        if (auth()->user()->isCustomer()) {
            $query->where('user_id', auth()->id());
        }

        $sales = $query
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('sale_number', 'like', "%{$search}%")
                      ->orWhere('customer_name', 'like', "%{$search}%")
                      ->orWhere('customer_phone', 'like', "%{$search}%")
                      ->orWhere('notes', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($orderType, function ($query, $orderType) {
                return $query->where('order_type', $orderType);
            })
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('sold_at', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('sold_at', '<=', $endDate);
            })
            ->latest('sold_at')
            ->paginate(10)
            ->withQueryString();

        return view('sales.index', compact('sales', 'search', 'status', 'orderType', 'startDate', 'endDate'));
    }

    /**
     * Formulir kasir POS walk-in penjualan internal (Staff & Admin)
     */
    public function create()
    {
        $products = Product::where('stock', '>', 0)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('sales.create', compact('products'));
    }

    /**
     * Simpan transaksi penjualan kasir POS internal (Atomic DB::transaction & lockForUpdate)
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => ['nullable', 'string', 'max:100'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'order_type' => ['required', 'string', 'in:pos,dine_in,take_away'],
            'payment_method' => ['required', 'string', 'in:cash,qris,transfer'],
            'sold_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ], [
            'items.required' => 'Minimal harus menambahkan 1 item menu kopi atau pastry.',
            'items.*.product_id.required' => 'Produk menu kopi wajib dipilih.',
            'items.*.quantity.min' => 'Kuantitas penjualan harus lebih besar dari 0.',
        ]);

        // PPT Slide 16: Business logic penjualan dengan DB::transaction & lockForUpdate
        $sale = DB::transaction(function () use ($request) {
            $prefix = ($request->order_type === 'pos') ? 'SENJA-' : 'ORD-';
            $saleNumber = $prefix . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

            $customerName = $request->customer_name ?: 'Pelanggan Kasir Walk-in';

            $sale = Sale::create([
                'user_id' => auth()->id(),
                'staff_id' => auth()->id(),
                'sale_number' => $saleNumber,
                'customer_name' => $customerName,
                'customer_phone' => $request->customer_phone,
                'order_type' => $request->order_type,
                'total' => 0, // Kalkulasi di backend!
                'payment_method' => $request->payment_method,
                'status' => 'completed',
                'notes' => $request->notes,
                'sold_at' => Carbon::parse($request->sold_at),
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                // Prevent race condition & overselling with lockForUpdate()
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];

                // Validasi kecukupan stok fisik
                if ($qty < 1 || $product->stock < $qty) {
                    throw ValidationException::withMessages([
                        'items' => "Kuantitas tidak valid atau stok tidak mencukupi untuk '{$product->name}'. Tersedia: {$product->stock}, Diminta: {$qty}.",
                    ]);
                }

                // Ambil harga resmi dari database, bukan dipercaya dari request form browser
                $unitPrice = $product->selling_price;
                $subtotal = $unitPrice * $qty;
                $total += $subtotal;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                // Kurangi stok barang secara atomik
                $product->decrement('stock', $qty);
            }

            // Total disimpan dari hasil kalkulasi backend yang valid
            $sale->update(['total' => $total]);

            return $sale;
        });

        return redirect()->route('sales.show', $sale)->with('success', 'Transaksi kasir penjualan ' . $sale->sale_number . ' berhasil diproses dan stok telah diperbarui.');
    }

    /**
     * Tampilkan rincian detail nota penjualan
     */
    public function show(Sale $sale)
    {
        // Otorisasi Pelanggan: Hanya boleh melihat pesanan miliknya sendiri
        if (auth()->user()->isCustomer() && $sale->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses melihat transaksi ini.');
        }

        $sale->load(['user', 'staff', 'items.product.category']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Halaman daftar pesanan pelanggan khusus Staff dan Admin
     */
    public function customerOrders(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $query = Sale::with(['user', 'staff', 'items.product'])
            ->whereIn('order_type', ['dine_in', 'take_away']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest('sold_at')->paginate(12)->withQueryString();

        $statusCounts = [
            'all' => Sale::whereIn('order_type', ['dine_in', 'take_away'])->count(),
            'pending' => Sale::whereIn('order_type', ['dine_in', 'take_away'])->where('status', 'pending')->count(),
            'confirmed' => Sale::whereIn('order_type', ['dine_in', 'take_away'])->where('status', 'confirmed')->count(),
            'preparing' => Sale::whereIn('order_type', ['dine_in', 'take_away'])->where('status', 'preparing')->count(),
            'ready' => Sale::whereIn('order_type', ['dine_in', 'take_away'])->where('status', 'ready')->count(),
            'completed' => Sale::whereIn('order_type', ['dine_in', 'take_away'])->where('status', 'completed')->count(),
            'cancelled' => Sale::whereIn('order_type', ['dine_in', 'take_away'])->where('status', 'cancelled')->count(),
        ];

        return view('sales.customer_orders', compact('orders', 'status', 'search', 'statusCounts'));
    }

    /**
     * Perbarui status alur pesanan operasional (Staff & Admin)
     */
    public function updateStatus(Request $request, Sale $sale)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:pending,confirmed,preparing,ready,completed,cancelled'],
        ]);

        $newStatus = $request->status;

        if ($sale->status === $newStatus) {
            return back()->with('info', 'Status pesanan sudah ' . $sale->status_label);
        }

        // Jika status diubah menjadi Dibatalkan (Cancelled), pulihkan stok barang (Khusus Owner!)
        if ($newStatus === 'cancelled') {
            if (!auth()->user()->isAdmin()) {
                return back()->with('error', 'Akses ditolak. Sesuai kebijakan kontrol ritel, pembatalan pesanan dan pemulihan stok hanya dapat dilakukan oleh Owner.');
            }

            if ($sale->status === 'cancelled') {
                return back()->with('error', 'Pesanan ini sudah dibatalkan sebelumnya.');
            }

            DB::transaction(function () use ($sale) {
                foreach ($sale->items as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item->product_id);
                    $product->increment('stock', $item->quantity);
                }
                $sale->update([
                    'status' => 'cancelled',
                    'staff_id' => auth()->id(),
                ]);
            });

            return back()->with('success', "Pesanan {$sale->sale_number} berhasil dibatalkan oleh Owner dan stok produk telah dikembalikan ke etalase.");
        }

        // Update status operasional pesanan
        $sale->update([
            'status' => $newStatus,
            'staff_id' => auth()->id(),
        ]);

        return back()->with('success', "Status pesanan {$sale->sale_number} berhasil diperbarui menjadi {$sale->status_label}.");
    }

    /**
     * Pembatalan pesanan oleh Pelanggan (Hanya jika status masih 'pending')
     */
    public function customerCancel(Sale $sale)
    {
        if ($sale->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak berhak membatalkan pesanan ini.');
        }

        if (!$sale->canBeCancelledByCustomer() && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah dalam proses penyiapan oleh barista.');
        }

        if ($sale->status === 'cancelled') {
            return back()->with('error', 'Pesanan ini sudah berstatus dibatalkan.');
        }

        DB::transaction(function () use ($sale) {
            foreach ($sale->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item->product_id);
                $product->increment('stock', $item->quantity);
            }
            $sale->update(['status' => 'cancelled']);
        });

        return back()->with('success', "Pesanan {$sale->sale_number} berhasil dibatalkan dan stok telah dipulihkan.");
    }

    /**
     * Pembatalan transaksi oleh Admin dengan pemulihan stok
     */
    public function cancel(Sale $sale)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin yang berhak membatalkan transaksi.');
        }

        if ($sale->status === 'cancelled') {
            return back()->with('error', 'Transaksi penjualan ini sudah dalam status dibatalkan sebelumnya.');
        }

        DB::transaction(function () use ($sale) {
            foreach ($sale->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item->product_id);
                $product->increment('stock', $item->quantity);
            }

            $sale->update(['status' => 'cancelled']);
        });

        return redirect()->route('sales.show', $sale)->with('success', 'Transaksi penjualan berhasil dibatalkan dan stok produk telah dikembalikan ke inventaris.');
    }
}
