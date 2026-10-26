@extends('layouts.app')

@section('title', 'KOPI SENJA — Temukan Cerita di Setiap Tegukan')

@section('content')
<div class="space-y-16">

    <!-- HERO SECTION -->
    <section class="relative bg-gradient-to-r from-espresso via-coffee-950 to-coffee-900 rounded-3xl p-8 sm:p-14 lg:p-16 text-white overflow-hidden shadow-2xl border border-coffee-800/50">
        <!-- Background Overlay Image -->
        <div class="absolute inset-0 opacity-15 bg-cover bg-center mix-blend-overlay pointer-events-none" 
             style="background-image: url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1600&q=80');">
        </div>

        <div class="relative z-10 max-w-2xl space-y-6">
            <span class="inline-flex items-center space-x-2 bg-amber-400/20 text-amber-300 text-xs px-4 py-1.5 rounded-full font-bold tracking-wide uppercase border border-amber-400/30">
                <i class="fa-solid fa-mug-saucer text-[11px]"></i>
                <span>Artisan Roastery & Pastry Kitchen</span>
            </span>

            <h1 class="text-4xl sm:text-6xl font-bold font-serif tracking-tight leading-tight text-cream">
                Temukan Cerita di Setiap Tegukan.
            </h1>

            <p class="text-sm sm:text-base text-stone-300 leading-relaxed max-w-xl font-light">
                Secangkir kopi bukan sekadar kafein, melainkan perjalanan rasa dari petani pegunungan nusantara, disangrai dengan presisi, dan disajikan dengan kehangatan bersama pastry renyah bermentega Prancis.
            </p>

            <div class="pt-2 flex flex-wrap items-center gap-4">
                <a href="{{ route('shop.index') }}" class="px-7 py-3.5 bg-amber-400 hover:bg-amber-300 text-espresso text-xs font-extrabold uppercase tracking-wider rounded-2xl shadow-lg shadow-amber-500/20 transition duration-300 transform hover:-translate-y-0.5 inline-flex items-center space-x-2">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span>Pesan Sekarang</span>
                </a>
                <a href="#kategori-menu" class="px-7 py-3.5 bg-white/10 hover:bg-white/20 text-white text-xs font-bold uppercase tracking-wider rounded-2xl border border-white/20 transition duration-300 inline-flex items-center space-x-2">
                    <i class="fa-solid fa-compass"></i>
                    <span>Jelajahi Menu</span>
                </a>
            </div>

            <!-- Highlights Pill Badges -->
            <div class="pt-4 grid grid-cols-3 gap-4 border-t border-white/10 text-stone-300">
                <div>
                    <span class="block text-xl font-serif font-bold text-amber-300">100%</span>
                    <span class="text-[11px] text-stone-400">Biji Kopi Specialty</span>
                </div>
                <div>
                    <span class="block text-xl font-serif font-bold text-amber-300">Fresh</span>
                    <span class="text-[11px] text-stone-400">Dipanggang Harian</span>
                </div>
                <div>
                    <span class="block text-xl font-serif font-bold text-amber-300">4.9/5</span>
                    <span class="text-[11px] text-stone-400">Kepuasan Pelanggan</span>
                </div>
            </div>
        </div>

        <!-- Decorative Right Image Graphic -->
        <div class="hidden lg:block absolute -right-6 -bottom-10 w-[420px] h-[420px] rounded-full overflow-hidden border-8 border-white/10 shadow-2xl pointer-events-none transform rotate-3">
            <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80" 
                 alt="Kopi Senja Atmosphere" 
                 class="w-full h-full object-cover">
        </div>
    </section>

    <!-- KATEGORI PRODUK -->
    <section id="kategori-menu" class="space-y-6">
        <div class="text-center max-w-xl mx-auto space-y-2">
            <span class="text-xs font-bold uppercase tracking-widest text-coffee-600">Pilihan Menyeluruh</span>
            <h2 class="text-2xl sm:text-3xl font-bold font-serif text-espresso">Kategori Kopi & Hidangan Kami</h2>
            <p class="text-xs sm:text-sm text-stone-600">
                Pilih racikan minuman favorit atau nikmati sepotong pastry hangat yang melengkapi momen santai Anda.
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('shop.index', ['category' => $category->slug]) }}" 
                   class="bg-white rounded-2xl p-5 border border-stone-200/80 shadow-sm hover:shadow-md hover:border-coffee-300 hover:-translate-y-1 transition duration-300 flex flex-col justify-between group">
                    <div class="space-y-2">
                        <div class="w-10 h-10 rounded-xl bg-coffee-50 group-hover:bg-coffee-700 text-coffee-700 group-hover:text-amber-300 flex items-center justify-center transition duration-300 text-base">
                            @if(str_contains($category->slug, 'espresso'))
                                <i class="fa-solid fa-mug-hot"></i>
                            @elseif(str_contains($category->slug, 'signature'))
                                <i class="fa-solid fa-star"></i>
                            @elseif(str_contains($category->slug, 'manual'))
                                <i class="fa-solid fa-filter"></i>
                            @elseif(str_contains($category->slug, 'non-coffee'))
                                <i class="fa-solid fa-wine-glass"></i>
                            @elseif(str_contains($category->slug, 'tea'))
                                <i class="fa-solid fa-leaf"></i>
                            @elseif(str_contains($category->slug, 'pastry'))
                                <i class="fa-solid fa-bread-slice"></i>
                            @elseif(str_contains($category->slug, 'bakery'))
                                <i class="fa-solid fa-cookie"></i>
                            @else
                                <i class="fa-solid fa-bowl-food"></i>
                            @endif
                        </div>
                        <h3 class="font-bold text-sm text-espresso group-hover:text-coffee-700 transition">
                            {{ $category->name }}
                        </h3>
                        <p class="text-[11px] text-stone-500 line-clamp-2 leading-relaxed">
                            {{ $category->description ?? 'Pilihan menu istimewa Kopi Senja.' }}
                        </p>
                    </div>
                    <div class="pt-4 flex items-center justify-between text-[11px] text-coffee-700 font-semibold border-t border-stone-100 mt-3">
                        <span>{{ $category->products_count }} Menu</span>
                        <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition duration-200"></i>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- PRODUK UNGGULAN -->
    <section class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div class="space-y-1">
                <span class="text-xs font-bold uppercase tracking-widest text-coffee-600">Menu Rekomendasi</span>
                <h2 class="text-2xl sm:text-3xl font-bold font-serif text-espresso">Produk Unggulan Hari Ini</h2>
            </div>
            <a href="{{ route('shop.index') }}" class="text-xs font-bold text-coffee-700 hover:text-espresso uppercase tracking-wider inline-flex items-center space-x-1.5 transition">
                <span>Lihat Seluruh Menu</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                <div class="bg-white rounded-3xl border border-stone-200/80 shadow-sm overflow-hidden flex flex-col justify-between group hover:shadow-xl hover:border-coffee-300 transition duration-300">
                    
                    <!-- Product Image & Badges -->
                    <div class="relative h-48 overflow-hidden bg-stone-100">
                        <img src="{{ $product->image_url }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        
                        <div class="absolute top-3 left-3 flex flex-col gap-1.5">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-white bg-espresso/80 backdrop-blur-md px-2.5 py-1 rounded-lg">
                                {{ $product->category->name ?? 'Menu' }}
                            </span>
                        </div>

                        <div class="absolute top-3 right-3">
                            @if($product->stock <= 0)
                                <span class="text-[10px] font-bold text-red-700 bg-red-100 px-2.5 py-1 rounded-full border border-red-200">
                                    Stok Habis
                                </span>
                            @elseif($product->is_low_stock)
                                <span class="text-[10px] font-bold text-amber-800 bg-amber-100 px-2.5 py-1 rounded-full border border-amber-300">
                                    Sisa {{ $product->stock }}!
                                </span>
                            @else
                                <span class="text-[10px] font-medium text-emerald-800 bg-emerald-100/90 px-2.5 py-1 rounded-full">
                                    Tersedia ({{ $product->stock }})
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Product Body -->
                    <div class="p-5 flex-grow flex flex-col justify-between space-y-4">
                        <div class="space-y-1.5">
                            <h3 class="font-bold text-base text-espresso group-hover:text-coffee-700 transition leading-snug">
                                <a href="{{ route('shop.show', $product) }}">{{ $product->name }}</a>
                            </h3>
                            <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed font-light">
                                {{ $product->description }}
                            </p>
                        </div>

                        <!-- Price & Add to Cart -->
                        <div class="pt-3 border-t border-stone-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-stone-400 uppercase tracking-wider block font-semibold">Harga</span>
                                <span class="text-base font-bold text-coffee-800 font-serif">
                                    Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                </span>
                            </div>

                            @if($product->stock > 0)
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" 
                                            class="p-2.5 rounded-xl bg-espresso hover:bg-coffee-700 text-amber-300 transition duration-200 shadow-md shadow-coffee-950/10 flex items-center space-x-1.5 text-xs font-semibold"
                                            title="Tambah ke Keranjang">
                                        <i class="fa-solid fa-plus"></i>
                                        <span class="hidden sm:inline">Pesan</span>
                                    </button>
                                </form>
                            @else
                                <button disabled class="p-2.5 rounded-xl bg-stone-100 text-stone-400 text-xs font-semibold cursor-not-allowed">
                                    Habis
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- TENTANG KAMI SECTION -->
    <section id="tentang-kami" class="bg-white rounded-3xl p-8 sm:p-12 border border-stone-200/80 shadow-sm overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            
            <div class="space-y-5">
                <span class="text-xs font-bold uppercase tracking-widest text-coffee-600">Cerita Kami</span>
                <h2 class="text-3xl sm:text-4xl font-bold font-serif text-espresso leading-tight">
                    Mendedikasikan Hati untuk Setiap Cangkir dan Gigitan Pastry.
                </h2>
                
                <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">
                    <strong>KOPI SENJA</strong> bermula dari kecintaan mendalam terhadap aroma kopi yang menguar saat matahari terbenam. Kami percaya bahwa di balik setiap tegukan kopi yang nikmat, tersimpan dedikasi para petani lokal dari Aceh Gayo hingga Toraja Sapan.
                </p>

                <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">
                    Setiap biji kopi kami kurasi secara langsung dari pemasok terpercaya, lalu disangrai oleh roaster berpengalaman. Ditemani croissant dan brownies yang dipanggang segar di dapur pastry kami setiap fajar, KOPI SENJA hadir sebagai ruang nyaman untuk bekerja, bercerita, dan merayakan jeda.
                </p>

                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div class="p-4 rounded-2xl bg-cream border border-coffee-200/60">
                        <i class="fa-solid fa-seedling text-coffee-700 text-lg mb-1"></i>
                        <h4 class="font-bold text-xs text-espresso">Etika & Kualitas</h4>
                        <p class="text-[11px] text-stone-500">Kemitraan langsung dengan petani lokal nusantara.</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-cream border border-coffee-200/60">
                        <i class="fa-solid fa-fire-burner text-coffee-700 text-lg mb-1"></i>
                        <h4 class="font-bold text-xs text-espresso">Presisi Sangrai</h4>
                        <p class="text-[11px] text-stone-500">Profil roasting konsisten untuk mengekstraksi notes terbaik.</p>
                    </div>
                </div>
            </div>

            <!-- Image collage -->
            <div class="grid grid-cols-2 gap-4">
                <img src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=600&q=80" 
                     alt="Barista at work" 
                     class="w-full h-64 object-cover rounded-3xl shadow-md">
                <img src="https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=600&q=80" 
                     alt="Fresh Butter Croissant" 
                     class="w-full h-64 object-cover rounded-3xl shadow-md mt-6">
            </div>
        </div>
    </section>

    <!-- TESTIMONI PELANGGAN -->
    <section class="space-y-6">
        <div class="text-center max-w-xl mx-auto space-y-2">
            <span class="text-xs font-bold uppercase tracking-widest text-coffee-600">Kata Penikmat Kopi</span>
            <h2 class="text-2xl sm:text-3xl font-bold font-serif text-espresso">Pengalaman Berkesan di Kopi Senja</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-stone-200/80 shadow-sm space-y-4">
                <div class="flex text-amber-400 text-xs space-x-1">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
                <p class="text-xs sm:text-sm text-stone-600 italic leading-relaxed">
                    “Kopi Senja Aren Signature rasanya luar biasa seimbang. Gula arennya harum dan kopinya tetap terasa bold tanpa bikin lambung kembung. Croissant-nya renyah banget!”
                </p>
                <div class="flex items-center space-x-3 pt-2 border-t border-stone-100">
                    <div class="w-9 h-9 rounded-full bg-coffee-100 text-coffee-800 flex items-center justify-center font-bold text-xs">
                        AP
                    </div>
                    <div>
                        <h4 class="font-bold text-xs text-espresso">Andi Pratama</h4>
                        <span class="text-[10px] text-stone-400">Software Engineer & Penikmat V60</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-stone-200/80 shadow-sm space-y-4">
                <div class="flex text-amber-400 text-xs space-x-1">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
                <p class="text-xs sm:text-sm text-stone-600 italic leading-relaxed">
                    “Tempat favorit saya untuk WFH sore hari. Suasananya hangat, playlist-nya nyaman, dan V60 Aceh Gayo-nya salah satu yang paling bersih dan fruity yang pernah saya coba.”
                </p>
                <div class="flex items-center space-x-3 pt-2 border-t border-stone-100">
                    <div class="w-9 h-9 rounded-full bg-coffee-100 text-coffee-800 flex items-center justify-center font-bold text-xs">
                        DA
                    </div>
                    <div>
                        <h4 class="font-bold text-xs text-espresso">Dian Anggraini</h4>
                        <span class="text-[10px] text-stone-400">Creative Designer</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-stone-200/80 shadow-sm space-y-4">
                <div class="flex text-amber-400 text-xs space-x-1">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
                <p class="text-xs sm:text-sm text-stone-600 italic leading-relaxed">
                    “Fitur pemesanan online-nya sangat praktis! Begitu saya datang untuk take away, pesanan sudah siap diambil tanpa antre lama. Pelayanan barista sangat ramah.”
                </p>
                <div class="flex items-center space-x-3 pt-2 border-t border-stone-100">
                    <div class="w-9 h-9 rounded-full bg-coffee-100 text-coffee-800 flex items-center justify-center font-bold text-xs">
                        RH
                    </div>
                    <div>
                        <h4 class="font-bold text-xs text-espresso">Rizky Hidayat</h4>
                        <span class="text-[10px] text-stone-400">Pelanggan Setia Kopi Senja</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- INFORMASI KONTAK & LOKASI KEDAI -->
    <section id="kontak" class="bg-gradient-to-br from-espresso to-coffee-950 text-white rounded-3xl p-8 sm:p-12 shadow-xl border border-coffee-800/40">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            
            <div class="space-y-4">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-300">Kunjungi Outlet Kami</span>
                <h2 class="text-2xl sm:text-3xl font-bold font-serif">Kedai Kopi Senja Senopati</h2>
                <p class="text-xs sm:text-sm text-stone-300 leading-relaxed font-light">
                    Nikmati suasana kedai dengan semerbak aroma sangrai kopi dan pastry yang baru saja keluar dari oven. Ruang indoor sejuk dan area outdoor nyaman menanti Anda.
                </p>

                <div class="space-y-3 pt-2 text-xs text-stone-300">
                    <div class="flex items-start space-x-3">
                        <i class="fa-solid fa-map-location-dot text-amber-400 mt-0.5"></i>
                        <span>Jl. Senja Coffee No. 8, Senopati, Kebayoran Baru, Jakarta Selatan 12190</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-clock text-amber-400"></i>
                        <span>Buka Setiap Hari: 08:00 – 22:00 WIB</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-phone text-amber-400"></i>
                        <span>+62 812-3456-7890 (Reservasi & WhatsApp)</span>
                    </div>
                </div>
            </div>

            <!-- Google Maps Embed or Aesthetic Box -->
            <div class="h-64 sm:h-72 rounded-2xl overflow-hidden border border-white/10 shadow-lg relative bg-stone-900 flex items-center justify-center">
                <iframe 
                    class="w-full h-full border-0 filter contrast-125"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.275149363597!2d106.80489957499039!3d-6.227409293760775!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f1506a6b5777%3A0xb33bc3a5e84845a7!2sSenopati%2C%20Kebayoran%20Baru%2C%20South%20Jakarta%20City%2C%20Jakarta!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>

        </div>
    </section>

</div>
@endsection
