@extends('layouts.app')

@section('title', 'Dashboard Operasional Staff — KOPI SENJA')

@section('content')
<div class="space-y-8">

    <!-- Staff Header Banner -->
    <div class="bg-gradient-to-r from-coffee-950 via-espresso to-coffee-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center md:justify-between gap-6 border border-coffee-800/40">
        <div class="space-y-2">
            <span class="inline-flex items-center space-x-2 bg-amber-400/20 text-amber-300 text-xs px-3.5 py-1 rounded-full font-bold uppercase tracking-wider border border-amber-400/30">
                <i class="fa-solid fa-mug-saucer text-[10px]"></i>
                <span>Staff & Cashier Operations</span>
            </span>
            <h1 class="text-2xl sm:text-3xl font-bold font-serif text-cream">
                Dashboard Staff / Admin (Operasional Toko & Kasir)
            </h1>
            <p class="text-xs sm:text-sm text-stone-300 font-light max-w-xl">
                Pantau antrean pesanan masuk, proses transaksi kasir walk-in, dan kelola pengadaan stok barang dari supplier.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('sales.create') }}" class="px-5 py-3 bg-amber-400 hover:bg-amber-300 text-espresso text-xs font-bold uppercase tracking-wider rounded-xl shadow-lg shadow-amber-400/20 transition inline-flex items-center space-x-2">
                <i class="fa-solid fa-cash-register"></i>
                <span>Buka Kasir POS Baru</span>
            </a>
            <a href="{{ route('purchases.create') }}" class="px-5 py-3 bg-white/10 hover:bg-white/20 text-white text-xs font-bold uppercase tracking-wider rounded-xl border border-white/20 transition inline-flex items-center space-x-2">
                <i class="fa-solid fa-cart-plus"></i>
                <span>Input Pembelian Stok (PO)</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Transaksi Hari Ini -->
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <span class="text-[10px] text-stone-400 uppercase tracking-wider font-semibold block">Transaksi Hari Ini</span>
                <span class="text-2xl font-bold font-serif text-espresso">{{ $transactionsTodayCount }}</span>
                <span class="text-[11px] text-emerald-700 font-semibold block mt-0.5">
                    Rp {{ number_format($salesTodayTotal, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Menunggu Konfirmasi -->
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-bell animate-pulse"></i>
            </div>
            <div>
                <span class="text-[10px] text-stone-400 uppercase tracking-wider font-semibold block">Menunggu Konfirmasi</span>
                <span class="text-2xl font-bold font-serif text-amber-700">{{ $pendingOrders->count() }}</span>
                <span class="text-[10px] text-stone-400 block mt-0.5">Pesanan Online Baru</span>
            </div>
        </div>

        <!-- Sedang Disiapkan -->
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-fire-burner"></i>
            </div>
            <div>
                <span class="text-[10px] text-stone-400 uppercase tracking-wider font-semibold block">Sedang Disiapkan</span>
                <span class="text-2xl font-bold font-serif text-indigo-700">{{ $preparingOrders->count() }}</span>
                <span class="text-[10px] text-stone-400 block mt-0.5">Antrean Seduh Barista</span>
            </div>
        </div>

        <!-- Produk Stok Rendah -->
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl {{ $lowStockProducts->count() > 0 ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <span class="text-[10px] text-stone-400 uppercase tracking-wider font-semibold block">Stok Menipis</span>
                <span class="text-2xl font-bold font-serif {{ $lowStockProducts->count() > 0 ? 'text-red-600' : 'text-emerald-700' }}">
                    {{ $lowStockProducts->count() }}
                </span>
                <span class="text-[10px] text-stone-400 block mt-0.5">Perlu Order ke Supplier</span>
            </div>
        </div>

    </div>

    <!-- ANTREAN PESANAN MASUK (PENDING & PREPARING) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span>
                <h2 class="text-xl font-bold font-serif text-espresso">Antrean Pesanan Masuk (Perlu Diproses)</h2>
            </div>
            <a href="{{ route('customer-orders.index') }}" class="text-xs font-bold text-coffee-700 hover:text-espresso">
                Lihat Semua Antrean Pesanan &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Pending Orders (Menunggu Konfirmasi) -->
            <div class="bg-white rounded-3xl p-6 border border-amber-200/80 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <h3 class="font-bold text-sm text-espresso">1. Menunggu Konfirmasi Barista</h3>
                    </div>
                    <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                        {{ $pendingOrders->count() }} Pesanan
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($pendingOrders as $pOrder)
                        <div class="p-4 rounded-2xl bg-amber-50/40 border border-amber-100 space-y-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-bold text-xs text-espresso font-mono">{{ $pOrder->sale_number }}</span>
                                    <span class="text-[11px] text-stone-500 block">
                                        Pemesan: <strong>{{ $pOrder->customer_name }}</strong> ({{ $pOrder->order_type_label }})
                                    </span>
                                </div>
                                <span class="font-bold text-xs text-coffee-800 font-serif">
                                    Rp {{ number_format($pOrder->total, 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Items List -->
                            <div class="text-[11px] text-stone-600 space-y-1 bg-white/70 p-2.5 rounded-xl border border-stone-100">
                                @foreach($pOrder->items as $it)
                                    <div class="flex justify-between">
                                        <span>{{ $it->product->name ?? 'Menu' }} &times; {{ $it->quantity }}</span>
                                        <span class="text-stone-400">Rp {{ number_format($it->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                                @if($pOrder->notes)
                                    <p class="text-[10px] text-stone-500 italic pt-1 border-t border-stone-100">
                                        “{{ $pOrder->notes }}”
                                    </p>
                                @endif
                            </div>

                            <!-- Quick Action Buttons -->
                            <div class="flex items-center justify-end space-x-2 pt-1">
                                <form method="POST" action="{{ route('customer-orders.update-status', $pOrder) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="px-3.5 py-1.5 bg-coffee-800 hover:bg-espresso text-amber-300 text-xs font-bold rounded-xl shadow-sm transition">
                                        <i class="fa-solid fa-check mr-1"></i> Konfirmasi Pesanan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-stone-400 py-4 text-center italic">Tidak ada pesanan yang menunggu konfirmasi saat ini.</p>
                    @endforelse
                </div>
            </div>

            <!-- Preparing Orders (Sedang Disiapkan) -->
            <div class="bg-white rounded-3xl p-6 border border-indigo-200/80 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                        <h3 class="font-bold text-sm text-espresso">2. Dikonfirmasi / Sedang Disiapkan</h3>
                    </div>
                    <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-200">
                        {{ $preparingOrders->count() }} Pesanan
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($preparingOrders as $prepOrder)
                        <div class="p-4 rounded-2xl bg-indigo-50/30 border border-indigo-100 space-y-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-bold text-xs text-espresso font-mono">{{ $prepOrder->sale_number }}</span>
                                    <span class="text-[11px] text-stone-500 block">
                                        Pemesan: <strong>{{ $prepOrder->customer_name }}</strong> ({{ $prepOrder->order_type_label }})
                                    </span>
                                </div>
                                <span class="font-bold text-xs text-coffee-800 font-serif">
                                    Rp {{ number_format($prepOrder->total, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="text-[11px] text-stone-600 space-y-1 bg-white/70 p-2.5 rounded-xl border border-stone-100">
                                @foreach($prepOrder->items as $it)
                                    <div class="flex justify-between">
                                        <span>{{ $it->product->name ?? 'Menu' }} &times; {{ $it->quantity }}</span>
                                        <span class="text-stone-400">Rp {{ number_format($it->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Action to mark Ready -->
                            <div class="flex items-center justify-between pt-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-700">
                                    {{ $prepOrder->status_label }}
                                </span>

                                <form method="POST" action="{{ route('customer-orders.update-status', $prepOrder) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="ready">
                                    <button type="submit" class="px-3.5 py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                        <i class="fa-solid fa-bell-concierge mr-1"></i> Tandai Siap Diambil
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-stone-400 py-4 text-center italic">Tidak ada pesanan yang sedang disiapkan.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- STOK RENDAH & RIWAYAT PENJUALAN TERBARU -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Tabel Stok Rendah (1 col) -->
        <div class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-sm text-espresso font-serif">
                    <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-1.5"></i> Stok Produk Menipis
                </h3>
                <a href="{{ route('purchases.create') }}" class="text-[11px] font-bold text-coffee-700 hover:text-espresso">
                    + Order Stok
                </a>
            </div>

            <div class="divide-y divide-stone-100">
                @forelse($lowStockProducts->take(6) as $lsp)
                    <div class="py-2.5 flex items-center justify-between text-xs">
                        <div class="space-y-0.5">
                            <span class="font-bold text-espresso block truncate max-w-[170px]">{{ $lsp->name }}</span>
                            <span class="text-[10px] text-stone-400">Min. Stok: {{ $lsp->minimum_stock }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200 font-mono">
                                Sisa {{ $lsp->stock }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-stone-400 py-4 text-center italic">Semua stok produk kopi & pastry dalam kondisi aman.</p>
                @endforelse
            </div>
        </div>

        <!-- Riwayat Transaksi Kasir Terbaru (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-stone-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-sm text-espresso font-serif">
                    <i class="fa-solid fa-cash-register text-coffee-600 mr-1.5"></i> Transaksi Kasir Terbaru
                </h3>
                <a href="{{ route('sales.index') }}" class="text-[11px] font-bold text-coffee-700 hover:text-espresso">
                    Lihat Semua Transaksi &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="text-[10px] text-stone-400 uppercase tracking-wider border-b border-stone-100">
                        <tr>
                            <th class="pb-2">No. Transaksi</th>
                            <th class="pb-2">Pelanggan</th>
                            <th class="pb-2">Tipe</th>
                            <th class="pb-2">Status</th>
                            <th class="pb-2 text-right">Total</th>
                            <th class="pb-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @forelse($recentSales as $sale)
                            <tr>
                                <td class="py-2.5 font-bold font-mono text-espresso">{{ $sale->sale_number }}</td>
                                <td class="py-2.5 text-stone-600">{{ $sale->customer_name }}</td>
                                <td class="py-2.5 text-stone-500">{{ $sale->order_type_label }}</td>
                                <td class="py-2.5">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border
                                        {{ $sale->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                                        {{ $sale->status === 'cancelled' ? 'bg-red-50 text-red-700 border-red-200' : '' }}
                                        {{ !in_array($sale->status, ['completed', 'cancelled']) ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                                    ">
                                        {{ $sale->status_label }}
                                    </span>
                                </td>
                                <td class="py-2.5 text-right font-bold text-coffee-800 font-serif">
                                    Rp {{ number_format($sale->total, 0, ',', '.') }}
                                </td>
                                <td class="py-2.5 text-center">
                                    <a href="{{ route('sales.show', $sale) }}" class="text-coffee-700 hover:text-espresso font-semibold">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-xs text-stone-400 italic">Belum ada transaksi penjualan hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
