<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KOPI SENJA') — Temukan Cerita di Setiap Tegukan</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        coffee: {
                            50: '#fdf9f4',
                            100: '#f7ede2',
                            200: '#edd8c4',
                            300: '#dfbea0',
                            400: '#ca9771',
                            500: '#a76d43',
                            600: '#8c522f',
                            700: '#6f3c21',
                            800: '#4c2614',
                            900: '#2c1409',
                            950: '#170904',
                        },
                        cream: '#faf7f2',
                        espresso: '#23150d',
                        warmgold: '#d4af37',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #faf7f2; color: #23150d; }
        .font-serif { font-family: 'Playfair Display', serif; }
        .bg-pattern {
            background-color: #faf7f2;
            background-image: radial-gradient(#d6c7b2 0.75px, transparent 0.75px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-pattern selection:bg-coffee-700 selection:text-white">

    <!-- Top Info & Demo Switcher Bar -->
    <div class="bg-espresso text-stone-300 text-xs py-2 px-4 border-b border-coffee-900/40">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center space-x-2">
                <span class="text-amber-300 font-semibold text-xs tracking-wide"><i class="fa-solid fa-mug-hot mr-1"></i> KOPI SENJA</span>
                <span class="hidden sm:inline text-stone-600">•</span>
                <span class="hidden sm:inline text-stone-300">Coffee Shop & Pastry Management System</span>
            </div>
            
            <div class="flex items-center space-x-3 text-[11px] text-stone-400">
                <span><i class="fa-solid fa-clock text-amber-400 mr-1.5"></i> Jam Buka: 08:00 - 22:00 WIB</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="bg-white/95 backdrop-blur-md border-b border-stone-200/80 sticky top-0 z-50 shadow-sm transition">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <!-- Brand Logo & Tagline -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-coffee-700 to-espresso flex items-center justify-center text-amber-300 shadow-md shadow-coffee-900/20 group-hover:scale-105 transition duration-300">
                        <i class="fa-solid fa-mug-hot text-xl"></i>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-espresso tracking-tight block leading-tight font-serif group-hover:text-coffee-600 transition">KOPI SENJA</span>
                        <span class="text-[10px] text-coffee-600 tracking-wider font-semibold block uppercase">Coffee & Pastry</span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center space-x-1 xl:space-x-2">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition {{ request()->routeIs('home') ? 'text-coffee-700 bg-coffee-50 font-bold' : 'text-stone-600 hover:text-coffee-800 hover:bg-stone-50' }}">
                        Beranda
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'espresso-based']) }}" class="px-3 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition text-stone-600 hover:text-coffee-800 hover:bg-stone-50">
                        Menu Kopi
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'non-coffee']) }}" class="px-3 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition text-stone-600 hover:text-coffee-800 hover:bg-stone-50">
                        Non-Kopi
                    </a>
                    <a href="{{ route('shop.index', ['category' => 'pastry']) }}" class="px-3 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition text-stone-600 hover:text-coffee-800 hover:bg-stone-50">
                        Pastry & Bakery
                    </a>
                    <a href="{{ route('home') }}#tentang-kami" class="px-3 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition text-stone-600 hover:text-coffee-800 hover:bg-stone-50">
                        Tentang Kami
                    </a>
                    <a href="{{ route('home') }}#kontak" class="px-3 py-2 rounded-xl text-xs font-semibold uppercase tracking-wider transition text-stone-600 hover:text-coffee-800 hover:bg-stone-50">
                        Kontak
                    </a>

                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition {{ request()->routeIs('admin.dashboard') || request()->routeIs('dashboard') ? 'bg-coffee-800 text-white shadow-sm' : 'text-coffee-800 hover:bg-coffee-100' }}">
                                <i class="fa-solid fa-crown mr-1 text-amber-400"></i> Dashboard Owner
                            </a>
                        @elseif(auth()->user()->isStaff())
                            <a href="{{ route('staff.dashboard') }}" class="px-3 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition {{ request()->routeIs('staff.dashboard') || request()->routeIs('dashboard') ? 'bg-coffee-800 text-white shadow-sm' : 'text-coffee-800 hover:bg-coffee-100' }}">
                                <i class="fa-solid fa-cash-register mr-1"></i> Dashboard Staff / Admin
                            </a>
                        @else
                            <a href="{{ route('customer.dashboard') }}" class="px-3 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition {{ request()->routeIs('customer.dashboard') ? 'bg-coffee-800 text-white shadow-sm' : 'text-coffee-800 hover:bg-coffee-100' }}">
                                <i class="fa-solid fa-user mr-1"></i> Dashboard Pelanggan
                            </a>
                        @endif
                    @endauth
                </nav>

                <!-- Actions: Cart & Auth Buttons -->
                <div class="flex items-center space-x-3">
                    
                    <!-- Cart Button with Count Badge -->
                    @php
                        $cartCount = array_sum(array_column(session('cart', []), 'quantity'));
                    @endphp
                    <a href="{{ route('cart.index') }}" class="relative p-2.5 rounded-2xl bg-stone-100 hover:bg-coffee-100 text-stone-700 hover:text-coffee-800 transition duration-200" title="Keranjang Belanja">
                        <i class="fa-solid fa-bag-shopping text-lg"></i>
                        @if($cartCount > 0)
                            <span class="absolute -top-1.5 -right-1.5 bg-amber-600 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center border-2 border-white shadow-sm animate-pulse">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    @guest
                        <div class="hidden sm:flex items-center space-x-2">
                            <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-xl text-stone-700 hover:text-coffee-900 hover:bg-stone-100 transition">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-xl bg-espresso hover:bg-coffee-800 text-amber-300 shadow-md shadow-coffee-950/20 transition">
                                Daftar
                            </a>
                        </div>
                    @else
                        <!-- Logged-in User Dropdown -->
                        <div class="relative group" x-data="{ open: false }">
                            <button class="flex items-center space-x-2 p-1.5 rounded-2xl hover:bg-stone-100 transition">
                                <div class="w-9 h-9 rounded-xl bg-coffee-800 text-amber-300 flex items-center justify-center text-xs font-bold shadow-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <div class="hidden md:block text-left text-xs">
                                    <span class="font-bold text-stone-800 block leading-tight truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                                    <span class="text-[10px] text-coffee-600 font-semibold uppercase tracking-wider">
                                        {{ auth()->user()->isAdmin() ? 'Owner (Pemilik)' : (auth()->user()->isStaff() ? 'Staff / Admin Toko' : 'Pelanggan') }}
                                    </span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-[10px] text-stone-400 ml-1"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-1 w-56 bg-white border border-stone-200 rounded-2xl shadow-xl py-2 hidden group-hover:block group-focus-within:block z-50">
                                <div class="px-4 py-2 border-b border-stone-100">
                                    <p class="text-xs font-bold text-stone-900">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-stone-500 truncate">{{ auth()->user()->email }}</p>
                                </div>

                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-crown mr-2 text-amber-600"></i> Dashboard Owner
                                    </a>
                                    <a href="{{ route('products.index') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-mug-hot mr-2 text-coffee-600"></i> Master Produk Kopi
                                    </a>
                                    <a href="{{ route('categories.index') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-tags mr-2 text-coffee-600"></i> Master Kategori
                                    </a>
                                    <a href="{{ route('suppliers.index') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-truck-field mr-2 text-coffee-600"></i> Master Supplier
                                    </a>
                                    <a href="{{ route('purchases.index') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-cart-flatbed mr-2 text-coffee-600"></i> Transaksi Pembelian
                                    </a>
                                    <a href="{{ route('sales.index') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-cash-register mr-2 text-coffee-600"></i> Transaksi Penjualan
                                    </a>
                                    <a href="{{ route('customer-orders.index') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-bell-concierge mr-2 text-coffee-600"></i> Pesanan Pelanggan
                                    </a>
                                    <a href="{{ route('reports.index') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-file-invoice-dollar mr-2 text-coffee-600"></i> Laporan Penjualan
                                    </a>
                                    <a href="{{ route('users.index') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-users-gear mr-2 text-coffee-600"></i> Kelola Akun Staff & User
                                    </a>
                                @elseif(auth()->user()->isStaff())
                                    <a href="{{ route('staff.dashboard') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-chart-line mr-2 text-coffee-600"></i> Dashboard Staff / Admin
                                    </a>
                                    <a href="{{ route('sales.create') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-cash-register mr-2 text-coffee-600"></i> Kasir POS Baru
                                    </a>
                                    <a href="{{ route('customer-orders.index') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-bell-concierge mr-2 text-coffee-600"></i> Pesanan Masuk (Online)
                                    </a>
                                    <a href="{{ route('purchases.create') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-cart-plus mr-2 text-coffee-600"></i> Input Pembelian Stok (PO)
                                    </a>
                                    <a href="{{ route('products.index') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-boxes-stacked mr-2 text-coffee-600"></i> Cek Stok Produk
                                    </a>
                                    <a href="{{ route('sales.index') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-receipt mr-2 text-coffee-600"></i> Riwayat Penjualan
                                    </a>
                                @else
                                    <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-gauge mr-2 text-coffee-600"></i> Dashboard Pelanggan
                                    </a>
                                    <a href="{{ route('shop.my-orders') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-receipt mr-2 text-coffee-600"></i> Pesanan Saya
                                    </a>
                                    <a href="{{ route('customer.profile') }}" class="block px-4 py-2 text-xs font-medium text-stone-700 hover:bg-coffee-50 hover:text-coffee-900">
                                        <i class="fa-solid fa-user-pen mr-2 text-coffee-600"></i> Edit Profil
                                    </a>
                                @endif

                                <div class="border-t border-stone-100 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-xs font-medium text-red-600 hover:bg-red-50 flex items-center">
                                        <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Keluar (Logout)
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest

                    <!-- Mobile Hamburger Menu Button -->
                    <button class="lg:hidden p-2 text-stone-600 hover:text-espresso" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div id="mobile-menu" class="hidden lg:hidden py-4 border-t border-stone-100 space-y-2">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-stone-700 hover:bg-coffee-50">Beranda</a>
                <a href="{{ route('shop.index') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-stone-700 hover:bg-coffee-50">Katalog Menu Lengkap</a>
                <a href="{{ route('cart.index') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-stone-700 hover:bg-coffee-50">Keranjang ({{ $cartCount }})</a>
                @guest
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-stone-700 hover:bg-coffee-50">Masuk</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-stone-700 hover:bg-coffee-50">Daftar Akun Baru</a>
                @else
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-xl text-sm font-semibold text-coffee-800 hover:bg-coffee-50">Buka Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-sm font-semibold text-red-600">Keluar</button>
                    </form>
                @endguest
            </div>
        </div>
    </header>

    <!-- Global Alert / Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-2xl flex items-center space-x-3 shadow-sm animate-fade-in mb-4">
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg flex-shrink-0"></i>
                <span class="text-xs sm:text-sm font-medium leading-relaxed">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3.5 rounded-2xl flex items-center space-x-3 shadow-sm animate-fade-in mb-4">
                <i class="fa-solid fa-circle-exclamation text-red-600 text-lg flex-shrink-0"></i>
                <span class="text-xs sm:text-sm font-medium leading-relaxed">{{ session('error') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3.5 rounded-2xl flex items-center space-x-3 shadow-sm animate-fade-in mb-4">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 text-lg flex-shrink-0"></i>
                <span class="text-xs sm:text-sm font-medium leading-relaxed">{{ session('warning') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="bg-sky-50 border border-sky-200 text-sky-800 px-4 py-3.5 rounded-2xl flex items-center space-x-3 shadow-sm animate-fade-in mb-4">
                <i class="fa-solid fa-circle-info text-sky-600 text-lg flex-shrink-0"></i>
                <span class="text-xs sm:text-sm font-medium leading-relaxed">{{ session('info') }}</span>
            </div>
        @endif
    </div>

    <!-- Main Content Slot -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Aesthetic Coffee Shop Footer -->
    <footer class="bg-espresso text-stone-300 mt-16 border-t border-coffee-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Col 1: Brand Info -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-400 text-espresso flex items-center justify-center font-bold text-lg shadow-md">
                            ☕
                        </div>
                        <div>
                            <span class="text-xl font-bold text-white tracking-tight font-serif block">KOPI SENJA</span>
                            <span class="text-[10px] text-amber-300/80 uppercase tracking-widest font-semibold block">Coffee & Pastry</span>
                        </div>
                    </div>
                    <p class="text-xs text-stone-400 leading-relaxed font-serif italic text-amber-200/90">
                        “Temukan Cerita di Setiap Tegukan.”
                    </p>
                    <p class="text-xs text-stone-400 leading-relaxed">
                        Menghadirkan racikan biji kopi nusantara pilihan berpadu dengan pastry renyah mentega Prancis otentik.
                    </p>
                    <div class="flex items-center space-x-3 pt-2">
                        <a href="#" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-amber-400 hover:text-espresso flex items-center justify-center text-xs transition"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-amber-400 hover:text-espresso flex items-center justify-center text-xs transition"><i class="fa-brands fa-whatsapp"></i></a>
                        <a href="#" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-amber-400 hover:text-espresso flex items-center justify-center text-xs transition"><i class="fa-solid fa-envelope"></i></a>
                    </div>
                </div>

                <!-- Col 2: Kategori Menu -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-amber-300 uppercase tracking-wider">Kategori Menu</h4>
                    <ul class="space-y-2 text-xs text-stone-400">
                        <li><a href="{{ route('shop.index', ['category' => 'espresso-based']) }}" class="hover:text-amber-300 transition">• Espresso Based</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'signature-coffee']) }}" class="hover:text-amber-300 transition">• Signature Coffee</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'manual-brew']) }}" class="hover:text-amber-300 transition">• Manual Brew V60</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'non-coffee']) }}" class="hover:text-amber-300 transition">• Non-Coffee & Chocolate</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'pastry']) }}" class="hover:text-amber-300 transition">• Pastry & Bakery</a></li>
                    </ul>
                </div>

                <!-- Col 3: Jam Operasional -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-amber-300 uppercase tracking-wider">Jam Operasional</h4>
                    <div class="space-y-2 text-xs text-stone-400">
                        <div class="flex justify-between border-b border-white/5 pb-1">
                            <span>Senin — Jumat:</span>
                            <span class="text-stone-200 font-medium">08:00 – 22:00</span>
                        </div>
                        <div class="flex justify-between border-b border-white/5 pb-1">
                            <span>Sabtu — Minggu:</span>
                            <span class="text-stone-200 font-medium">07:00 – 23:00</span>
                        </div>
                        <p class="text-[11px] text-amber-400/80 pt-1">
                            <i class="fa-solid fa-clock mr-1"></i> Dine In & Take Away selalu tersedia.
                        </p>
                    </div>
                </div>

                <!-- Col 4: Lokasi & Kontak -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-amber-300 uppercase tracking-wider">Lokasi & Kontak</h4>
                    <p class="text-xs text-stone-400 leading-relaxed">
                        <i class="fa-solid fa-location-dot text-amber-400 mr-1.5"></i> Jl. Senja Coffee No. 8, Senopati, Kebayoran Baru, Jakarta Selatan
                    </p>
                    <p class="text-xs text-stone-400">
                        <i class="fa-solid fa-phone text-amber-400 mr-1.5"></i> +62 812-3456-7890
                    </p>
                    <p class="text-xs text-stone-400">
                        <i class="fa-solid fa-envelope text-amber-400 mr-1.5"></i> halo@kopisenja.id
                    </p>
                </div>
            </div>

            <!-- Bottom Copyright Info -->
            <div class="mt-12 pt-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-stone-400">
                <p>&copy; {{ date('Y') }} <strong>KOPI SENJA</strong>. All Rights Reserved. Integrated Laravel & MySQL System.</p>
                <div class="flex items-center space-x-2">
                    <span class="text-[11px] text-stone-400">Coffee Shop & Pastry Management System</span>
                </div>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
