@extends('layouts.app')

@section('title', 'Pesanan Diterima — ' . $sale->sale_number)

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <!-- Success Card -->
    <div class="bg-white rounded-3xl p-8 border border-stone-200/80 shadow-xl text-center space-y-6">
        
        <!-- Animated Icon -->
        <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto text-2xl shadow-inner border border-emerald-200">
            <i class="fa-solid fa-check"></i>
        </div>

        <div class="space-y-2">
            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                Pesanan Berhasil Masuk
            </span>
            <h1 class="text-2xl sm:text-3xl font-bold font-serif text-espresso">
                Terima Kasih Atas Pesanan Anda!
            </h1>
            <p class="text-xs text-stone-500 leading-relaxed max-w-sm mx-auto">
                Nomor pesanan Anda telah dicatat. Barista kami segera menyiapkan racikan terbaik untuk Anda.
            </p>
        </div>

        <!-- Order Number Badge -->
        <div class="p-4 rounded-2xl bg-cream border border-coffee-200/60 flex items-center justify-between">
            <div class="text-left">
                <span class="text-[10px] text-stone-400 uppercase tracking-wider block font-semibold">Nomor Pesanan</span>
                <span class="text-base font-bold font-mono text-coffee-900">{{ $sale->sale_number }}</span>
            </div>
            <div class="text-right">
                <span class="text-[10px] text-stone-400 uppercase tracking-wider block font-semibold">Status Saat Ini</span>
                <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200 inline-block">
                    {{ $sale->status_label }}
                </span>
            </div>
        </div>

        <!-- Order Details Table -->
        <div class="space-y-3 text-left border-t border-b border-stone-100 py-4 text-xs">
            <div class="flex justify-between text-stone-500">
                <span>Nama Pemesan:</span>
                <span class="font-bold text-stone-800">{{ $sale->customer_name }}</span>
            </div>
            <div class="flex justify-between text-stone-500">
                <span>Metode Penyajian:</span>
                <span class="font-bold text-coffee-800">{{ $sale->order_type_label }}</span>
            </div>
            <div class="flex justify-between text-stone-500">
                <span>Metode Pembayaran:</span>
                <span class="font-bold uppercase text-stone-800">{{ $sale->payment_method }}</span>
            </div>
            @if($sale->notes)
                <div class="text-stone-500 pt-1">
                    <span class="block">Catatan Barista:</span>
                    <span class="font-medium text-stone-800 italic bg-stone-50 p-2 rounded-lg block mt-0.5">
                        “{{ $sale->notes }}”
                    </span>
                </div>
            @endif

            <!-- Items Breakdown -->
            <div class="pt-3 border-t border-stone-100 space-y-2">
                <span class="text-[10px] text-stone-400 uppercase tracking-wider font-semibold block">Daftar Menu Kopi:</span>
                @foreach($sale->items as $item)
                    <div class="flex justify-between text-xs">
                        <span class="text-stone-700">{{ $item->product->name ?? 'Menu' }} &times; {{ $item->quantity }}</span>
                        <span class="font-medium text-espresso">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-between items-baseline pt-3 border-t border-stone-100">
                <span class="font-bold text-sm text-espresso">Total Pembayaran:</span>
                <span class="text-lg font-bold font-serif text-coffee-800">Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <a href="{{ route('customer.dashboard') }}" class="flex-1 py-3 bg-espresso hover:bg-coffee-800 text-amber-300 font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition text-center">
                <i class="fa-solid fa-gauge mr-1"></i> Dashboard Akun
            </a>
            <a href="{{ route('shop.my-orders') }}" class="flex-1 py-3 bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-xs uppercase tracking-wider rounded-xl transition text-center">
                <i class="fa-solid fa-receipt mr-1"></i> Riwayat Pesanan
            </a>
        </div>
    </div>

</div>
@endsection
