@extends('layouts.app')

@section('title', 'Edit Pengguna: ' . $user->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">Edit Pengguna Sistem</h1>
            <p class="text-xs text-stone-500 mt-1">Ubah peran (role), data kontak, atau reset kata sandi</p>
        </div>
        <a href="{{ route('users.index') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900">&larr; Kembali</a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200 shadow-sm">
        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Alamat Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                </div>

                <div>
                    <label for="role" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Peran / Hak Akses (Role) *</label>
                    <select name="role" id="role" required
                            class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                        <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff Kasir / Toko</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin / Owner</option>
                        <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer (Pelanggan)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Kata Sandi Baru</label>
                    <input type="password" name="password" id="password"
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition"
                           placeholder="Kosongkan jika tidak ingin diubah">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Alamat Tempat Tinggal</label>
                <textarea name="address" id="address" rows="2"
                          class="w-full px-4 py-2 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">{{ old('address', $user->address) }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <a href="{{ route('users.index') }}" class="px-5 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-coffee-800 hover:bg-coffee-900 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                    Perbarui Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
