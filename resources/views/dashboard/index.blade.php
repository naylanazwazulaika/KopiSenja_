@extends('layouts.app')

@section('title', 'Dashboard Ringkasan')

@section('content')
<div class="space-y-8">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 text-xs font-semibold text-coffee-700 bg-coffee-100 px-3 py-1 rounded-full mb-1">
                <i class="fa-solid fa-mug-hot"></i>
                <span>Roastery & Coffee Inventory Hub</span>
            </div>
            <h1 class="text-3xl font-bold text-coffee-950 font-serif">Ringkasan Sistem & Bisnis</h1>
            <p class="text-xs text-stone-500 mt-1">Pemantauan real-time inventaris biji kopi, stok opname, dan transaksi penjualan</p>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('sales.create') }}" class="px-4 py-2.5 bg-coffee-800 hover:bg-coffee-900 text-white text-xs font-semibold rounded-xl shadow-sm transition inline-flex items-center space-x-2">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Transaksi Kasir Baru</span>
            </a>
            <a href="{{ route('purchases.create') }}" class="px-4 py-2.5 bg-white hover:bg-stone-50 border border-stone-200 text-stone-700 text-xs font-semibold rounded-xl transition inline-flex items-center space-x-2">
                <i class="fa-solid fa-truck-ramp-box text-[10px] text-coffee-600"></i>
                <span>Pengadaan Stok-In</span>
            </a>
        </div>
    </div>

    <!-- Aggregate Stats Grid (PPT Slide 18: Ringkasan memakai query agregat aktual) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Penjualan Hari Ini -->
        <div class="bg-white p-5 rounded-2xl border border-stone-200/80 shadow-sm relative overflow-hidden group hover:border-coffee-300 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Penjualan Hari Ini</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fa-solid fa-wallet text-sm"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-stone-900 tracking-tight">
                Rp {{ number_format($salesToday, 0, ',', '.') }}
            </div>
            <div class="mt-2 text-[11px] text-stone-500 flex items-center justify-between">
                <span>Bulan Ini: <strong>Rp {{ number_format($salesThisMonth, 0, ',', '.') }}</strong></span>
            </div>
        </div>

        <!-- Card 2: Total Produk Kopi -->
        <div class="bg-white p-5 rounded-2xl border border-stone-200/80 shadow-sm relative overflow-hidden group hover:border-coffee-300 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Total Produk Kopi</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-boxes-stacked text-sm"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-stone-900 tracking-tight">
                {{ $totalProducts }} <span class="text-xs font-normal text-stone-500">SKU</span>
            </div>
            <div class="mt-2 text-[11px] text-stone-500 flex items-center justify-between">
                <span>{{ $totalCategories }} Kategori</span>
                <span>•</span>
                <span>{{ $totalSuppliers }} Supplier</span>
            </div>
        </div>

        <!-- Card 3: Total Transaksi Penjualan -->
        <div class="bg-white p-5 rounded-2xl border border-stone-200/80 shadow-sm relative overflow-hidden group hover:border-coffee-300 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Volume Penjualan</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="fa-solid fa-receipt text-sm"></i>
                </div>
            </div>
            <div class="text-2xl font-bold text-stone-900 tracking-tight">
                {{ $totalSalesCount }} <span class="text-xs font-normal text-stone-500">Transaksi</span>
            </div>
            <div class="mt-2 text-[11px] text-stone-500">
                <span>Total Omzet: <strong>Rp {{ number_format($salesAllTime, 0, ',', '.') }}</strong></span>
            </div>
        </div>

        <!-- Card 4: Peringatan Stok Rendah -->
        <div class="bg-white p-5 rounded-2xl border {{ $lowStockCount > 0 ? 'border-amber-300 bg-amber-50/20' : 'border-stone-200/80' }} shadow-sm relative overflow-hidden group hover:border-amber-400 transition">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider {{ $lowStockCount > 0 ? 'text-amber-800' : 'text-stone-500' }}">Stok Menipis</span>
                <div class="w-9 h-9 rounded-xl {{ $lowStockCount > 0 ? 'bg-amber-100 text-amber-700 animate-pulse' : 'bg-stone-100 text-stone-500' }} flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                </div>
            </div>
            <div class="text-2xl font-bold {{ $lowStockCount > 0 ? 'text-amber-900' : 'text-stone-900' }} tracking-tight">
                {{ $lowStockCount }} <span class="text-xs font-normal text-stone-500">Produk</span>
            </div>
            <div class="mt-2 text-[11px] text-stone-500 flex items-center justify-between">
                <span>Perlu Restock Segera</span>
                <a href="#low-stock-section" class="text-amber-700 font-semibold underline">Lihat Detail &darr;</a>
            </div>
        </div>
    </div>

    <!-- Low Stock Table Section (PPT Slide 13 & 18: Produk stok rendah) -->
    <div id="low-stock-section" class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-stone-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full {{ $lowStockCount > 0 ? 'bg-amber-500 animate-ping' : 'bg-emerald-500' }}"></span>
                    <h2 class="text-lg font-bold text-coffee-950 font-serif">Daftar Peringatan Stok Rendah (Low-Stock Alert)</h2>
                </div>
                <p class="text-xs text-stone-500 mt-0.5">Produk dengan stok fisik saat ini kurang dari atau sama dengan batas minimum stok (stok &le; minimum_stock)</p>
            </div>
            <a href="{{ route('purchases.create') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-coffee-700 hover:text-coffee-900 bg-coffee-50 px-3 py-1.5 rounded-xl border border-coffee-200 transition">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Buat Pengadaan Stok-In</span>
            </a>
        </div>

        @if($lowStockProducts->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-100">
                        <tr>
                            <th class="py-3 px-6">SKU</th>
                            <th class="py-3 px-6">Nama Produk Kopi</th>
                            <th class="py-3 px-6">Kategori</th>
                            <th class="py-3 px-6 text-center">Sisa Stok</th>
                            <th class="py-3 px-6 text-center">Batas Minimum</th>
                            <th class="py-3 px-6 text-right">Harga Beli Terakhir</th>
                            <th class="py-3 px-6 text-center">Status</th>
                            <th class="py-3 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 text-stone-700">
                        @foreach($lowStockProducts as $product)
                            <tr class="hover:bg-amber-50/30 transition">
                                <td class="py-3.5 px-6 font-mono font-semibold text-coffee-900">{{ $product->sku }}</td>
                                <td class="py-3.5 px-6 font-semibold text-stone-900">{{ $product->name }}</td>
                                <td class="py-3.5 px-6 text-stone-600">{{ $product->category->name ?? '-' }}</td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-center font-medium text-stone-600">{{ $product->minimum_stock }}</td>
                                <td class="py-3.5 px-6 text-right font-medium">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">
                                        Stok Menipis
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-right space-x-2">
                                    <a href="{{ route('purchases.create') }}" class="text-coffee-700 hover:text-coffee-900 font-semibold underline">Beli Stok</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center text-stone-500 text-xs">
                <i class="fa-regular fa-circle-check text-emerald-500 text-2xl mb-2 block"></i>
                Semua stok produk kopi saat ini berada dalam kondisi aman di atas batas minimum stok.
            </div>
        @endif
    </div>

    <!-- Recent Transactions Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Recent Sales (Penjualan Terakhir) -->
        <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-stone-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-coffee-950 font-serif">5 Penjualan Terakhir</h3>
                    <p class="text-[11px] text-stone-500">Transaksi kasir POS & order customer</p>
                </div>
                <a href="{{ route('sales.index') }}" class="text-xs font-semibold text-coffee-700 hover:text-coffee-900">Lihat Semua &raquo;</a>
            </div>

            <div class="divide-y divide-stone-100 text-xs">
                @forelse($recentSales as $sale)
                    <div class="p-4 hover:bg-stone-50/50 transition flex items-center justify-between">
                        <div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('sales.show', $sale) }}" class="font-mono font-semibold text-coffee-800 hover:underline">
                                    {{ $sale->sale_number }}
                                </a>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase {{ $sale->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $sale->status }}
                                </span>
                            </div>
                            <div class="text-stone-500 text-[11px] mt-0.5">
                                {{ $sale->customer_name }} • {{ $sale->items->count() }} item • {{ $sale->sold_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-stone-900">Rp {{ number_format($sale->total, 0, ',', '.') }}</div>
                            <div class="text-[10px] text-stone-400 uppercase tracking-wider">{{ $sale->payment_method }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-stone-400">Belum ada transaksi penjualan</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Purchases (Pengadaan Stok-In Terakhir) -->
        <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-stone-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-coffee-950 font-serif">5 Pengadaan Terakhir (Stock-In)</h3>
                    <p class="text-[11px] text-stone-500">Pemasukan stok biji kopi dari perkebunan/supplier</p>
                </div>
                <a href="{{ route('purchases.index') }}" class="text-xs font-semibold text-coffee-700 hover:text-coffee-900">Lihat Semua &raquo;</a>
            </div>

            <div class="divide-y divide-stone-100 text-xs">
                @forelse($recentPurchases as $purchase)
                    <div class="p-4 hover:bg-stone-50/50 transition flex items-center justify-between">
                        <div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('purchases.show', $purchase) }}" class="font-mono font-semibold text-coffee-800 hover:underline">
                                    {{ $purchase->purchase_number }}
                                </a>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase {{ $purchase->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $purchase->status }}
                                </span>
                            </div>
                            <div class="text-stone-500 text-[11px] mt-0.5">
                                {{ $purchase->supplier->name ?? 'Supplier' }} • {{ $purchase->items->count() }} item • {{ $purchase->purchased_at->diffForHumans() }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-stone-900">Rp {{ number_format($purchase->total, 0, ',', '.') }}</div>
                            <div class="text-[10px] text-stone-400">Oleh: {{ $purchase->user->name ?? 'User' }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-stone-400">Belum ada transaksi pembelian</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
