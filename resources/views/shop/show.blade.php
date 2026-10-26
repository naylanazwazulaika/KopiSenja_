@extends('layouts.app')

@section('title', $product->name . ' — KOPI SENJA')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between text-xs text-stone-500">
        <a href="{{ route('shop.index') }}" class="hover:text-coffee-700 inline-flex items-center space-x-1.5 font-semibold">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            <span>Kembali ke Katalog Menu</span>
        </a>
        <span class="font-mono">SKU: {{ $product->sku }}</span>
    </div>

    <!-- Product Hero Detail -->
    <div class="bg-white rounded-3xl border border-stone-200/80 shadow-md p-6 sm:p-10 grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
        
        <!-- Left Product Visual Image -->
        <div class="md:col-span-5 rounded-3xl overflow-hidden border border-stone-200 shadow-sm relative group bg-stone-100">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-80 object-cover group-hover:scale-105 transition duration-500">
            
            <div class="absolute top-3 left-3">
                <span class="text-[10px] font-bold uppercase tracking-wider text-white bg-espresso/80 backdrop-blur-md px-3 py-1 rounded-lg">
                    {{ $product->category->name ?? 'Menu' }}
                </span>
            </div>
        </div>

        <!-- Right Product Info & Add to Cart -->
        <div class="md:col-span-7 space-y-5">
            <div>
                <div class="flex items-center space-x-2 mb-2">
                    @if($product->stock <= 0)
                        <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2.5 py-0.5 rounded-full border border-red-200">Stok Habis</span>
                    @elseif($product->is_low_stock)
                        <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200">Sisa {{ $product->stock }} Unit Saja!</span>
                    @else
                        <span class="text-[10px] font-medium text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full">Stok Tersedia: {{ $product->stock }} Unit</span>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-3xl font-bold text-espresso font-serif leading-tight">
                    {{ $product->name }}
                </h1>

                <div class="text-2xl font-bold text-coffee-800 font-serif mt-2">
                    Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                </div>
            </div>

            <div class="border-t border-b border-stone-100 py-4 text-xs text-stone-600 leading-relaxed space-y-1">
                <h3 class="font-bold uppercase tracking-wider text-coffee-600 text-[10px]">Deskripsi Menu & Karakter Rasa</h3>
                <p>{{ $product->description ?: 'Biji kopi pilihan roasted fresh dari perkebunan dataran tinggi nusantara dengan karakter aroma memikat dan tingkat keasaman seimbang.' }}</p>
            </div>

            <!-- Add To Cart Form -->
            @if($product->stock > 0)
                <form method="POST" action="{{ route('cart.add') }}" class="space-y-4 pt-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="flex items-center space-x-4">
                        <label for="orderQty" class="text-xs font-bold text-stone-700">Jumlah Porsi:</label>
                        <div class="flex items-center border border-stone-200 rounded-xl overflow-hidden bg-stone-50">
                            <button type="button" onclick="const input = document.getElementById('orderQty'); if(input.value > 1) input.value--" class="px-3 py-2 text-stone-600 hover:bg-stone-200 text-xs font-bold">-</button>
                            <input type="number" name="quantity" id="orderQty" value="1" min="1" max="{{ $product->stock }}"
                                   class="w-14 text-center py-2 text-xs font-bold bg-white focus:outline-none">
                            <button type="button" onclick="const input = document.getElementById('orderQty'); if(input.value < {{ $product->stock }}) input.value++" class="px-3 py-2 text-stone-600 hover:bg-stone-200 text-xs font-bold">+</button>
                        </div>
                        <span class="text-xs text-stone-400">(Tersedia {{ $product->stock }})</span>
                    </div>

                    <div class="flex items-center space-x-3 pt-2">
                        <button type="submit" class="flex-1 py-3.5 bg-espresso hover:bg-coffee-800 text-amber-300 font-bold text-xs uppercase tracking-wider rounded-2xl shadow-lg shadow-coffee-950/15 transition flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-bag-shopping"></i>
                            <span>Tambah ke Keranjang Pesanan</span>
                        </button>
                        <a href="{{ route('cart.index') }}" class="px-5 py-3.5 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold uppercase tracking-wider rounded-2xl transition">
                            Lihat Keranjang
                        </a>
                    </div>
                </form>
            @else
                <div class="p-4 bg-red-50 text-red-700 rounded-2xl text-xs font-semibold text-center border border-red-200">
                    Maaf, stok produk kopi ini sedang habis. Tim barista dan dapur roastery kami sedang menyiapkan batch baru!
                </div>
            @endif

        </div>

    </div>

    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
        <div class="space-y-4 pt-6 border-t border-stone-200">
            <h3 class="text-lg font-bold font-serif text-espresso">Menu Terkait yang Mungkin Anda Suka</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($relatedProducts as $related)
                    <a href="{{ route('shop.show', $related) }}" class="bg-white p-3.5 rounded-2xl border border-stone-200/80 shadow-sm hover:shadow-md hover:border-coffee-300 transition group block">
                        <img src="{{ $related->image_url }}" alt="{{ $related->name }}" class="w-full h-28 object-cover rounded-xl mb-2">
                        <h4 class="text-xs font-bold text-espresso group-hover:text-coffee-700 truncate">{{ $related->name }}</h4>
                        <span class="text-xs font-bold text-coffee-800 font-serif block mt-1">
                            Rp {{ number_format($related->selling_price, 0, ',', '.') }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
