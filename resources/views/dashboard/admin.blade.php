@extends('layouts.app')

@section('title', 'Dashboard Utama Admin & Pemilik — KOPI SENJA')

@section('content')
<div class="space-y-8">

    <!-- Admin Top Header Banner -->
    <div class="bg-gradient-to-r from-espresso via-coffee-950 to-coffee-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center md:justify-between gap-6 border border-coffee-800/40">
        <div class="space-y-2">
            <span class="inline-flex items-center space-x-2 bg-amber-400/20 text-amber-300 text-xs px-3.5 py-1 rounded-full font-bold uppercase tracking-wider border border-amber-400/30">
                <i class="fa-solid fa-crown text-[10px]"></i>
                <span>Executive Owner Suite</span>
            </span>
            <h1 class="text-2xl sm:text-3xl font-bold font-serif text-cream">
                Dashboard Owner (Pemilik Kopi Senja)
            </h1>
            <p class="text-xs sm:text-sm text-stone-300 font-light max-w-xl">
                Ringkasan komprehensif performa penjualan, ketersediaan inventaris biji kopi & pastry, serta aktivitas operasional harian.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('reports.index') }}" class="px-5 py-3 bg-amber-400 hover:bg-amber-300 text-espresso text-xs font-bold uppercase tracking-wider rounded-xl shadow-lg shadow-amber-400/20 transition inline-flex items-center space-x-2">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Laporan Keuangan</span>
            </a>
            <a href="{{ route('products.create') }}" class="px-5 py-3 bg-white/10 hover:bg-white/20 text-white text-xs font-bold uppercase tracking-wider rounded-xl border border-white/20 transition inline-flex items-center space-x-2">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Produk</span>
            </a>
        </div>
    </div>

    <!-- METRICS GRID (Dihitung Real-time dari Database) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        
        <!-- Penjualan Hari Ini -->
        <div class="bg-white p-4 rounded-3xl border border-stone-200/80 shadow-sm space-y-1">
            <span class="text-[10px] text-stone-400 uppercase tracking-wider font-semibold block">Omset Hari Ini</span>
            <span class="text-lg font-bold font-serif text-coffee-800 block truncate">
                Rp {{ number_format($salesToday, 0, ',', '.') }}
            </span>
            <span class="text-[10px] text-emerald-700 font-semibold block">Hari ini</span>
        </div>

        <!-- Penjualan Bulan Ini -->
        <div class="bg-white p-4 rounded-3xl border border-stone-200/80 shadow-sm space-y-1">
            <span class="text-[10px] text-stone-400 uppercase tracking-wider font-semibold block">Omset Bulan Ini</span>
            <span class="text-lg font-bold font-serif text-emerald-700 block truncate">
                Rp {{ number_format($salesThisMonth, 0, ',', '.') }}
            </span>
            <span class="text-[10px] text-stone-400 block">{{ date('F Y') }}</span>
        </div>

        <!-- Total Transaksi Penjualan -->
        <div class="bg-white p-4 rounded-3xl border border-stone-200/80 shadow-sm space-y-1">
            <span class="text-[10px] text-stone-400 uppercase tracking-wider font-semibold block">Penjualan Total</span>
            <span class="text-xl font-bold font-serif text-espresso block">
                {{ $totalSalesCount }}
            </span>
            <span class="text-[10px] text-stone-500 block">Struk kasir & online</span>
        </div>

        <!-- Total Transaksi Pembelian (Stock-In) -->
        <div class="bg-white p-4 rounded-3xl border border-stone-200/80 shadow-sm space-y-1">
            <span class="text-[10px] text-stone-400 uppercase tracking-wider font-semibold block">Pembelian (PO)</span>
            <span class="text-xl font-bold font-serif text-espresso block">
                {{ $totalPurchasesCount }}
            </span>
            <span class="text-[10px] text-stone-500 block">Faktur supplier</span>
        </div>

        <!-- Pesanan Online Pelanggan -->
        <div class="bg-white p-4 rounded-3xl border border-stone-200/80 shadow-sm space-y-1">
            <span class="text-[10px] text-stone-400 uppercase tracking-wider font-semibold block">Pesanan Online</span>
            <span class="text-xl font-bold font-serif text-indigo-700 block">
                {{ $totalCustomerOrders }}
            </span>
            <span class="text-[10px] text-stone-500 block">Dine In & Take Away</span>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white p-4 rounded-3xl border border-stone-200/80 shadow-sm space-y-1">
            <span class="text-[10px] text-stone-400 uppercase tracking-wider font-semibold block">Stok Rendah</span>
            <span class="text-xl font-bold font-serif {{ $lowStockCount > 0 ? 'text-red-600' : 'text-emerald-700' }} block">
                {{ $lowStockCount }}
            </span>
            <span class="text-[10px] {{ $lowStockCount > 0 ? 'text-red-500 font-bold' : 'text-emerald-600' }} block">
                {{ $lowStockCount > 0 ? 'Perlu Restock!' : 'Aman terkendali' }}
            </span>
        </div>

    </div>

    <!-- MASTER DATA METRICS & ORDER STATUS SUMMARY -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Master Data Overview Cards (1 col) -->
        <div class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-sm space-y-4">
            <h3 class="font-bold text-sm text-espresso font-serif pb-2 border-b border-stone-100">
                <i class="fa-solid fa-database text-coffee-600 mr-1.5"></i> Ringkasan Master Data
            </h3>

            <div class="grid grid-cols-2 gap-3 text-xs">
                <a href="{{ route('products.index') }}" class="p-3 rounded-2xl bg-stone-50 hover:bg-coffee-50 transition border border-stone-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-stone-400 uppercase font-semibold block">Produk</span>
                        <span class="text-base font-bold text-espresso">{{ $totalProducts }}</span>
                    </div>
                    <i class="fa-solid fa-mug-hot text-coffee-600"></i>
                </a>

                <a href="{{ route('categories.index') }}" class="p-3 rounded-2xl bg-stone-50 hover:bg-coffee-50 transition border border-stone-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-stone-400 uppercase font-semibold block">Kategori</span>
                        <span class="text-base font-bold text-espresso">{{ $totalCategories }}</span>
                    </div>
                    <i class="fa-solid fa-tags text-coffee-600"></i>
                </a>

                <a href="{{ route('suppliers.index') }}" class="p-3 rounded-2xl bg-stone-50 hover:bg-coffee-50 transition border border-stone-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-stone-400 uppercase font-semibold block">Supplier</span>
                        <span class="text-base font-bold text-espresso">{{ $totalSuppliers }}</span>
                    </div>
                    <i class="fa-solid fa-truck-field text-coffee-600"></i>
                </a>

                <a href="{{ route('users.index') }}" class="p-3 rounded-2xl bg-stone-50 hover:bg-coffee-50 transition border border-stone-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-stone-400 uppercase font-semibold block">Staff / Kasir</span>
                        <span class="text-base font-bold text-espresso">{{ $totalStaff }}</span>
                    </div>
                    <i class="fa-solid fa-id-badge text-coffee-600"></i>
                </a>
            </div>

            <div class="pt-2 flex items-center justify-between text-xs text-stone-500 border-t border-stone-100">
                <span>Total Pelanggan Terdaftar:</span>
                <span class="font-bold text-coffee-800">{{ $totalCustomers }} Pengguna</span>
            </div>
        </div>

        <!-- Ringkasan Status Pesanan Pelanggan (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-stone-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-stone-100">
                <h3 class="font-bold text-sm text-espresso font-serif">
                    <i class="fa-solid fa-chart-pie text-coffee-600 mr-1.5"></i> Ringkasan Alur Status Pesanan
                </h3>
                <a href="{{ route('customer-orders.index') }}" class="text-xs font-bold text-coffee-700 hover:text-espresso">
                    Kelola Pesanan Pelanggan &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200/60">
                    <span class="text-[10px] text-amber-700 font-bold uppercase tracking-wider block">Menunggu Konfirmasi</span>
                    <span class="text-2xl font-bold font-serif text-amber-900 mt-1 block">{{ $orderStatusCounts['pending'] }}</span>
                    <span class="text-[10px] text-amber-600">Pesanan baru masuk</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-sky-50 border border-sky-200/60">
                    <span class="text-[10px] text-sky-700 font-bold uppercase tracking-wider block">Dikonfirmasi</span>
                    <span class="text-2xl font-bold font-serif text-sky-900 mt-1 block">{{ $orderStatusCounts['confirmed'] }}</span>
                    <span class="text-[10px] text-sky-600">Diterima staf</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-indigo-50 border border-indigo-200/60">
                    <span class="text-[10px] text-indigo-700 font-bold uppercase tracking-wider block">Sedang Disiapkan</span>
                    <span class="text-2xl font-bold font-serif text-indigo-900 mt-1 block">{{ $orderStatusCounts['preparing'] }}</span>
                    <span class="text-[10px] text-indigo-600">Dalam seduhan barista</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-teal-50 border border-teal-200/60">
                    <span class="text-[10px] text-teal-700 font-bold uppercase tracking-wider block">Siap Diambil</span>
                    <span class="text-2xl font-bold font-serif text-teal-900 mt-1 block">{{ $orderStatusCounts['ready'] }}</span>
                    <span class="text-[10px] text-teal-600">Menunggu pickup</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200/60">
                    <span class="text-[10px] text-emerald-700 font-bold uppercase tracking-wider block">Selesai (Completed)</span>
                    <span class="text-2xl font-bold font-serif text-emerald-900 mt-1 block">{{ $orderStatusCounts['completed'] }}</span>
                    <span class="text-[10px] text-emerald-600">Sukses diserahkan</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-red-50 border border-red-200/60">
                    <span class="text-[10px] text-red-700 font-bold uppercase tracking-wider block">Dibatalkan</span>
                    <span class="text-2xl font-bold font-serif text-red-900 mt-1 block">{{ $orderStatusCounts['cancelled'] }}</span>
                    <span class="text-[10px] text-red-600">Stok dipulihkan</span>
                </div>
            </div>
        </div>

    </div>

    <!-- GRAFIK PENJUALAN & PRODUK TERLARIS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Grafik Penjualan Bulanan (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-stone-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-stone-100">
                <h3 class="font-bold text-sm text-espresso font-serif">
                    <i class="fa-solid fa-chart-column text-coffee-600 mr-1.5"></i> Tren Penjualan 6 Bulan Terakhir
                </h3>
                <span class="text-[11px] text-stone-400">Total Transaksi Valid</span>
            </div>

            <!-- Visual Bar Chart using CSS/SVG -->
            @php
                $maxRevenue = $monthlySales->max('revenue') ?: 1;
            @endphp
            <div class="pt-4 space-y-4">
                <div class="flex items-end justify-between h-48 gap-3 px-2 border-b border-stone-200 pb-2">
                    @forelse($monthlySales as $mSale)
                        @php
                            $heightPercent = max(10, min(100, round(($mSale->revenue / $maxRevenue) * 100)));
                        @endphp
                        <div class="flex-1 flex flex-col items-center justify-end h-full group">
                            <!-- Tooltip value -->
                            <span class="text-[9px] font-bold text-coffee-900 mb-1 opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                                Rp {{ number_format($mSale->revenue / 1000, 0) }}k
                            </span>
                            <div class="w-full max-w-[48px] bg-gradient-to-t from-coffee-800 to-amber-500 rounded-t-xl group-hover:from-espresso group-hover:to-amber-400 transition-all duration-300 shadow-sm"
                                 style="height: {{ $heightPercent }}%;">
                            </div>
                            <span class="text-[10px] font-bold text-stone-500 mt-2 whitespace-nowrap">
                                {{ date('M Y', strtotime($mSale->month . '-01')) }}
                            </span>
                        </div>
                    @empty
                        <div class="w-full text-center py-16 text-xs text-stone-400 italic">
                            Belum ada riwayat transaksi penjualan dalam 6 bulan terakhir.
                        </div>
                    @endforelse
                </div>
                <p class="text-[11px] text-stone-400 text-right">
                    * Pendapatan dihitung secara akurat dari transaksi penjualan berstatus <strong>Selesai (Completed)</strong>.
                </p>
            </div>
        </div>

        <!-- Produk Terlaris (1 col) -->
        <div class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-stone-100">
                <h3 class="font-bold text-sm text-espresso font-serif">
                    <i class="fa-solid fa-award text-amber-500 mr-1.5"></i> Produk Terlaris
                </h3>
                <span class="text-[10px] text-stone-400 uppercase font-semibold">Top 5</span>
            </div>

            <div class="space-y-3">
                @forelse($topSellingProducts as $index => $topProduct)
                    <div class="flex items-center justify-between text-xs py-2 border-b border-stone-50">
                        <div class="flex items-center space-x-3">
                            <span class="w-6 h-6 rounded-full {{ $index === 0 ? 'bg-amber-400 text-espresso' : 'bg-stone-100 text-stone-600' }} flex items-center justify-center font-bold text-xs flex-shrink-0">
                                {{ $index + 1 }}
                            </span>
                            <div class="space-y-0.5 max-w-[130px]">
                                <span class="font-bold text-espresso block truncate">{{ $topProduct->name }}</span>
                                <span class="text-[10px] text-stone-400">Rp {{ number_format($topProduct->selling_price, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="font-bold text-coffee-800 text-xs block font-mono">
                                {{ $topProduct->total_sold }} terjual
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-stone-400 py-4 text-center italic">Belum ada data penjualan tercatat.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- AI DEMAND FORECASTING & PREDIKSI PRODUK TERLARIS -->
    @if(isset($aiForecasts) && $aiForecasts->isNotEmpty())
        <div class="bg-gradient-to-br from-espresso via-coffee-950 to-stone-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-coffee-800/40 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="space-y-1">
                    <div class="inline-flex items-center space-x-2 bg-amber-400/20 text-amber-300 text-[11px] px-3 py-1 rounded-full font-bold uppercase tracking-wider border border-amber-400/30">
                        <i class="fa-solid fa-brain text-[10px]"></i>
                        <span>AI Forecasting & Smart Demand Prediction</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold font-serif text-cream">
                        Prediksi Kebutuhan Stok Produk Terlaris
                    </h2>
                    <p class="text-xs text-stone-300 max-w-2xl font-light">
                        Algoritma AI menganalisis kecepatan penjualan (<em>sales velocity</em>) 30 hari terakhir, menghitung estimasi hari sebelum stok habis, dan memberikan rekomendasi restock cerdas.
                    </p>
                </div>

                <a href="{{ route('purchases.create') }}" class="px-4 py-2.5 bg-amber-400 hover:bg-amber-300 text-espresso font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition inline-flex items-center space-x-2 self-start sm:self-auto">
                    <i class="fa-solid fa-cart-plus"></i>
                    <span>Buat PO Supplier Baru</span>
                </a>
            </div>

            <!-- Grid Forecast Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($aiForecasts as $forecast)
                    @php
                        $p = $forecast['product'];
                    @endphp
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/10 hover:border-amber-400/40 transition space-y-4">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="text-[10px] text-amber-300 font-mono block">{{ $p->sku }}</span>
                                <h4 class="font-bold text-sm text-white truncate max-w-[170px]">{{ $p->name }}</h4>
                            </div>

                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider border
                                {{ $forecast['urgency'] === 'KRITIS' || $forecast['urgency'] === 'HABIS' ? 'bg-red-500/20 text-red-300 border-red-500/40 animate-pulse' : '' }}
                                {{ $forecast['urgency'] === 'PERHATIAN' ? 'bg-amber-500/20 text-amber-300 border-amber-500/40' : '' }}
                                {{ $forecast['urgency'] === 'OPTIMAL' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : '' }}
                            ">
                                {{ $forecast['urgency'] }}
                            </span>
                        </div>

                        <!-- Velocity & Days to Stockout -->
                        <div class="grid grid-cols-2 gap-2 text-xs py-2 border-y border-white/10">
                            <div>
                                <span class="text-[10px] text-stone-400 block uppercase font-medium">Laju Penjualan</span>
                                <span class="font-bold text-white font-mono text-sm">{{ $forecast['daily_velocity'] }} <span class="text-[10px] text-stone-300">pcs/hari</span></span>
                            </div>
                            <div>
                                <span class="text-[10px] text-stone-400 block uppercase font-medium">Prediksi Sisa</span>
                                <span class="font-bold font-mono text-sm {{ $forecast['days_to_stockout'] <= 3 ? 'text-red-400' : 'text-amber-300' }}">
                                    @if($p->stock <= 0)
                                        0 Hari (Habis)
                                    @else
                                        ±{{ $forecast['days_to_stockout'] }} Hari Lagi
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Recommendation & Confidence -->
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-stone-300">Stok Saat Ini:</span>
                                <span class="font-bold font-mono text-white">{{ $p->stock }} unit</span>
                            </div>
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-stone-300">Prediksi Permintaan Bln Depan:</span>
                                <span class="font-bold font-mono text-amber-300">{{ $forecast['forecast_next_month'] }} unit</span>
                            </div>
                            <div class="flex justify-between items-center text-[11px] pt-1 border-t border-white/5">
                                <span class="text-stone-300 font-semibold">Saran Restock:</span>
                                <span class="font-bold font-mono {{ $forecast['suggested_restock'] > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
                                    {{ $forecast['suggested_restock'] > 0 ? '+' . $forecast['suggested_restock'] . ' unit' : 'Stok Mencukupi' }}
                                </span>
                            </div>
                        </div>

                        <!-- Confidence Bar -->
                        <div class="pt-1">
                            <div class="flex justify-between text-[10px] text-stone-400 mb-1">
                                <span>Akurasi Model</span>
                                <span class="font-mono text-stone-300">{{ $forecast['confidence_score'] }}% Confidence</span>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-gradient-to-r from-amber-400 to-emerald-400 h-1.5 rounded-full" style="width: {{ $forecast['confidence_score'] }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- TABEL STOK RENDAH & TRANSAKSI TERBARU -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Tabel Peringatan Stok Rendah -->
        <div class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-stone-100">
                <h3 class="font-bold text-sm text-espresso font-serif">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 mr-1.5"></i> Daftar Stok Rendah (Perlu Restock)
                </h3>
                <a href="{{ route('purchases.create') }}" class="text-[11px] font-bold text-coffee-700 hover:text-espresso">
                    + Buat PO Supplier
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="text-[10px] text-stone-400 uppercase tracking-wider border-b border-stone-100">
                        <tr>
                            <th class="pb-2">SKU / Nama</th>
                            <th class="pb-2 text-center">Sisa Stok</th>
                            <th class="pb-2 text-center">Batas Min.</th>
                            <th class="pb-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @forelse($lowStockProducts as $lsp)
                            <tr>
                                <td class="py-2.5">
                                    <span class="font-bold text-espresso block">{{ $lsp->name }}</span>
                                    <span class="text-[10px] text-stone-400 font-mono">{{ $lsp->sku }}</span>
                                </td>
                                <td class="py-2.5 text-center font-bold font-mono text-red-600">{{ $lsp->stock }}</td>
                                <td class="py-2.5 text-center text-stone-500 font-mono">{{ $lsp->minimum_stock }}</td>
                                <td class="py-2.5 text-center">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-50 text-red-700 border border-red-200">
                                        {{ $lsp->stock == 0 ? 'Habis' : 'Menipis' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-xs text-stone-400 italic">Semua stok produk kopi & pastry di atas batas minimum.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Transaksi Pembelian Terbaru (Stock-In) -->
        <div class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-stone-100">
                <h3 class="font-bold text-sm text-espresso font-serif">
                    <i class="fa-solid fa-truck-ramp-box text-coffee-600 mr-1.5"></i> Riwayat Pembelian (Stock-In)
                </h3>
                <a href="{{ route('purchases.index') }}" class="text-[11px] font-bold text-coffee-700 hover:text-espresso">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="text-[10px] text-stone-400 uppercase tracking-wider border-b border-stone-100">
                        <tr>
                            <th class="pb-2">No. Faktur</th>
                            <th class="pb-2">Supplier</th>
                            <th class="pb-2 text-right">Total</th>
                            <th class="pb-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @forelse($recentPurchases as $p)
                            <tr>
                                <td class="py-2.5 font-bold font-mono text-espresso">
                                    <a href="{{ route('purchases.show', $p) }}" class="hover:underline">{{ $p->purchase_number }}</a>
                                </td>
                                <td class="py-2.5 text-stone-600">{{ $p->supplier->name ?? 'Supplier' }}</td>
                                <td class="py-2.5 text-right font-bold text-coffee-800 font-serif">
                                    Rp {{ number_format($p->total, 0, ',', '.') }}
                                </td>
                                <td class="py-2.5 text-center">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $p->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-xs text-stone-400 italic">Belum ada transaksi pembelian stok dari supplier.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
