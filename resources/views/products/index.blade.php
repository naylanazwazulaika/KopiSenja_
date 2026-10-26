@extends('layouts.app')

@section('title', 'Inventaris Produk Kopi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 text-xs font-semibold text-coffee-700 bg-coffee-100 px-3 py-1 rounded-full mb-1">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>Manajemen Inventaris</span>
            </div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">Inventaris Produk Kopi</h1>
            <p class="text-xs text-stone-500 mt-1">Daftar katalog biji kopi roast beans, minuman olahan, dan perlengkapan barista</p>
        </div>

        @if(auth()->user()->isAdmin())
            <a href="{{ route('products.create') }}" class="px-4 py-2.5 bg-coffee-800 hover:bg-coffee-900 text-white text-xs font-semibold rounded-xl shadow-sm transition inline-flex items-center space-x-2">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Tambah Produk Baru</span>
            </a>
        @endif
    </div>

    <!-- Search & Filters (PPT Slide 18: Search SKU/nama produk, filter kategori, status stok) -->
    <div class="bg-white p-4 rounded-2xl border border-stone-200 shadow-sm">
        <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            
            <!-- Search Text -->
            <div class="sm:col-span-5 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-stone-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, SKU, atau deskripsi..."
                       class="w-full pl-9 pr-4 py-2 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
            </div>

            <!-- Category Filter -->
            <div class="sm:col-span-3">
                <select name="category_id" class="w-full px-3 py-2 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Stock Filter -->
            <div class="sm:col-span-2">
                <select name="stock_filter" class="w-full px-3 py-2 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                    <option value="">Semua Stok</option>
                    <option value="low" {{ $stockFilter === 'low' ? 'selected' : '' }}>Stok Rendah (&le; Min)</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full py-2 bg-stone-800 hover:bg-stone-900 text-white text-xs font-semibold rounded-xl transition text-center">
                    Filter
                </button>
                @if($search || $categoryId || $stockFilter)
                    <a href="{{ route('products.index') }}" class="p-2 text-stone-500 hover:text-stone-800 text-xs text-center" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-100">
                    <tr>
                        <th class="py-3 px-6">SKU</th>
                        <th class="py-3 px-6">Nama Produk Kopi</th>
                        <th class="py-3 px-6">Kategori</th>
                        <th class="py-3 px-6 text-right">Harga Beli</th>
                        <th class="py-3 px-6 text-right">Harga Jual</th>
                        <th class="py-3 px-6 text-center">Stok</th>
                        <th class="py-3 px-6 text-center">Min. Stok</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-700">
                    @forelse($products as $product)
                        <tr class="hover:bg-stone-50/50 transition">
                            <td class="py-3.5 px-6 font-mono font-bold text-coffee-900">{{ $product->sku }}</td>
                            <td class="py-3.5 px-6">
                                <a href="{{ route('products.show', $product) }}" class="font-bold text-stone-900 hover:text-coffee-800 hover:underline">
                                    {{ $product->name }}
                                </a>
                                @if($product->description)
                                    <div class="text-[11px] text-stone-400 truncate max-w-xs">{{ $product->description }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-stone-100 text-stone-700">
                                    {{ $product->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-right font-medium text-stone-600">
                                Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-6 text-right font-bold text-coffee-900">
                                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-6 text-center font-bold">
                                <span class="px-2 py-0.5 rounded-full {{ $product->is_low_stock ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-emerald-50 text-emerald-800' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-center text-stone-500 font-medium">
                                {{ $product->minimum_stock }}
                            </td>
                            <td class="py-3.5 px-6 text-center">
                                @if($product->is_low_stock)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        <i class="fa-solid fa-triangle-exclamation mr-1 text-[9px]"></i> Stok Rendah
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700">
                                        Tersedia
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right space-x-2">
                                <a href="{{ route('products.show', $product) }}" class="text-stone-500 hover:text-stone-800 font-semibold" title="Detail">
                                    <i class="fa-regular fa-eye"></i>
                                </a>

                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('products.edit', $product) }}" class="text-stone-600 hover:text-coffee-800 font-semibold" title="Edit">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Jika produk memiliki transaksi, produk akan di-soft-delete untuk menjaga integritas data.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-semibold ml-1" title="Hapus">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-stone-400">
                                Tidak ada produk yang sesuai dengan kriteria pencarian
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-stone-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
