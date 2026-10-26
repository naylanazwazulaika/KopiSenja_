@extends('layouts.app')

@section('title', 'Checkout Pesanan — KOPI SENJA')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">

    <!-- Header Breadcrumbs -->
    <div class="flex items-center space-x-2 text-xs text-stone-500">
        <a href="{{ route('home') }}" class="hover:text-coffee-700">Beranda</a>
        <span>/</span>
        <a href="{{ route('cart.index') }}" class="hover:text-coffee-700">Keranjang</a>
        <span>/</span>
        <span class="text-espresso font-semibold">Konfirmasi Checkout</span>
    </div>

    <div>
        <h1 class="text-3xl font-bold font-serif text-espresso">Checkout Pesanan Kopi</h1>
        <p class="text-xs text-stone-500 mt-1">Lengkapi informasi penerima dan opsi penyajian pesanan Anda.</p>
    </div>

    <form method="POST" action="{{ route('cart.process-checkout') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        @csrf

        <!-- Customer & Order Details (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Recipient Information Card -->
            <div class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-sm space-y-5">
                <div class="flex items-center space-x-3 pb-3 border-b border-stone-100">
                    <div class="w-8 h-8 rounded-xl bg-coffee-50 text-coffee-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-espresso">1. Informasi Penerima</h3>
                        <p class="text-[11px] text-stone-400">Pastikan nomor telepon aktif untuk konfirmasi barista.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nama Penerima -->
                    <div class="space-y-1.5">
                        <label for="customer_name" class="block text-xs font-bold text-stone-700">Nama Penerima <span class="text-red-500">*</span></label>
                        <input type="text" id="customer_name" name="customer_name" 
                               value="{{ old('customer_name', $user->name) }}" required
                               placeholder="Nama lengkap Anda"
                               class="w-full px-3.5 py-2.5 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                        @error('customer_name')
                            <p class="text-[11px] text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Telepon / WA -->
                    <div class="space-y-1.5">
                        <label for="customer_phone" class="block text-xs font-bold text-stone-700">Nomor WhatsApp / HP <span class="text-red-500">*</span></label>
                        <input type="text" id="customer_phone" name="customer_phone" 
                               value="{{ old('customer_phone', $user->phone) }}" required
                               placeholder="Contoh: 0812-3456-7890"
                               class="w-full px-3.5 py-2.5 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                        @error('customer_phone')
                            <p class="text-[11px] text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Serving Method & Notes Card -->
            <div class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-sm space-y-5">
                <div class="flex items-center space-x-3 pb-3 border-b border-stone-100">
                    <div class="w-8 h-8 rounded-xl bg-coffee-50 text-coffee-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-mug-saucer"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-espresso">2. Metode Penyajian & Catatan Barista</h3>
                        <p class="text-[11px] text-stone-400">Pilih santap di kedai atau bungkus bawa pulang.</p>
                    </div>
                </div>

                <!-- Order Type (Dine In vs Take Away) -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-stone-700">Pilihan Penyajian <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="order_type" value="dine_in" class="peer sr-only" {{ old('order_type', 'dine_in') === 'dine_in' ? 'checked' : '' }}>
                            <div class="p-4 rounded-2xl border-2 border-stone-200 peer-checked:border-coffee-700 peer-checked:bg-coffee-50/50 transition flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-coffee-100 text-coffee-800 flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-chair"></i>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-espresso">Dine In</span>
                                    <span class="block text-[10px] text-stone-500">Makan di Kedai Kopi Senja</span>
                                </div>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="order_type" value="take_away" class="peer sr-only" {{ old('order_type') === 'take_away' ? 'checked' : '' }}>
                            <div class="p-4 rounded-2xl border-2 border-stone-200 peer-checked:border-coffee-700 peer-checked:bg-coffee-50/50 transition flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-coffee-100 text-coffee-800 flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-bag-shopping"></i>
                                </div>
                                <div>
                                    <span class="block text-xs font-bold text-espresso">Take Away</span>
                                    <span class="block text-[10px] text-stone-500">Bawa Pulang (Cup & Paperbag)</span>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('order_type')
                        <p class="text-[11px] text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes for Barista -->
                <div class="space-y-1.5 pt-2">
                    <label for="notes" class="block text-xs font-bold text-stone-700">
                        Catatan Khusus untuk Barista (Opsional)
                    </label>
                    <textarea id="notes" name="notes" rows="3" 
                              placeholder="Contoh: Es dipisah, less sugar pada caramel macchiato, nomor meja 5 jika sudah duduk..."
                              class="w-full px-3.5 py-2.5 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">{{ old('notes') }}</textarea>
                    <p class="text-[10px] text-stone-400">Sebutkan preferensi tingkat kemanisan atau alergi makanan jika ada.</p>
                </div>
            </div>

            <!-- Payment Method Card -->
            <div class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-sm space-y-4">
                <div class="flex items-center space-x-3 pb-3 border-b border-stone-100">
                    <div class="w-8 h-8 rounded-xl bg-coffee-50 text-coffee-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-espresso">3. Metode Pembayaran</h3>
                        <p class="text-[11px] text-stone-400">Pilih metode pembayaran yang paling nyaman.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="payment_method" value="qris" class="peer sr-only" {{ old('payment_method', 'qris') === 'qris' ? 'checked' : '' }}>
                        <div class="p-3.5 rounded-2xl border-2 border-stone-200 peer-checked:border-coffee-700 peer-checked:bg-coffee-50/50 transition text-center space-y-1">
                            <i class="fa-solid fa-qrcode text-lg text-coffee-700"></i>
                            <span class="block text-xs font-bold text-espresso">QRIS Instant</span>
                            <span class="block text-[10px] text-stone-400">Gopay / OVO / Dana / BCA</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="payment_method" value="transfer" class="peer sr-only" {{ old('payment_method') === 'transfer' ? 'checked' : '' }}>
                        <div class="p-3.5 rounded-2xl border-2 border-stone-200 peer-checked:border-coffee-700 peer-checked:bg-coffee-50/50 transition text-center space-y-1">
                            <i class="fa-solid fa-building-columns text-lg text-coffee-700"></i>
                            <span class="block text-xs font-bold text-espresso">Transfer Bank</span>
                            <span class="block text-[10px] text-stone-400">BCA / Mandiri / BNI</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="payment_method" value="cash" class="peer sr-only" {{ old('payment_method') === 'cash' ? 'checked' : '' }}>
                        <div class="p-3.5 rounded-2xl border-2 border-stone-200 peer-checked:border-coffee-700 peer-checked:bg-coffee-50/50 transition text-center space-y-1">
                            <i class="fa-solid fa-money-bill-wave text-lg text-coffee-700"></i>
                            <span class="block text-xs font-bold text-espresso">Bayar di Kasir</span>
                            <span class="block text-[10px] text-stone-400">Tunai saat ambil pesanan</span>
                        </div>
                    </label>
                </div>
            </div>

        </div>

        <!-- Order Review & Summary (1 col) -->
        <div class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-sm space-y-6 sticky top-28">
            <h3 class="font-bold text-base text-espresso font-serif pb-3 border-b border-stone-100">
                Rincian Pesanan
            </h3>

            <!-- Cart Items Summary List -->
            <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                @foreach($itemsWithDetails as $item)
                    <div class="flex items-center justify-between text-xs py-1.5 border-b border-stone-50">
                        <div class="space-y-0.5 max-w-[160px]">
                            <span class="font-bold text-espresso block truncate">{{ $item['product']->name }}</span>
                            <span class="text-[10px] text-stone-400">
                                {{ $item['quantity'] }}x @ Rp {{ number_format($item['product']->selling_price, 0, ',', '.') }}
                            </span>
                        </div>
                        <span class="font-bold text-coffee-800 font-serif">
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Calculation breakdown -->
            <div class="space-y-2 text-xs pt-2 border-t border-stone-100">
                <div class="flex justify-between text-stone-600">
                    <span>Subtotal Menu</span>
                    <span class="font-semibold text-espresso">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-stone-600">
                    <span>Layanan & Pengemasan</span>
                    <span class="text-emerald-700 font-semibold">Gratis</span>
                </div>
                <div class="flex justify-between items-baseline pt-2 border-t border-stone-100">
                    <span class="font-bold text-sm text-espresso">Total Akhir</span>
                    <span class="text-xl font-bold text-coffee-800 font-serif">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="space-y-3 pt-2">
                <button type="submit" class="w-full py-4 bg-espresso hover:bg-coffee-800 text-amber-300 font-bold text-xs uppercase tracking-wider rounded-2xl shadow-xl shadow-coffee-950/20 transition duration-300 transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                    <span>Konfirmasi & Buat Pesanan</span>
                </button>

                <p class="text-[11px] text-stone-400 text-center leading-relaxed">
                    Dengan menekan tombol di atas, pesanan Anda akan langsung diteruskan ke mesin kasir dan barista Kopi Senja.
                </p>
            </div>
        </div>

    </form>

</div>
@endsection
