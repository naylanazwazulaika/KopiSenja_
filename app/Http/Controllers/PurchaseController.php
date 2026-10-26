<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $supplierId = $request->input('supplier_id');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $purchases = Purchase::with(['supplier', 'user', 'items.product'])
            ->when($search, function ($query, $search) {
                return $query->where('purchase_number', 'like', "%{$search}%")
                             ->orWhereHas('supplier', function ($q) use ($search) {
                                 $q->where('name', 'like', "%{$search}%");
                             });
            })
            ->when($supplierId, function ($query, $supplierId) {
                return $query->where('supplier_id', $supplierId);
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('purchased_at', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                return $query->whereDate('purchased_at', '<=', $endDate);
            })
            ->latest('purchased_at')
            ->paginate(10)
            ->withQueryString();

        $suppliers = Supplier::orderBy('name')->get();

        return view('purchases.index', compact('purchases', 'suppliers', 'search', 'supplierId', 'status', 'startDate', 'endDate'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchased_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
        ], [
            'supplier_id.required' => 'Supplier pemasok wajib dipilih.',
            'items.required' => 'Minimal harus menambahkan 1 item produk pembelian.',
            'items.*.product_id.required' => 'Produk wajib dipilih.',
            'items.*.quantity.min' => 'Kuantitas pembelian harus lebih besar dari 0.',
            'items.*.unit_cost.min' => 'Harga beli satuan tidak boleh bernilai negatif.',
        ]);

        // PPT Requirement: Atomic DB::transaction for header, items, and stock increments
        $purchase = DB::transaction(function () use ($request) {
            $purchaseNumber = 'PO-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

            $purchase = Purchase::create([
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'purchase_number' => $purchaseNumber,
                'total' => 0, // Calculated on server below!
                'status' => 'completed',
                'notes' => $request->notes,
                'purchased_at' => Carbon::parse($request->purchased_at),
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];
                $unitCost = (float) $item['unit_cost'];

                if ($qty < 1) {
                    throw ValidationException::withMessages([
                        'items' => 'Kuantitas produk ' . $product->name . ' harus minimal 1.',
                    ]);
                }

                $subtotal = $unitCost * $qty;
                $total += $subtotal;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_cost' => $unitCost,
                    'subtotal' => $subtotal,
                ]);

                // PPT Slide 7: Stok bertambah setelah transaksi pembelian berhasil
                $product->increment('stock', $qty);

                // Update purchase_price on product if new unit cost given
                if ($unitCost > 0) {
                    $product->update(['purchase_price' => $unitCost]);
                }
            }

            // Total is calculated on the server, not trusted from browser!
            $purchase->update(['total' => $total]);

            return $purchase;
        });

        return redirect()->route('purchases.show', $purchase)->with('success', 'Transaksi pembelian ' . $purchase->purchase_number . ' berhasil disimpan dan stok produk telah ditambahkan.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.product']);
        return view('purchases.show', compact('purchase'));
    }

    public function cancel(Purchase $purchase)
    {
        // Only Admin can cancel transaction (PPT Slide 5)
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin yang berhak membatalkan transaksi.');
        }

        if ($purchase->status === 'cancelled') {
            return back()->with('error', 'Transaksi pembelian ini sudah dalam status dibatalkan.');
        }

        // PPT Slide 8: "Transaksi selesai tidak boleh diedit sembarangan; gunakan aturan pembatalan yang memulihkan stok secara konsisten."
        DB::transaction(function () use ($purchase) {
            foreach ($purchase->items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item->product_id);

                if ($product->stock < $item->quantity) {
                    throw ValidationException::withMessages([
                        'cancel' => 'Pembatalan gagal: Stok saat ini untuk ' . $product->name . ' (' . $product->stock . ') lebih sedikit dari kuantitas yang dibeli (' . $item->quantity . '). Barang mungkin sudah terjual.',
                    ]);
                }

                // Restore stock (deduct what was added)
                $product->decrement('stock', $item->quantity);
            }

            $purchase->update(['status' => 'cancelled']);
        });

        return redirect()->route('purchases.show', $purchase)->with('success', 'Transaksi pembelian berhasil dibatalkan dan stok produk telah dipulihkan secara konsisten.');
    }
}
