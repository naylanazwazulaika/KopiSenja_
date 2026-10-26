@extends('layouts.app')

@section('title', 'Edit Produk: ' . $product->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">Edit Data Produk Kopi</h1>
            <p class="text-xs text-stone-500 mt-1">Perbarui rincian harga, kategori, dan batas minimum stok</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900">&larr; Kembali</a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm">
        <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- SKU -->
                <div>
                    <label for="sku" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Kode SKU Produk *</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" required
                           class="w-full px-4 py-2.5 text-sm font-mono bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Kategori Kopi *</label>
                    <select name="category_id" id="category_id" required
                            class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Nama Produk Kopi *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                       class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
            </div>

            <!-- Pricing Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-stone-50/70 rounded-2xl border border-stone-100">
                <div>
                    <label for="purchase_price" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Harga Beli / Pokok (Rp) *</label>
                    <input type="number" name="purchase_price" id="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" min="0" step="100" required
                           class="w-full px-4 py-2.5 text-sm bg-white border border-stone-200 rounded-xl focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                </div>

                <div>
                    <label for="selling_price" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Harga Jual Konsumen (Rp) *</label>
                    <input type="number" name="selling_price" id="selling_price" value="{{ old('selling_price', $product->selling_price) }}" min="0" step="100" required
                           class="w-full px-4 py-2.5 text-sm bg-white border border-stone-200 rounded-xl focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                </div>
            </div>

            <!-- Stock Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="stock" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">
                        Stok Fisik Saat Ini
                        @if(!auth()->user()->isAdmin())
                            <span class="text-stone-400 font-normal">(Hanya diubah lewat transaksi)</span>
                        @endif
                    </label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0"
                           {{ auth()->user()->isAdmin() ? '' : 'readonly' }}
                           class="w-full px-4 py-2.5 text-sm {{ auth()->user()->isAdmin() ? 'bg-stone-50' : 'bg-stone-100 cursor-not-allowed' }} border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                </div>

                <div>
                    <label for="minimum_stock" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Batas Minimum Stok (Alert) *</label>
                    <input type="number" name="minimum_stock" id="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}" min="0" required
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                </div>
            </div>

            <!-- Image Upload & Preview -->
            <div class="space-y-2">
                <label for="image" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Foto Menu Produk</label>
                @if($product->image)
                    <div class="flex items-center space-x-3 mb-2">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded-xl border border-stone-200">
                        <span class="text-xs text-stone-500">Foto produk saat ini</span>
                    </div>
                @endif
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full px-3 py-2 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:outline-none transition file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-coffee-100 file:text-coffee-800 hover:file:bg-coffee-200">
                <span class="text-[10px] text-stone-400 block">Pilih file baru jika ingin mengganti gambar produk (Max 2MB: JPG, PNG, WEBP)</span>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Deskripsi Tasting Notes & Karakteristik</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-stone-100">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-coffee-800 hover:bg-coffee-900 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                    Perbarui Produk Kopi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
