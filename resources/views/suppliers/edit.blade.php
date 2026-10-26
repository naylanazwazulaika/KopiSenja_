@extends('layouts.app')

@section('title', 'Edit Pemasok: ' . $supplier->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">Edit Mitra Pemasok Kopi</h1>
            <p class="text-xs text-stone-500 mt-1">Perbarui informasi kontak dan alamat pemasok</p>
        </div>
        <a href="{{ route('suppliers.index') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900">&larr; Kembali</a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm">
        <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Nama Pemasok *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $supplier->name) }}" required
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                </div>

                <div>
                    <label for="contact_person" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Nama Kontak (Contact Person)</label>
                    <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}"
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Nomor Telepon *</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $supplier->phone) }}" required
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Email Kontak</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $supplier->email) }}"
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Alamat / Asal Kebun</label>
                <textarea name="address" id="address" rows="3"
                          class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">{{ old('address', $supplier->address) }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <a href="{{ route('suppliers.index') }}" class="px-5 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-coffee-800 hover:bg-coffee-900 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                    Perbarui Pemasok
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
