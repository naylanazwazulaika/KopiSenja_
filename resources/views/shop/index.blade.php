@extends('layouts.app')

@section('title', 'Katalog Menu Kopi & Pastry — KOPI SENJA')

@section('content')
<div class="space-y-8">

    <!-- Hero Header Banner -->
    <div class="relative bg-gradient-to-r from-espresso via-coffee-950 to-coffee-900 rounded-3xl p-8 sm:p-12 text-white overflow-hidden shadow-xl border border-coffee-800/40">
        <div class="relative z-10 max-w-2xl space-y-4">
            <span class="inline-flex items-center space-x-2 bg-amber-400/20 text-amber-300 text-xs px-3.5 py-1 rounded-full font-bold uppercase tracking-wider border border-amber-400/30">
                <i class="fa-solid fa-mug-saucer text-[10px]"></i>
                <span>Katalog Menu Kopi Senja</span>
            </span>
            <h1 class="text-3xl sm:text-5xl font-bold font-serif tracking-tight leading-tight text-cream">
                Eksplorasi Cita Rasa Kopi & Pastry Kami
            </h1>
            <p class="text-xs sm:text-sm text-stone-300 leading-relaxed max-w-xl font-light">
                Dari racikan espresso murni, signature latte aren, seduhan manual pour over V60, hingga pastry butter Prancis hangat. Pesan sekarang untuk Dine In atau Take Away.
            </p>
        </div>

        <div class="absolute -right-6 -bottom-10 text-white/5 text-[220px] pointer-events-none font-serif select-none">
            ☕
        </div>
    </div>

    <!-- Category Filter Pills & Search Bar -->
    <div id="katalog-grid" class="space-y-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            
            <!-- Category Pills -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('shop.index') }}" 
                   class="px-4 py-2 rounded-2xl text-xs font-bold uppercase tracking-wider transition {{ !$selectedCategory ? 'bg-espresso text-amber-300 shadow-md' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200' }}">
                    Semua Menu
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" 
                       class="px-4 py-2 rounded-2xl text-xs font-bold uppercase tracking-wider transition {{ $selectedCategory && $selectedCategory->id == $cat->id ? 'bg-espresso text-amber-300 shadow-md' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200' }}">
                        {{ $cat->name }} ({{ $cat->products_count }})
                    </a>
                @endforeach
            </div>

            <!-- Search Bar -->
            <form method="GET" action="{{ route('shop.index') }}" class="relative min-w-[280px]">
                @if($selectedCategory)
                    <input type="hidden" name="category" value="{{ $selectedCategory->slug }}">
                @endif
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-stone-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari kopi, latte, pastry..."
                       class="w-full pl-9 pr-4 py-2.5 text-xs bg-white border border-stone-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 shadow-sm transition">
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-3xl border border-stone-200/80 shadow-sm overflow-hidden flex flex-col justify-between group hover:shadow-xl hover:border-coffee-300 transition duration-300">
                
                <!-- Product Image Banner & Stock Badge -->
                <div class="relative h-48 overflow-hidden bg-stone-100">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    
                    <div class="absolute top-3 left-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-white bg-espresso/80 backdrop-blur-md px-2.5 py-1 rounded-lg">
                            {{ $product->category->name ?? 'Menu' }}
                        </span>
                    </div>

                    <div class="absolute top-3 right-3">
                        @if($product->stock <= 0)
                            <span class="text-[10px] font-bold text-red-700 bg-red-100 px-2.5 py-1 rounded-full border border-red-200">
                                Stok Habis
                            </span>
                        @elseif($product->is_low_stock)
                            <span class="text-[10px] font-bold text-amber-800 bg-amber-100 px-2.5 py-1 rounded-full border border-amber-300">
                                Sisa {{ $product->stock }} Unit!
                            </span>
                        @else
                            <span class="text-[10px] font-medium text-emerald-800 bg-emerald-100/90 px-2.5 py-1 rounded-full">
                                Stok: {{ $product->stock }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Product Body -->
                <div class="p-5 flex-grow flex flex-col justify-between space-y-4">
                    <div class="space-y-1.5">
                        <a href="{{ route('shop.show', $product) }}" class="block">
                            <h3 class="font-bold text-base text-espresso group-hover:text-coffee-700 transition leading-snug font-serif">
                                {{ $product->name }}
                            </h3>
                        </a>
                        <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed font-light">
                            {{ $product->description }}
                        </p>
                    </div>

                    <!-- Price & Add to Cart Action -->
                    <div class="pt-3 border-t border-stone-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-stone-400 uppercase tracking-wider block font-semibold">Harga</span>
                            <span class="text-base font-bold text-coffee-800 font-serif">
                                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex items-center space-x-2">
                            <a href="{{ route('shop.show', $product) }}" class="p-2 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-600 text-xs transition" title="Lihat Rincian">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            @if($product->stock > 0)
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" 
                                            class="px-3.5 py-2 rounded-xl bg-espresso hover:bg-coffee-700 text-amber-300 transition duration-200 shadow-md shadow-coffee-950/10 flex items-center space-x-1.5 text-xs font-bold"
                                            title="Tambah ke Keranjang">
                                        <i class="fa-solid fa-plus text-[10px]"></i>
                                        <span>Pesan</span>
                                    </button>
                                </form>
                            @else
                                <button disabled class="px-3 py-2 rounded-xl bg-stone-100 text-stone-400 text-xs font-semibold cursor-not-allowed">
                                    Habis
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full bg-white p-12 rounded-3xl border border-stone-200 text-center space-y-3">
                <div class="w-16 h-16 rounded-full bg-stone-100 text-stone-400 flex items-center justify-center mx-auto text-2xl">
                    <i class="fa-solid fa-mug-saucer"></i>
                </div>
                <h3 class="text-base font-bold text-stone-800 font-serif">Tidak Ada Produk Ditemukan</h3>
                <p class="text-xs text-stone-500 max-w-sm mx-auto">Coba ubah kata kunci pencarian atau pilih kategori produk kopi yang lain.</p>
                <a href="{{ route('shop.index') }}" class="inline-block mt-2 text-xs font-semibold text-coffee-700 hover:underline">Reset Pencarian</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="p-4 bg-white rounded-2xl border border-stone-200 shadow-sm">
            {{ $products->links() }}
        </div>
    @endif

</div>
@endsection
