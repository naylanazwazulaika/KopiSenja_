<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Tampilkan halaman keranjang belanja pelanggan
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $itemsWithDetails = [];

        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product && $product->is_active) {
                $subtotal = $product->selling_price * $item['quantity'];
                $total += $subtotal;
                $itemsWithDetails[] = [
                    'id' => $product->id,
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'price' => $product->selling_price,
                    'image' => $product->image_url,
                    'quantity' => $item['quantity'],
                    'stock' => $product->stock,
                    'subtotal' => $subtotal,
                    'category' => $product->category->name ?? 'Menu',
                    'is_stock_sufficient' => ($product->stock >= $item['quantity']),
                ];
            }
        }

        return view('shop.cart', compact('itemsWithDetails', 'total'));
    }

    /**
     * Tambahkan produk ke keranjang belanja
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($request->product_id);
        $qtyToAdd = (int) ($request->quantity ?? 1);

        if (!$product->is_active) {
            return back()->with('error', 'Produk ini sedang tidak aktif.');
        }

        if ($product->stock < 1) {
            return back()->with('error', 'Maaf, stok produk ' . $product->name . ' sedang habis.');
        }

        $cart = session()->get('cart', []);
        $currentQty = isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0;
        $newQty = $currentQty + $qtyToAdd;

        if ($newQty > $product->stock) {
            return back()->with('error', "Stok tidak mencukupi. Tersedia: {$product->stock}, di keranjang Anda: {$currentQty}.");
        }

        $cart[$product->id] = [
            'id' => $product->id,
            'name' => $product->name,
            'quantity' => $newQty,
            'price' => $product->selling_price,
            'image' => $product->image_url,
        ];

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$product->name} berhasil ditambahkan ke keranjang.",
                'cart_count' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return back()->with('success', "{$product->name} berhasil ditambahkan ke keranjang pesanan.");
    }

    /**
     * Perbarui kuantitas produk dalam keranjang
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty = (int) $request->quantity;

        if ($qty > $product->stock) {
            return back()->with('error', "Kuantitas melebihi stok yang tersedia ({$product->stock} unit).");
        }

        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $qty;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Kuantitas keranjang berhasil diperbarui.');
    }

    /**
     * Hapus item dari keranjang
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    /**
     * Kosongkan keranjang
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Keranjang pesanan telah dikosongkan.');
    }

    /**
     * Tampilkan formulir checkout pelanggan
     */
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('info', 'Keranjang belanja Anda masih kosong. Silakan pilih menu terlebih dahulu.');
        }

        $total = 0;
        $itemsWithDetails = [];

        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product && $product->is_active) {
                if ($product->stock < $item['quantity']) {
                    return redirect()->route('cart.index')->with('error', "Stok untuk {$product->name} tidak mencukupi (Tersedia: {$product->stock}). Silakan sesuaikan kuantitas.");
                }
                $subtotal = $product->selling_price * $item['quantity'];
                $total += $subtotal;
                $itemsWithDetails[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
            }
        }

        $user = auth()->user();

        return view('shop.checkout', compact('itemsWithDetails', 'total', 'user'));
    }

    /**
     * Proses transaksi checkout pelanggan
     */
    public function processCheckout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Keranjang belanja kosong.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'order_type' => ['required', 'string', 'in:dine_in,take_away'],
            'payment_method' => ['required', 'string', 'in:cash,qris,transfer'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'customer_name.required' => 'Nama penerima pesanan wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp / telepon wajib diisi.',
            'order_type.required' => 'Silakan pilih Dine In atau Take Away.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        // PPT Requirement: Atomic DB::transaction + lockForUpdate to prevent overselling
        $sale = DB::transaction(function () use ($validated, $cart) {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

            $sale = Sale::create([
                'user_id' => auth()->id(),
                'staff_id' => null,
                'sale_number' => $orderNumber,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'order_type' => $validated['order_type'],
                'payment_method' => $validated['payment_method'],
                'status' => 'pending', // Menunggu Konfirmasi Barista
                'notes' => $validated['notes'] ?? null,
                'total' => 0, // Server-side calculation only!
                'sold_at' => Carbon::now(),
            ]);

            $total = 0;

            foreach ($cart as $id => $item) {
                // Pessimistic locking: Product::lockForUpdate()
                $product = Product::lockForUpdate()->findOrFail($id);
                $qty = (int) $item['quantity'];

                // PPT Slide 16 & 17: Validasi stok server-side
                if ($qty < 1 || $product->stock < $qty) {
                    throw ValidationException::withMessages([
                        'cart' => "Stok produk '{$product->name}' tidak mencukupi untuk menyelesaikan checkout. Tersedia: {$product->stock}, Diminta: {$qty}.",
                    ]);
                }

                // Ambil harga asli dari database, BUKAN dari form browser!
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

            // Total disimpan dari hasil kalkulasi backend
            $sale->update(['total' => $total]);

            // Kosongkan keranjang setelah transaksi sukses
            session()->forget('cart');

            return $sale;
        });

        return redirect()->route('shop.order-success', $sale)->with('success', "Pesanan {$sale->sale_number} berhasil dibuat! Barista Kopi Senja sedang menunggu konfirmasi.");
    }
}
