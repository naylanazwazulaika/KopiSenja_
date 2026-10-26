@extends('layouts.app')

@section('title', 'Dashboard Pelanggan — KOPI SENJA')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">

    <!-- Customer Greeting Hero -->
    <div class="bg-gradient-to-r from-espresso via-coffee-950 to-coffee-900 rounded-3xl p-8 sm:p-10 text-white shadow-xl relative overflow-hidden border border-coffee-800/40">
        <div class="relative z-10 space-y-4 max-w-2xl">
            <span class="inline-flex items-center space-x-2 bg-amber-400/20 text-amber-300 text-xs px-3.5 py-1 rounded-full font-bold uppercase tracking-wider border border-amber-400/30">
                <i class="fa-solid fa-sparkles text-[10px]"></i>
                <span>Ruang Pelanggan Kopi Senja</span>
            </span>

            <h1 class="text-3xl sm:text-4xl font-bold font-serif text-cream">
                Halo, {{ $user->name }}!
            </h1>

            <p class="text-xs sm:text-sm text-stone-300 leading-relaxed font-light">
                Selamat datang kembali di KOPI SENJA — <em>“Temukan Cerita di Setiap Tegukan.”</em> Pantau pesanan kopi Anda yang sedang diseduh barista atau jelajahi koleksi pastry segar hari ini.
            </p>

            <div class="pt-2 flex flex-wrap items-center gap-3 text-xs">
                <a href="{{ route('shop.index') }}" class="px-5 py-2.5 bg-amber-400 hover:bg-amber-300 text-espresso font-bold uppercase tracking-wider rounded-xl shadow-md transition inline-flex items-center space-x-2">
                    <i class="fa-solid fa-mug-hot"></i>
                    <span>Pesan Kopi Sekarang</span>
                </a>
                <a href="{{ route('shop.my-orders') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl border border-white/20 transition inline-flex items-center space-x-2">
                    <i class="fa-solid fa-receipt"></i>
                    <span>Lihat Semua Pesanan ({{ $totalOrdersCount }})</span>
                </a>
            </div>
        </div>

        <div class="absolute -right-4 -bottom-8 text-white/5 text-[200px] pointer-events-none font-serif select-none">
            ☕
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
            <div>
                <span class="text-[10px] text-stone-400 uppercase tracking-wider font-semibold block">Total Pesanan</span>
                <span class="text-xl font-bold font-serif text-espresso">{{ $totalOrdersCount }} Transaksi</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div>
                <span class="text-[10px] text-stone-400 uppercase tracking-wider font-semibold block">Total Pengeluaran</span>
                <span class="text-xl font-bold font-serif text-coffee-800">Rp {{ number_format($totalSpent, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-stone-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-coffee-50 text-coffee-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div>
                <span class="text-[10px] text-stone-400 uppercase tracking-wider font-semibold block">Pesanan Aktif</span>
                <span class="text-xl font-bold font-serif text-espresso">{{ $activeOrders->count() }} Sedang Proses</span>
            </div>
        </div>
    </div>

    <!-- PESANAN AKTIF DENGAN STATUS TRACKER TIMELINE -->
    @if($activeOrders->isNotEmpty())
        <div class="space-y-4">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                <h2 class="text-xl font-bold font-serif text-espresso">Status Pesanan Aktif Saat Ini</h2>
            </div>

            <div class="space-y-6">
                @foreach($activeOrders as $activeOrder)
                    <div class="bg-white rounded-3xl p-6 border-2 border-coffee-200 shadow-md space-y-6">
                        
                        <!-- Header info -->
                        <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-stone-100">
                            <div>
                                <span class="text-xs font-bold text-coffee-700 font-mono">{{ $activeOrder->sale_number }}</span>
                                <span class="text-[11px] text-stone-400 ml-2">Dipesan pada {{ $activeOrder->sold_at->format('H:i') }} WIB</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs font-bold text-stone-700 bg-stone-100 px-3 py-1 rounded-full">
                                    {{ $activeOrder->order_type_label }}
                                </span>
                                <span class="text-xs font-bold px-3 py-1 rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                                    {{ $activeOrder->status_label }}
                                </span>
                            </div>
                        </div>

                        <!-- Interactive Status Progress Bar / Stepper -->
                        @php
                            $stepIndex = match($activeOrder->status) {
                                'pending' => 1,
                                'confirmed' => 2,
                                'preparing' => 3,
                                'ready' => 4,
                                'completed' => 5,
                                default => 1
                            };
                        @endphp
                        <div class="py-2">
                            <div class="grid grid-cols-4 gap-2 text-center relative">
                                
                                <!-- Step 1: Pending -->
                                <div class="space-y-2">
                                    <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center text-xs font-bold {{ $stepIndex >= 1 ? 'bg-coffee-800 text-amber-300 ring-4 ring-coffee-100' : 'bg-stone-200 text-stone-500' }}">
                                        <i class="fa-solid fa-clock"></i>
                                    </div>
                                    <span class="text-[11px] font-bold block {{ $stepIndex >= 1 ? 'text-espresso' : 'text-stone-400' }}">Menunggu Konfirmasi</span>
                                </div>

                                <!-- Step 2: Confirmed -->
                                <div class="space-y-2">
                                    <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center text-xs font-bold {{ $stepIndex >= 2 ? 'bg-coffee-800 text-amber-300 ring-4 ring-coffee-100' : 'bg-stone-200 text-stone-500' }}">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                    <span class="text-[11px] font-bold block {{ $stepIndex >= 2 ? 'text-espresso' : 'text-stone-400' }}">Dikonfirmasi</span>
                                </div>

                                <!-- Step 3: Preparing -->
                                <div class="space-y-2">
                                    <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center text-xs font-bold {{ $stepIndex >= 3 ? 'bg-coffee-800 text-amber-300 ring-4 ring-coffee-100 animate-bounce' : 'bg-stone-200 text-stone-500' }}">
                                        <i class="fa-solid fa-mug-saucer"></i>
                                    </div>
                                    <span class="text-[11px] font-bold block {{ $stepIndex >= 3 ? 'text-espresso' : 'text-stone-400' }}">Sedang Disiapkan</span>
                                </div>

                                <!-- Step 4: Ready -->
                                <div class="space-y-2">
                                    <div class="w-8 h-8 rounded-full mx-auto flex items-center justify-center text-xs font-bold {{ $stepIndex >= 4 ? 'bg-emerald-600 text-white ring-4 ring-emerald-100 animate-pulse' : 'bg-stone-200 text-stone-500' }}">
                                        <i class="fa-solid fa-bell-concierge"></i>
                                    </div>
                                    <span class="text-[11px] font-bold block {{ $stepIndex >= 4 ? 'text-emerald-700' : 'text-stone-400' }}">Siap Diambil</span>
                                </div>
                            </div>
                        </div>

                        <!-- Items ordered -->
                        <div class="bg-stone-50 p-4 rounded-2xl space-y-2 text-xs">
                            <span class="text-[10px] text-stone-400 uppercase tracking-wider font-bold block">Menu yang Dipesan:</span>
                            @foreach($activeOrder->items as $item)
                                <div class="flex justify-between">
                                    <span class="text-stone-700 font-medium">{{ $item->product->name ?? 'Menu' }} &times; {{ $item->quantity }}</span>
                                    <span class="font-bold text-coffee-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                            @if($activeOrder->notes)
                                <p class="text-[11px] text-stone-500 italic pt-1 border-t border-stone-200">
                                    “{{ $activeOrder->notes }}”
                                </p>
                            @endif
                        </div>

                        <!-- Footer actions -->
                        <div class="flex items-center justify-between pt-2">
                            <a href="{{ route('customer.orders.show', $activeOrder) }}" class="text-xs font-bold text-coffee-700 hover:text-espresso underline">
                                Buka Rincian Nota Digital
                            </a>

                            @if($activeOrder->canBeCancelledByCustomer())
                                <form method="POST" action="{{ route('customer.orders.cancel', $activeOrder) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Stok akan otomatis dipulihkan.')">
                                    @csrf
                                    <button type="submit" class="px-3.5 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold rounded-xl border border-red-200 transition">
                                        <i class="fa-solid fa-ban mr-1"></i> Batalkan Pesanan
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- RIWAYAT PESANAN TERAKHIR & REKOMENDASI MENU -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Riwayat Pesanan (2 cols) -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold font-serif text-espresso">Riwayat Pesanan Terakhir</h2>
                <a href="{{ route('shop.my-orders') }}" class="text-xs font-bold text-coffee-700 hover:text-espresso">
                    Lihat Semua
                </a>
            </div>

            <div class="bg-white rounded-3xl border border-stone-200/80 shadow-sm divide-y divide-stone-100 overflow-hidden">
                @forelse($recentOrders as $order)
                    <div class="p-4 sm:p-5 flex items-center justify-between gap-4 hover:bg-stone-50/50 transition">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-xs text-espresso font-mono">{{ $order->sale_number }}</span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold border 
                                    {{ $order->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                                    {{ $order->status === 'cancelled' ? 'bg-red-50 text-red-700 border-red-200' : '' }}
                                    {{ !in_array($order->status, ['completed', 'cancelled']) ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                                ">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                            <p class="text-[11px] text-stone-500">
                                {{ $order->sold_at->format('d M Y, H:i') }} • {{ $order->items->count() }} jenis menu
                            </p>
                        </div>

                        <div class="text-right space-y-1">
                            <span class="block text-sm font-bold font-serif text-coffee-800">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </span>
                            <a href="{{ route('customer.orders.show', $order) }}" class="text-[11px] text-coffee-700 hover:underline font-semibold block">
                                Rincian
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-stone-500">
                        Belum ada riwayat pesanan sebelumnya.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Rekomendasi Menu Hari Ini (1 col) -->
        <div class="space-y-4">
            <h2 class="text-xl font-bold font-serif text-espresso">Rekomendasi Barista</h2>

            <div class="space-y-3">
                @foreach($featuredProducts as $fp)
                    <a href="{{ route('shop.show', $fp) }}" class="bg-white p-3.5 rounded-2xl border border-stone-200/80 shadow-sm hover:shadow-md hover:border-coffee-300 transition flex items-center space-x-3.5 group">
                        <img src="{{ $fp->image_url }}" alt="{{ $fp->name }}" class="w-14 h-14 object-cover rounded-xl flex-shrink-0">
                        <div class="overflow-hidden">
                            <span class="text-[9px] uppercase font-bold text-coffee-600 block">{{ $fp->category->name ?? 'Menu' }}</span>
                            <h4 class="text-xs font-bold text-espresso group-hover:text-coffee-700 truncate">{{ $fp->name }}</h4>
                            <span class="text-xs font-bold text-coffee-800 font-serif block mt-0.5">
                                Rp {{ number_format($fp->selling_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
