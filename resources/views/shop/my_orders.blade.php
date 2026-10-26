@extends('layouts.app')

@section('title', 'Pesanan Saya — KOPI SENJA')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">

    <!-- Header Breadcrumbs -->
    <div class="flex items-center space-x-2 text-xs text-stone-500">
        <a href="{{ route('home') }}" class="hover:text-coffee-700">Beranda</a>
        <span>/</span>
        <a href="{{ route('customer.dashboard') }}" class="hover:text-coffee-700">Dashboard</a>
        <span>/</span>
        <span class="text-espresso font-semibold">Pesanan Saya</span>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold font-serif text-espresso">Riwayat & Status Pesanan</h1>
            <p class="text-xs text-stone-500 mt-1">Pantau status pesanan kopi Anda yang sedang diproses barista secara real-time.</p>
        </div>

        <a href="{{ route('shop.index') }}" class="px-5 py-2.5 bg-espresso hover:bg-coffee-800 text-amber-300 font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition inline-flex items-center space-x-2 self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i>
            <span>Pesan Menu Baru</span>
        </a>
    </div>

    <!-- Filter Status Tabs -->
    <div class="flex flex-wrap items-center gap-2 border-b border-stone-200 pb-3">
        <a href="{{ route('shop.my-orders') }}" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ empty($status) ? 'bg-coffee-800 text-white shadow-sm' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200' }}">
            Semua Pesanan
        </a>
        <a href="{{ route('shop.my-orders', ['status' => 'pending']) }}" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'pending' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200' }}">
            Menunggu Konfirmasi
        </a>
        <a href="{{ route('shop.my-orders', ['status' => 'preparing']) }}" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'preparing' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200' }}">
            Sedang Disiapkan
        </a>
        <a href="{{ route('shop.my-orders', ['status' => 'ready']) }}" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'ready' ? 'bg-teal-600 text-white shadow-sm' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200' }}">
            Siap Diambil
        </a>
        <a href="{{ route('shop.my-orders', ['status' => 'completed']) }}" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'completed' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200' }}">
            Selesai
        </a>
        <a href="{{ route('shop.my-orders', ['status' => 'cancelled']) }}" 
           class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'cancelled' ? 'bg-red-600 text-white shadow-sm' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200' }}">
            Dibatalkan
        </a>
    </div>

    <!-- Orders List -->
    <div class="space-y-6">
        @forelse($orders as $order)
            <div class="bg-white rounded-3xl border border-stone-200/80 shadow-sm overflow-hidden hover:shadow-md transition duration-300">
                
                <!-- Order Card Header -->
                <div class="p-6 bg-stone-50/70 border-b border-stone-100 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-white border border-stone-200 text-coffee-800 flex items-center justify-center font-bold text-sm shadow-sm">
                            ☕
                        </div>
                        <div>
                            <span class="text-sm font-bold font-mono text-espresso block">{{ $order->sale_number }}</span>
                            <span class="text-[11px] text-stone-400">
                                {{ $order->sold_at ? $order->sold_at->format('d M Y, H:i') : $order->created_at->format('d M Y, H:i') }} WIB
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <!-- Order Type Badge -->
                        <span class="text-[11px] font-semibold text-stone-700 bg-white px-3 py-1 rounded-full border border-stone-200">
                            {{ $order->order_type_label }}
                        </span>

                        <!-- Status Badge -->
                        <span class="text-xs font-bold px-3 py-1 rounded-full border 
                            {{ $order->status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                            {{ $order->status === 'confirmed' ? 'bg-sky-50 text-sky-700 border-sky-200' : '' }}
                            {{ $order->status === 'preparing' ? 'bg-indigo-50 text-indigo-700 border-indigo-200 animate-pulse' : '' }}
                            {{ $order->status === 'ready' ? 'bg-teal-50 text-teal-700 border-teal-200' : '' }}
                            {{ $order->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                            {{ $order->status === 'cancelled' ? 'bg-red-50 text-red-700 border-red-200' : '' }}
                        ">
                            {{ $order->status_label }}
                        </span>
                    </div>
                </div>

                <!-- Order Card Body: Items List -->
                <div class="p-6 space-y-4">
                    
                    <!-- Table Items -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead class="text-[10px] text-stone-400 uppercase tracking-wider border-b border-stone-100">
                                <tr>
                                    <th class="pb-2">Menu Produk</th>
                                    <th class="pb-2 text-center">Kuantitas</th>
                                    <th class="pb-2 text-right">Harga Satuan</th>
                                    <th class="pb-2 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-50">
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="py-2.5 font-medium text-espresso flex items-center space-x-2">
                                            <span>{{ $item->product->name ?? 'Menu Produk' }}</span>
                                            @if($item->product && $item->product->category)
                                                <span class="text-[9px] text-coffee-600 bg-coffee-50 px-1.5 py-0.5 rounded">
                                                    {{ $item->product->category->name }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-2.5 text-center text-stone-600">{{ $item->quantity }}</td>
                                        <td class="py-2.5 text-right text-stone-600">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="py-2.5 text-right font-bold text-coffee-800 font-serif">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($order->notes)
                        <div class="p-3 bg-stone-50 rounded-2xl text-[11px] text-stone-600 border border-stone-100">
                            <span class="font-bold text-stone-700"><i class="fa-solid fa-note-sticky text-amber-500 mr-1"></i> Catatan Pelanggan:</span>
                            <span class="italic">“{{ $order->notes }}”</span>
                        </div>
                    @endif

                    <!-- Card Footer: Summary & Actions -->
                    <div class="pt-4 border-t border-stone-100 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <span class="text-[10px] text-stone-400 uppercase tracking-wider block font-semibold">Total Tagihan</span>
                            <span class="text-xl font-bold font-serif text-coffee-900">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] text-stone-400 block">Metode Pembayaran: {{ strtoupper($order->payment_method) }}</span>
                        </div>

                        <div class="flex items-center space-x-3">
                            <a href="{{ route('customer.orders.show', $order) }}" class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-semibold rounded-xl transition inline-flex items-center space-x-1.5">
                                <i class="fa-solid fa-receipt"></i>
                                <span>Lihat Struk Nota</span>
                            </a>

                            <!-- Cancel Button (Only if status is still pending) -->
                            @if($order->canBeCancelledByCustomer())
                                <form method="POST" action="{{ route('customer.orders.cancel', $order) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Stok akan otomatis dikembalikan.')">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold rounded-xl border border-red-200 transition inline-flex items-center space-x-1.5">
                                        <i class="fa-solid fa-ban"></i>
                                        <span>Batalkan Pesanan</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>

            </div>
        @empty
            <div class="bg-white rounded-3xl p-12 text-center border border-stone-200/80 shadow-sm space-y-4">
                <div class="w-16 h-16 rounded-full bg-stone-100 text-stone-400 flex items-center justify-center mx-auto text-2xl">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <h3 class="text-lg font-bold font-serif text-espresso">Belum Ada Riwayat Pesanan</h3>
                <p class="text-xs text-stone-500 max-w-sm mx-auto">
                    Anda belum memiliki riwayat pesanan dengan filter status yang dipilih.
                </p>
                <div class="pt-2">
                    <a href="{{ route('shop.index') }}" class="px-5 py-2.5 bg-espresso hover:bg-coffee-800 text-amber-300 text-xs font-bold uppercase tracking-wider rounded-xl shadow-md transition inline-flex items-center space-x-2">
                        <i class="fa-solid fa-mug-hot"></i>
                        <span>Pesan Menu Kopi</span>
                    </a>
                </div>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="pt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
