@extends('layouts.app')

@section('title', 'Daftar Akun Customer')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <div class="bg-white p-6 sm:p-10 rounded-3xl shadow-sm border border-stone-200">
        <div class="mb-6 text-center">
            <div class="w-12 h-12 rounded-2xl bg-coffee-100 text-coffee-800 flex items-center justify-center mx-auto mb-3 text-xl">
                <i class="fa-solid fa-mug-hot"></i>
            </div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">Pendaftaran Akun Pelanggan</h1>
            <p class="text-xs text-stone-500 mt-1">Buat akun untuk memesan kopi pilihan dan melihat riwayat pesanan</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                       class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/30 focus:border-coffee-600 transition"
                       placeholder="Contoh: Siti Penikmat Kopi">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/30 focus:border-coffee-600 transition"
                           placeholder="nama@email.com">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/30 focus:border-coffee-600 transition"
                           placeholder="0812-xxxx-xxxx">
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Alamat Pengiriman</label>
                <textarea name="address" id="address" rows="2"
                          class="w-full px-4 py-2 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/30 focus:border-coffee-600 transition"
                          placeholder="Alamat lengkap tujuan pemesanan">{{ old('address') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Kata Sandi (Min 6 Karakter)</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/30 focus:border-coffee-600 transition"
                           placeholder="••••••••">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Ulangi Kata Sandi</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/30 focus:border-coffee-600 transition"
                           placeholder="••••••••">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-3 px-4 bg-coffee-800 hover:bg-coffee-900 text-white font-semibold text-sm rounded-xl shadow-md shadow-coffee-900/10 transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Daftar Sekarang</span>
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-xs text-stone-500 pt-4 border-t border-stone-100">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-semibold text-coffee-700 hover:text-coffee-900 underline ml-1">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection
