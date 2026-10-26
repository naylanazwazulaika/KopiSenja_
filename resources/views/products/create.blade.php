@extends('layouts.app')

@section('title', 'Tambah Produk Kopi')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">Tambah Produk Kopi Baru</h1>
            <p class="text-xs text-stone-500 mt-1">Daftarkan SKU produk kopi dengan harga dan batas minimum stok</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900">&larr; Kembali</a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm">
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- SKU (PPT Slide 15: required, string, max:50, unique:products,sku) -->
                <div>
                    <label for="sku" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Kode SKU Produk *</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku') }}" required
                           class="w-full px-4 py-2.5 text-sm font-mono bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition"
                           placeholder="Misal: KOP-GYO-250">
                    <span class="text-[10px] text-stone-400 mt-1 block">Harus unik di database</span>
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Kategori Kopi *</label>
                    <select name="category_id" id="category_id" required
                            class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Nama Produk Kopi *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition"
                       placeholder="Contoh: Aceh Gayo Wine Processed Arabica 250g">
            </div>

            <!-- Pricing Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-stone-50/70 rounded-2xl border border-stone-100">
                <div>
                    <label for="purchase_price" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Harga Beli / Pokok (Rp) *</label>
                    <input type="number" name="purchase_price" id="purchase_price" value="{{ old('purchase_price', 0) }}" min="0" step="100" required
                           class="w-full px-4 py-2.5 text-sm bg-white border border-stone-200 rounded-xl focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition"
                           placeholder="65000">
                </div>

                <div>
                    <label for="selling_price" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Harga Jual Konsumen (Rp) *</label>
                    <input type="number" name="selling_price" id="selling_price" value="{{ old('selling_price', 0) }}" min="0" step="100" required
                           class="w-full px-4 py-2.5 text-sm bg-white border border-stone-200 rounded-xl focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition"
                           placeholder="95000">
                </div>
            </div>

            <!-- Stock Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="stock" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Stok Awal Fisik *</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" min="0" required
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition"
                           placeholder="20">
                </div>

                <div>
                    <label for="minimum_stock" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Batas Minimum Stok (Alert) *</label>
                    <input type="number" name="minimum_stock" id="minimum_stock" value="{{ old('minimum_stock', 5) }}" min="0" required
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition"
                           placeholder="5">
                    <span class="text-[10px] text-stone-400 mt-1 block">Peringatan muncul jika stok &le; angka ini</span>
                </div>
            </div>

            <!-- Image Upload -->
            <div>
                <label for="image" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Foto Menu Produk (Opsional)</label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full px-3 py-2 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:outline-none transition file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-coffee-100 file:text-coffee-800 hover:file:bg-coffee-200">
                <span class="text-[10px] text-stone-400 mt-1 block">Format: JPG, JPEG, PNG, WEBP (Maksimal 2MB)</span>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Deskripsi Tasting Notes & Karakteristik</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition"
                          placeholder="Profil rasa (flavor notes), tingkat sangrai (roast level), proses olah, dll.">{{ old('description') }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-stone-100">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-coffee-800 hover:bg-coffee-900 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                    Simpan Produk Kopi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
