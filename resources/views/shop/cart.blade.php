@extends('layouts.app')

@section('title', 'Keranjang Belanja — KOPI SENJA')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">

    <!-- Header Breadcrumbs -->
    <div class="flex items-center space-x-2 text-xs text-stone-500">
        <a href="{{ route('home') }}" class="hover:text-coffee-700">Beranda</a>
        <span>/</span>
        <a href="{{ route('shop.index') }}" class="hover:text-coffee-700">Katalog Menu</a>
        <span>/</span>
        <span class="text-espresso font-semibold">Keranjang Belanja</span>
    </div>

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold font-serif text-espresso">Keranjang Pesanan</h1>
            <p class="text-xs text-stone-500 mt-1">Periksa kembali daftar racikan kopi dan pastry pilihan Anda.</p>
        </div>

        @if(!empty($itemsWithDetails))
            <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?')">
                @csrf
                <button type="submit" class="text-xs text-red-600 hover:text-red-700 font-semibold inline-flex items-center space-x-1 transition">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Kosongkan Keranjang</span>
                </button>
            </form>
        @endif
    </div>

    @if(empty($itemsWithDetails))
        <!-- Empty Cart State -->
        <div class="bg-white rounded-3xl p-12 text-center border border-stone-200/80 shadow-sm space-y-4">
            <div class="w-20 h-20 rounded-full bg-coffee-50 text-coffee-600 flex items-center justify-center mx-auto text-3xl">
                ☕
            </div>
            <h3 class="text-xl font-bold font-serif text-espresso">Keranjang Pesanan Masih Kosong</h3>
            <p class="text-xs sm:text-sm text-stone-500 max-w-md mx-auto">
                Anda belum memilih menu kopi atau pastry favorit. Mari jelajahi koleksi menu kami dan temukan cita rasa terbaik hari ini.
            </p>
            <div class="pt-4">
                <a href="{{ route('shop.index') }}" class="px-6 py-3 bg-espresso hover:bg-coffee-800 text-amber-300 text-xs font-bold uppercase tracking-wider rounded-xl shadow-md transition inline-flex items-center space-x-2">
                    <i class="fa-solid fa-mug-hot"></i>
                    <span>Jelajahi Menu Kopi Sekarang</span>
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Items List (2 cols) -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($itemsWithDetails as $item)
                    <div class="bg-white rounded-3xl p-5 border {{ !$item['is_stock_sufficient'] ? 'border-red-300 bg-red-50/20' : 'border-stone-200/80' }} shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                        
                        <!-- Product Info -->
                        <div class="flex items-center space-x-4 w-full sm:w-auto">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-20 h-20 object-cover rounded-2xl flex-shrink-0 border border-stone-100 shadow-sm">
                            
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-coffee-600 bg-coffee-50 px-2 py-0.5 rounded-md">
                                    {{ $item['category'] }}
                                </span>
                                <h4 class="font-bold text-sm text-espresso leading-snug">{{ $item['name'] }}</h4>
                                <p class="text-xs text-stone-500">
                                    Harga Satuan: <span class="font-semibold text-espresso">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                </p>

                                @if(!$item['is_stock_sufficient'])
                                    <span class="inline-block text-[11px] font-bold text-red-600">
                                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> Stok hanya tersisa {{ $item['stock'] }} unit!
                                    </span>
                                @else
                                    <span class="inline-block text-[10px] text-emerald-700">
                                        <i class="fa-solid fa-check mr-1"></i> Tersedia (Stok: {{ $item['stock'] }})
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Quantity Modifier & Subtotal -->
                        <div class="flex items-center justify-between sm:justify-end space-x-6 w-full sm:w-auto border-t sm:border-t-0 pt-3 sm:pt-0">
                            
                            <!-- Qty Form -->
                            <form method="POST" action="{{ route('cart.update') }}" class="flex items-center border border-stone-200 rounded-xl overflow-hidden bg-stone-50">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                                
                                <button type="submit" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}" 
                                        class="px-2.5 py-1.5 text-stone-600 hover:bg-stone-200 transition text-xs font-bold"
                                        {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                    -
                                </button>
                                
                                <span class="px-3 py-1.5 text-xs font-bold text-espresso bg-white min-w-[36px] text-center">
                                    {{ $item['quantity'] }}
                                </span>
                                
                                <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}" 
                                        class="px-2.5 py-1.5 text-stone-600 hover:bg-stone-200 transition text-xs font-bold"
                                        {{ $item['quantity'] >= $item['stock'] ? 'disabled' : '' }}>
                                    +
                                </button>
                            </form>

                            <!-- Subtotal -->
                            <div class="text-right min-w-[100px]">
                                <span class="text-[10px] text-stone-400 uppercase tracking-wider block">Subtotal</span>
                                <span class="text-sm font-bold text-coffee-800 font-serif">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Remove Button -->
                            <form method="POST" action="{{ route('cart.remove', $item['id']) }}">
                                @csrf
                                <button type="submit" class="text-stone-400 hover:text-red-600 transition p-1" title="Hapus dari keranjang">
                                    <i class="fa-solid fa-xmark text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach

                <div class="pt-2">
                    <a href="{{ route('shop.index') }}" class="text-xs font-bold text-coffee-700 hover:text-espresso inline-flex items-center space-x-1.5">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i>
                        <span>Pilih Menu Lainnya</span>
                    </a>
                </div>
            </div>

            <!-- Order Summary Card (1 col) -->
            <div class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-sm space-y-6 sticky top-28">
                <h3 class="font-bold text-base text-espresso font-serif pb-3 border-b border-stone-100">
                    Ringkasan Pesanan
                </h3>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between text-stone-600">
                        <span>Total Jumlah Item</span>
                        <span class="font-bold text-espresso">{{ array_sum(array_column($itemsWithDetails, 'quantity')) }} porsi</span>
                    </div>
                    <div class="flex justify-between text-stone-600">
                        <span>Biaya Pengemasan / Cup</span>
                        <span class="text-emerald-700 font-semibold">Gratis</span>
                    </div>
                    <div class="flex justify-between text-stone-600">
                        <span>Pajak Resto (PB1)</span>
                        <span class="text-stone-500 font-medium">Termasuk dalam harga</span>
                    </div>

                    <div class="border-t border-stone-100 pt-3 flex justify-between items-baseline">
                        <span class="font-bold text-sm text-espresso">Total Tagihan</span>
                        <span class="text-xl font-bold text-coffee-800 font-serif">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    @auth
                        @php
                            $hasInsufficient = collect($itemsWithDetails)->contains('is_stock_sufficient', false);
                        @endphp

                        @if($hasInsufficient)
                            <div class="bg-red-50 p-3 rounded-xl border border-red-200 text-red-800 text-[11px] leading-relaxed">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Beberapa menu melebihi stok yang tersedia. Harap kurangi kuantitas sebelum checkout.
                            </div>
                            <button disabled class="w-full py-3.5 bg-stone-200 text-stone-400 font-bold text-xs uppercase tracking-wider rounded-xl cursor-not-allowed text-center">
                                Checkout Tidak Tersedia
                            </button>
                        @else
                            <a href="{{ route('cart.checkout') }}" class="w-full py-3.5 bg-espresso hover:bg-coffee-800 text-amber-300 font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-coffee-950/10 transition text-center block">
                                <i class="fa-solid fa-cash-register mr-1.5"></i> Lanjut ke Checkout
                            </a>
                        @endif
                    @else
                        <div class="bg-amber-50 p-3.5 rounded-xl border border-amber-200 text-amber-900 text-xs space-y-2">
                            <p class="font-semibold"><i class="fa-solid fa-lock mr-1"></i> Masuk untuk Melanjutkan</p>
                            <p class="text-[11px] text-amber-800">
                                Anda perlu login atau daftar akun terlebih dahulu sebelum dapat menyelesaikan checkout pesanan online.
                            </p>
                        </div>
                        <a href="{{ route('login') }}" class="w-full py-3.5 bg-espresso hover:bg-coffee-800 text-amber-300 font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition text-center block">
                            Masuk untuk Checkout
                        </a>
                        <a href="{{ route('register') }}" class="w-full py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-xs uppercase tracking-wider rounded-xl transition text-center block">
                            Daftar Akun Baru
                        </a>
                    @endauth
                </div>

                <div class="pt-3 border-t border-stone-100 text-[11px] text-stone-400 space-y-1">
                    <p><i class="fa-solid fa-shield-halved text-emerald-600 mr-1"></i> Transaksi terjamin dan stok diperiksa real-time.</p>
                    <p><i class="fa-solid fa-mug-hot text-coffee-600 mr-1"></i> Pilihan Dine In & Take Away tersedia di langkah berikutnya.</p>
                </div>
            </div>

        </div>
    @endif

</div>
@endsection
