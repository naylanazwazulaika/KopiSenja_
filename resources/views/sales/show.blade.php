@extends('layouts.app')

@section('title', 'Struk Penjualan: ' . $sale->sale_number)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="inline-flex items-center space-x-2 text-xs font-semibold text-coffee-700 bg-coffee-100 px-3 py-1 rounded-full mb-1">
                <i class="fa-solid fa-receipt"></i>
                <span>Struk & Faktur Kasir</span>
            </div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">{{ $sale->sale_number }}</h1>
            <p class="text-xs text-stone-500 mt-0.5">Waktu transaksi: {{ $sale->sold_at->format('d F Y, H:i') }} WIB</p>
        </div>

        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-3.5 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-semibold rounded-xl transition inline-flex items-center space-x-1.5">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Struk</span>
            </button>
            <a href="{{ auth()->user()->isCustomer() ? route('shop.my-orders') : route('sales.index') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900">&larr; Kembali</a>
        </div>
    </div>

    <!-- Thermal Receipt Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-md print:shadow-none print:border-none space-y-6">
        
        <!-- Coffee Shop Brand Header -->
        <div class="text-center pb-6 border-b border-dashed border-stone-200">
            <div class="w-12 h-12 rounded-2xl bg-coffee-900 text-amber-400 flex items-center justify-center mx-auto mb-2 text-xl">
                <i class="fa-solid fa-mug-hot"></i>
            </div>
            <h2 class="text-xl font-bold text-coffee-950 font-serif">Kopi Senja Roastery & Cafe</h2>
            <p class="text-xs text-stone-500 mt-0.5">Jl. Roastery Senja No. 1, Jakarta • Telp: 0812-3456-0001</p>
            <div class="mt-3 inline-flex items-center space-x-2 text-[11px] font-mono text-stone-600 bg-stone-50 px-3 py-1 rounded-full border border-stone-200">
                <span>{{ $sale->sale_number }}</span>
                <span>•</span>
                <span>Kasir: {{ $sale->user->name ?? 'POS' }}</span>
            </div>
        </div>

        <!-- Customer & Payment Metadata -->
        <div class="grid grid-cols-2 text-xs gap-3 pb-4 border-b border-stone-100">
            <div>
                <span class="text-stone-400 block text-[10px] uppercase font-semibold">Nama Pelanggan</span>
                <span class="font-bold text-stone-800">{{ $sale->customer_name }}</span>
                @if($sale->customer_phone)
                    <span class="block text-stone-500 text-[11px]">WA: {{ $sale->customer_phone }}</span>
                @endif
                <span class="inline-block mt-1 text-[10px] font-semibold text-coffee-700 bg-coffee-50 px-2 py-0.5 rounded">
                    {{ $sale->order_type_label }}
                </span>
            </div>

            <div class="text-right">
                <span class="text-stone-400 block text-[10px] uppercase font-semibold">Status / Metode Bayar</span>
                <div class="flex items-center justify-end space-x-1.5 mt-0.5 flex-wrap gap-1">
                    <span class="uppercase font-mono text-[10px] font-bold px-2 py-0.5 rounded bg-stone-100 text-stone-700">
                        {{ $sale->payment_method }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border
                        {{ $sale->status === 'completed' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : '' }}
                        {{ $sale->status === 'cancelled' ? 'bg-red-100 text-red-800 border-red-200' : '' }}
                        {{ !in_array($sale->status, ['completed', 'cancelled']) ? 'bg-amber-100 text-amber-800 border-amber-200' : '' }}
                    ">
                        {{ $sale->status_label }}
                    </span>
                </div>
                @if($sale->staff)
                    <span class="text-[10px] text-stone-400 block mt-1">Barista: {{ $sale->staff->name }}</span>
                @endif
            </div>
        </div>

        <!-- Items List -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-stone-400">Daftar Menu Kopi</h3>
            <div class="divide-y divide-dashed divide-stone-200">
                @foreach($sale->items as $item)
                    <div class="py-2.5 flex items-center justify-between text-xs">
                        <div>
                            <div class="font-bold text-stone-900">{{ $item->product->name ?? 'Menu Kopi' }}</div>
                            <div class="text-[11px] text-stone-500 font-mono">
                                {{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="font-bold text-stone-900 font-mono">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Grand Total Calculation -->
        <div class="pt-4 border-t-2 border-stone-800 space-y-2">
            <div class="flex justify-between items-center text-xs text-stone-600">
                <span>Subtotal Pesanan:</span>
                <span class="font-mono">Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center text-xs text-stone-600">
                <span>Pajak Resto (0%):</span>
                <span class="font-mono">Rp 0</span>
            </div>
            <div class="flex justify-between items-center text-base font-bold text-coffee-950 pt-2 border-t border-stone-100">
                <span class="font-serif">Total Bayar:</span>
                <span class="text-xl font-mono text-emerald-700">Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
            </div>
        </div>

        @if($sale->notes)
            <div class="text-xs bg-amber-50 p-3 rounded-2xl border border-amber-200 text-amber-900">
                <strong>Catatan:</strong> {{ $sale->notes }}
            </div>
        @endif

        <div class="text-center text-[11px] text-stone-400 pt-4 border-t border-dashed border-stone-200">
            Terima kasih atas kunjungan Anda di Kopi Senja!<br>
            Nikmati aroma dan rasa biji kopi terbaik nusantara.
        </div>

    </div>

    <!-- Admin Cancel Action (PPT Slide 5 & 8) -->
    @if(auth()->user()->isAdmin() && $sale->status === 'completed')
        <div class="bg-red-50 p-6 rounded-3xl border border-red-200 space-y-2">
            <div class="flex items-center space-x-2 text-red-900 font-bold text-sm">
                <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                <span>Otoritas Pembatalan Transaksi Penjualan (Khusus Admin / Owner)</span>
            </div>
            <p class="text-xs text-red-700 leading-relaxed">
                Sesuai aturan bisnis pada PPT Slide 8, transaksi yang dibatalkan akan mengembalikan secara konsisten (restore) stok produk yang sebelumnya sudah berkurang kembali ke inventaris.
            </p>
            <div class="pt-2">
                <form method="POST" action="{{ route('sales.cancel', $sale) }}" onsubmit="return confirm('PERHATIAN: Apakah Anda yakin ingin membatalkan transaksi penjualan ini? Stok semua item pada transaksi ini akan dikembalikan ke inventaris.');">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-xl transition inline-flex items-center space-x-1.5 shadow-sm">
                        <i class="fa-solid fa-ban"></i>
                        <span>Batalkan Penjualan & Pulihkan Stok</span>
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
