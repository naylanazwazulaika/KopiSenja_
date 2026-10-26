@extends('layouts.app')

@section('title', '403 — Akses Ditolak | KOPI SENJA')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full bg-white rounded-3xl p-8 sm:p-10 border border-stone-200/80 shadow-2xl text-center space-y-6 relative overflow-hidden">
        
        <!-- Background subtle watermark -->
        <div class="absolute -right-6 -bottom-6 text-stone-100 text-9xl font-serif font-black select-none pointer-events-none">
            403
        </div>

        <div class="relative z-10 space-y-4">
            <!-- Icon -->
            <div class="w-20 h-20 rounded-3xl bg-red-50 text-red-600 border border-red-200/60 flex items-center justify-center mx-auto text-3xl shadow-inner">
                <i class="fa-solid fa-shield-halved"></i>
            </div>

            <div class="space-y-2">
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-red-100 text-red-800 border border-red-200">
                    <i class="fa-solid fa-lock text-[10px]"></i>
                    <span>HTTP 403 — Hak Akses Dibatasi</span>
                </span>
                
                <h1 class="text-2xl sm:text-3xl font-bold font-serif text-espresso">
                    Anda Tidak Memiliki Akses ke Halaman Ini
                </h1>

                <p class="text-xs sm:text-sm text-stone-600 leading-relaxed max-w-md mx-auto">
                    {{ $exception->getMessage() ?: 'Anda tidak memiliki akses ke halaman ini.' }}
                </p>
            </div>

            <!-- Role Badge info if logged in -->
            @auth
                <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200 text-left text-xs space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="text-stone-500">Pengguna Masuk:</span>
                        <span class="font-bold text-stone-900">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-stone-500">Tingkat Role Akun:</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                            {{ auth()->user()->isAdmin() ? 'bg-purple-100 text-purple-800 border border-purple-200' : '' }}
                            {{ auth()->user()->isStaff() ? 'bg-blue-100 text-blue-800 border border-blue-200' : '' }}
                            {{ auth()->user()->isCustomer() ? 'bg-amber-100 text-amber-800 border border-amber-200' : '' }}
                        ">
                            {{ strtoupper(auth()->user()->role) }}
                        </span>
                    </div>
                </div>
            @endauth

            <!-- Action Buttons -->
            <div class="pt-2 flex flex-col sm:flex-row gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="flex-1 py-3 px-4 bg-espresso hover:bg-coffee-800 text-amber-300 font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition text-center inline-flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-gauge"></i>
                        <span>Kembali ke Dashboard Saya</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex-1 py-3 px-4 bg-espresso hover:bg-coffee-800 text-amber-300 font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition text-center inline-flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                        <span>Masuk ke Akun Anda</span>
                    </a>
                @endauth

                <a href="{{ route('home') }}" class="py-3 px-4 bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-xs uppercase tracking-wider rounded-xl transition text-center inline-flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-house"></i>
                    <span>Beranda Menu</span>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
