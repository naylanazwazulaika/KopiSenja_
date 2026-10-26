@extends('layouts.app')

@section('title', 'Masuk ke Sistem — KOPI SENJA')

@section('content')
<div class="max-w-xl mx-auto py-8 sm:py-12">
    <div class="bg-white p-6 sm:p-10 rounded-3xl shadow-sm border border-stone-200">
        <div class="mb-6 text-center">
            <div class="w-12 h-12 rounded-2xl bg-coffee-100 text-coffee-800 flex items-center justify-center mx-auto mb-3 text-xl shadow-inner">
                <i class="fa-solid fa-mug-hot"></i>
            </div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">Selamat Datang</h1>
            <p class="text-xs text-stone-500 mt-1">Masuk dengan email dan kata sandi akun Anda</p>
        </div>

        @if($errors->any())
            <div class="mb-5 p-3.5 bg-red-50 border border-red-200 text-red-700 text-xs rounded-2xl flex items-center space-x-2.5">
                <i class="fa-solid fa-circle-exclamation text-red-500 flex-shrink-0 text-sm"></i>
                <span>{{ $errors->first('email') ?: 'Email atau kata sandi yang Anda masukkan salah.' }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Pilihan Role Pengguna (3 Kartu Sejajar) -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2 text-center sm:text-left">
                    Pilih Role Akun
                </label>
                <div class="grid grid-cols-3 gap-2 sm:gap-3">
                    <!-- 1. Owner -->
                    <label for="role_owner" class="cursor-pointer group block">
                        <input type="radio" name="role" id="role_owner" value="owner" class="peer sr-only" {{ old('role') === 'owner' ? 'checked' : '' }} required>
                        <div class="py-2.5 px-2 rounded-2xl border-2 border-stone-200 bg-stone-50 hover:bg-stone-100 text-stone-700 text-center transition-all duration-200 flex flex-col items-center justify-center space-y-1 peer-checked:bg-coffee-900 peer-checked:text-amber-300 peer-checked:border-coffee-950 peer-checked:ring-2 peer-checked:ring-coffee-700/50 peer-checked:shadow-md">
                            <span class="text-base sm:text-lg">👑</span>
                            <span class="text-xs font-bold block leading-tight">Owner</span>
                        </div>
                    </label>

                    <!-- 2. Staff/Admin -->
                    <label for="role_staff" class="cursor-pointer group block">
                        <input type="radio" name="role" id="role_staff" value="staff" class="peer sr-only" {{ old('role') === 'staff' ? 'checked' : '' }}>
                        <div class="py-2.5 px-2 rounded-2xl border-2 border-stone-200 bg-stone-50 hover:bg-stone-100 text-stone-700 text-center transition-all duration-200 flex flex-col items-center justify-center space-y-1 peer-checked:bg-coffee-900 peer-checked:text-amber-300 peer-checked:border-coffee-950 peer-checked:ring-2 peer-checked:ring-coffee-700/50 peer-checked:shadow-md">
                            <span class="text-base sm:text-lg">🧾</span>
                            <span class="text-xs font-bold block leading-tight">Staff/Admin</span>
                        </div>
                    </label>

                    <!-- 3. Pelanggan -->
                    <label for="role_customer" class="cursor-pointer group block">
                        <input type="radio" name="role" id="role_customer" value="customer" class="peer sr-only" {{ old('role', 'customer') === 'customer' ? 'checked' : '' }}>
                        <div class="py-2.5 px-2 rounded-2xl border-2 border-stone-200 bg-stone-50 hover:bg-stone-100 text-stone-700 text-center transition-all duration-200 flex flex-col items-center justify-center space-y-1 peer-checked:bg-coffee-900 peer-checked:text-amber-300 peer-checked:border-coffee-950 peer-checked:ring-2 peer-checked:ring-coffee-700/50 peer-checked:shadow-md">
                            <span class="text-base sm:text-lg">👤</span>
                            <span class="text-xs font-bold block leading-tight">Pelanggan</span>
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-stone-400 text-sm">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="w-full pl-10 pr-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-coffee-500/30 focus:border-coffee-600 transition"
                           placeholder="nama@email.com">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-stone-400 text-sm">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" id="password" required
                           class="w-full pl-10 pr-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-coffee-500/30 focus:border-coffee-600 transition"
                           placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-coffee-600 rounded border-stone-300 focus:ring-coffee-500">
                    <span class="text-stone-600">Ingat saya</span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 px-4 bg-coffee-800 hover:bg-coffee-900 text-white font-semibold text-sm rounded-xl shadow-md shadow-coffee-900/10 transition flex items-center justify-center space-x-2">
                    <span>Masuk ke Akun</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-xs text-stone-500 pt-4 border-t border-stone-100">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="font-semibold text-coffee-700 hover:text-coffee-900 underline ml-1">Daftar sekarang sebagai Customer</a>
        </div>
    </div>
</div>
@endsection
