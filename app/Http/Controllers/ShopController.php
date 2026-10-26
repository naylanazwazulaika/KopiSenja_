<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ShopController extends Controller
{
    /**
     * 3. HALAMAN BERANDA PUBLIK (LANDING PAGE)
     */
    public function home()
    {
        // Kategori utama kopi & pastry
        $categories = Category::withCount(['products' => function ($q) {
            $q->where('is_active', true);
        }])->get();

        // Produk unggulan (featured)
        $featuredProducts = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->take(8)
            ->get();

        // Kategori kopi khusus (espresso, signature, manual brew)
        $coffeeCategoryIds = Category::whereIn('slug', ['espresso-based', 'signature-coffee', 'manual-brew'])->pluck('id');
        $coffeeProducts = Product::with('category')
            ->whereIn('category_id', $coffeeCategoryIds)
            ->where('is_active', true)
            ->take(6)
            ->get();

        // Kategori non-kopi
        $nonCoffeeCat = Category::whereIn('slug', ['non-coffee', 'tea'])->pluck('id');
        $nonCoffeeProducts = Product::with('category')
            ->whereIn('category_id', $nonCoffeeCat)
            ->where('is_active', true)
            ->take(4)
            ->get();

        // Kategori pastry & bakery
        $pastryCat = Category::whereIn('slug', ['pastry', 'bakery', 'snacks'])->pluck('id');
        $pastryProducts = Product::with('category')
            ->whereIn('category_id', $pastryCat)
            ->where('is_active', true)
            ->take(6)
            ->get();

        return view('shop.home', compact(
            'categories',
            'featuredProducts',
            'coffeeProducts',
            'nonCoffeeProducts',
            'pastryProducts'
        ));
    }

    /**
     * 4. KATALOG MENU PRODUK LENGKAP
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categorySlug = $request->input('category');

        $categories = Category::withCount(['products' => function ($q) {
            $q->where('is_active', true);
        }])->get();

        $selectedCategory = null;
        if ($categorySlug) {
            $selectedCategory = Category::where('slug', $categorySlug)->first();
        }

        $products = Product::with('category')
            ->where('is_active', true)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($selectedCategory, function ($query, $selectedCategory) {
                return $query->where('category_id', $selectedCategory->id);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('shop.index', compact('products', 'categories', 'selectedCategory', 'search'));
    }

    /**
     * Detail produk menu
     */
    public function show(Product $product)
    {
        $product->load('category');
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }

    /**
     * Daftar Pesanan Saya (Pelanggan)
     */
    public function myOrders(Request $request)
    {
        $status = $request->input('status');

        $query = Sale::where('user_id', auth()->id())
            ->with(['items.product.category']);

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->latest('sold_at')->paginate(10)->withQueryString();

        return view('shop.my_orders', compact('orders', 'status'));
    }

    /**
     * Halaman Konfirmasi Sukses Pesanan
     */
    public function orderSuccess(Sale $sale)
    {
        if ($sale->user_id !== auth()->id() && !auth()->user()->isAdmin() && !auth()->user()->isStaff()) {
            abort(403, 'Akses ditolak.');
        }

        $sale->load(['items.product.category', 'user']);
        return view('shop.order_success', compact('sale'));
    }

    /**
     * Struk Digital Pesanan untuk Pelanggan (Hanya nota milik pelanggan bersangkutan)
     */
    public function orderReceipt(Sale $sale)
    {
        if ($sale->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak. Anda hanya berhak melihat nota pesanan milik Anda sendiri.');
        }

        $sale->load(['user', 'staff', 'items.product.category']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Halaman Profil Pelanggan
     */
    public function profile()
    {
        $user = auth()->user();
        return view('shop.profile', compact('user'));
    }

    /**
     * Perbarui Profil Pelanggan (Role tidak dapat diubah sendiri!)
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar oleh pengguna lain.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // Pastikan role TIDAK berubah!
        $user->update($updateData);

        return back()->with('success', 'Profil dan informasi akun Anda berhasil diperbarui.');
    }
}
