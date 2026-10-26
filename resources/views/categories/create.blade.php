@extends('layouts.app')

@section('title', 'Tambah Kategori Kopi')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">Tambah Kategori Kopi Baru</h1>
            <p class="text-xs text-stone-500 mt-1">Daftarkan kategori klasifikasi produk kopi baru</p>
        </div>
        <a href="{{ route('categories.index') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900">&larr; Kembali</a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm">
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Nama Kategori *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition"
                       placeholder="Contoh: Biji Kopi Flores Bajawa">
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Deskripsi Singkat</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition"
                          placeholder="Penjelasan ringkas mengenai jenis kopi atau produk pada kategori ini">{{ old('description') }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <a href="{{ route('categories.index') }}" class="px-5 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-coffee-800 hover:bg-coffee-900 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
