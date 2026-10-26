@extends('layouts.app')

@section('title', 'Detail Produk: ' . $product->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="inline-flex items-center space-x-2 text-xs font-semibold text-coffee-700 bg-coffee-100 px-3 py-1 rounded-full mb-1">
                <i class="fa-solid fa-mug-hot"></i>
                <span>Detail Inventaris</span>
            </div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">{{ $product->name }}</h1>
            <p class="text-xs text-stone-500 font-mono mt-0.5">SKU: {{ $product->sku }}</p>
        </div>

        <div class="flex items-center space-x-2">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('products.edit', $product) }}" class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-semibold rounded-xl transition inline-flex items-center space-x-1.5">
                    <i class="fa-regular fa-pen-to-square"></i>
                    <span>Edit Produk</span>
                </a>
            @endif
            <a href="{{ route('products.index') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900">&larr; Kembali</a>
        </div>
    </div>

    <!-- Product Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-stone-200 shadow-sm">
            <span class="text-[11px] font-semibold text-stone-500 uppercase tracking-wider block">Harga Beli</span>
            <div class="text-xl font-bold text-stone-900 mt-1">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-stone-200 shadow-sm">
            <span class="text-[11px] font-semibold text-stone-500 uppercase tracking-wider block">Harga Jual</span>
            <div class="text-xl font-bold text-coffee-900 mt-1">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-stone-200 shadow-sm">
            <span class="text-[11px] font-semibold text-stone-500 uppercase tracking-wider block">Margin Keuntungan</span>
            @php
                $margin = $product->selling_price - $product->purchase_price;
                $pct = $product->selling_price > 0 ? ($margin / $product->selling_price) * 100 : 0;
            @endphp
            <div class="text-xl font-bold text-emerald-600 mt-1">
                Rp {{ number_format($margin, 0, ',', '.') }}
                <span class="text-xs font-medium text-emerald-700">({{ number_format($pct, 1) }}%)</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border {{ $product->is_low_stock ? 'border-red-300 bg-red-50/20' : 'border-stone-200' }} shadow-sm">
            <span class="text-[11px] font-semibold text-stone-500 uppercase tracking-wider block">Sisa Stok Fisik</span>
            <div class="text-xl font-bold {{ $product->is_low_stock ? 'text-red-700' : 'text-stone-900' }} mt-1 flex items-center justify-between">
                <span>{{ $product->stock }} Unit</span>
                @if($product->is_low_stock)
                    <span class="text-[10px] bg-red-100 text-red-800 px-2 py-0.5 rounded-full font-bold">Stok Rendah</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Description & Category info -->
    <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm space-y-4">
        <div>
            <h3 class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Kategori</h3>
            <p class="text-sm font-bold text-coffee-900 mt-0.5">{{ $product->category->name ?? '-' }}</p>
        </div>

        <div>
            <h3 class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Deskripsi & Profil Rasa Kopi</h3>
            <p class="text-xs text-stone-700 mt-1 leading-relaxed">{{ $product->description ?: 'Belum ada deskripsi profil rasa untuk produk ini.' }}</p>
        </div>
    </div>

    <!-- Transaction History (Purchases & Sales) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Stock-In History -->
        <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-stone-100">
                <h3 class="text-sm font-bold text-coffee-950 font-serif">Riwayat Pembelian (Stock-In)</h3>
            </div>
            <div class="divide-y divide-stone-100 text-xs">
                @forelse($product->purchaseItems as $pItem)
                    <div class="p-3.5 flex items-center justify-between">
                        <div>
                            <div class="font-mono font-semibold text-coffee-900">{{ $pItem->purchase->purchase_number ?? '-' }}</div>
                            <div class="text-[11px] text-stone-500">{{ $pItem->purchase->supplier->name ?? 'Supplier' }} • {{ $pItem->purchase->purchased_at->format('d M Y') }}</div>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-blue-600">+{{ $pItem->quantity }} item</span>
                            <div class="text-[10px] text-stone-400">@ Rp {{ number_format($pItem->unit_cost, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-stone-400 text-xs">Belum ada riwayat stock-in</div>
                @endforelse
            </div>
        </div>

        <!-- Stock-Out History -->
        <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-stone-100">
                <h3 class="text-sm font-bold text-coffee-950 font-serif">Riwayat Penjualan (Stock-Out)</h3>
            </div>
            <div class="divide-y divide-stone-100 text-xs">
                @forelse($product->saleItems as $sItem)
                    <div class="p-3.5 flex items-center justify-between">
                        <div>
                            <div class="font-mono font-semibold text-coffee-900">{{ $sItem->sale->sale_number ?? '-' }}</div>
                            <div class="text-[11px] text-stone-500">{{ $sItem->sale->customer_name ?? 'Pelanggan' }} • {{ $sItem->sale->sold_at->format('d M Y') }}</div>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-emerald-600">-{{ $sItem->quantity }} item</span>
                            <div class="text-[10px] text-stone-400">@ Rp {{ number_format($sItem->unit_price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-stone-400 text-xs">Belum ada riwayat stock-out</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
