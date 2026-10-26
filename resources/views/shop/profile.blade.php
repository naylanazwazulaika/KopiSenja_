@extends('layouts.app')

@section('title', 'Profil Saya — KOPI SENJA')

@section('content')
<div class="space-y-8 max-w-3xl mx-auto">

    <!-- Header Breadcrumbs -->
    <div class="flex items-center space-x-2 text-xs text-stone-500">
        <a href="{{ route('home') }}" class="hover:text-coffee-700">Beranda</a>
        <span>/</span>
        <a href="{{ route('customer.dashboard') }}" class="hover:text-coffee-700">Dashboard</a>
        <span>/</span>
        <span class="text-espresso font-semibold">Edit Profil Akun</span>
    </div>

    <div>
        <h1 class="text-3xl font-bold font-serif text-espresso">Kelola Profil Akun</h1>
        <p class="text-xs text-stone-500 mt-1">Perbarui data diri, nomor kontak pemesanan, dan kata sandi keamanan akun Anda.</p>
    </div>

    <div class="bg-white rounded-3xl p-8 border border-stone-200/80 shadow-sm space-y-6">
        
        <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="flex items-center space-x-4 pb-6 border-b border-stone-100">
                <div class="w-16 h-16 rounded-2xl bg-espresso text-amber-300 flex items-center justify-center font-bold text-xl shadow-md">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <h3 class="text-base font-bold text-espresso">{{ $user->name }}</h3>
                    <p class="text-xs text-stone-500">{{ $user->email }}</p>
                    <span class="inline-block mt-1 text-[10px] font-bold uppercase tracking-wider text-coffee-700 bg-coffee-50 px-2.5 py-0.5 rounded-full border border-coffee-200">
                        Role: {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>

            <!-- Nama Lengkap -->
            <div class="space-y-1.5">
                <label for="name" class="block text-xs font-bold text-stone-700">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-3.5 py-2.5 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                @error('name')
                    <p class="text-[11px] text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-bold text-stone-700">Alamat Email <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-3.5 py-2.5 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                @error('email')
                    <p class="text-[11px] text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nomor Telepon / WA -->
            <div class="space-y-1.5">
                <label for="phone" class="block text-xs font-bold text-stone-700">Nomor WhatsApp / HP</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                       placeholder="Contoh: 0812-3456-7890"
                       class="w-full px-3.5 py-2.5 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                @error('phone')
                    <p class="text-[11px] text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Alamat -->
            <div class="space-y-1.5">
                <label for="address" class="block text-xs font-bold text-stone-700">Alamat Lengkap</label>
                <textarea id="address" name="address" rows="3"
                          placeholder="Alamat domisili atau kantor Anda..."
                          class="w-full px-3.5 py-2.5 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">{{ old('address', $user->address) }}</textarea>
                @error('address')
                    <p class="text-[11px] text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-stone-100">
                <h4 class="text-xs font-bold text-espresso uppercase tracking-wider mb-1">Ganti Password (Opsional)</h4>
                <p class="text-[11px] text-stone-400 mb-4">Kosongkan jika tidak ingin mengubah kata sandi Anda.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-bold text-stone-700">Password Baru</label>
                        <input type="password" id="password" name="password" placeholder="Minimal 6 karakter"
                               class="w-full px-3.5 py-2.5 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                        @error('password')
                            <p class="text-[11px] text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-xs font-bold text-stone-700">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru"
                               class="w-full px-3.5 py-2.5 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-stone-100 flex items-center justify-end space-x-3">
                <a href="{{ route('customer.dashboard') }}" class="px-5 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-espresso hover:bg-coffee-800 text-amber-300 text-xs font-bold uppercase tracking-wider rounded-xl shadow-md transition">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i> Simpan Perubahan Profil
                </button>
            </div>
        </form>

    </div>

</div>
@endsection
