@extends('layouts.app')

@section('title', 'Manajemen Pesanan Masuk Pelanggan — KOPI SENJA')

@section('content')
<div class="space-y-8">

    <!-- Header Breadcrumbs -->
    <div class="flex items-center space-x-2 text-xs text-stone-500">
        <a href="{{ route('home') }}" class="hover:text-coffee-700">Beranda</a>
        <span>/</span>
        <a href="{{ route('dashboard') }}" class="hover:text-coffee-700">Dashboard</a>
        <span>/</span>
        <span class="text-espresso font-semibold">Pesanan Pelanggan</span>
    </div>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold font-serif text-espresso">Pesanan Pelanggan (Online Ordering)</h1>
            <p class="text-xs text-stone-500 mt-1">Kelola dan perbarui status pesanan Dine In & Take Away pelanggan kedai kopi.</p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('sales.create') }}" class="px-5 py-2.5 bg-espresso hover:bg-coffee-800 text-amber-300 font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition inline-flex items-center space-x-2">
                <i class="fa-solid fa-cash-register"></i>
                <span>Kasir POS Walk-in</span>
            </a>
        </div>
    </div>

    <!-- Filter Status Tabs & Search -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Status Pills -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('customer-orders.index') }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ empty($status) ? 'bg-coffee-900 text-white shadow-sm' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200' }}">
                Semua ({{ $statusCounts['all'] }})
            </a>
            <a href="{{ route('customer-orders.index', ['status' => 'pending']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'pending' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200' }}">
                Menunggu ({{ $statusCounts['pending'] }})
            </a>
            <a href="{{ route('customer-orders.index', ['status' => 'confirmed']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'confirmed' ? 'bg-sky-600 text-white shadow-sm' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200' }}">
                Dikonfirmasi ({{ $statusCounts['confirmed'] }})
            </a>
            <a href="{{ route('customer-orders.index', ['status' => 'preparing']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'preparing' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200' }}">
                Disiapkan ({{ $statusCounts['preparing'] }})
            </a>
            <a href="{{ route('customer-orders.index', ['status' => 'ready']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'ready' ? 'bg-teal-600 text-white shadow-sm' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200' }}">
                Siap Diambil ({{ $statusCounts['ready'] }})
            </a>
            <a href="{{ route('customer-orders.index', ['status' => 'completed']) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $status === 'completed' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200' }}">
                Selesai ({{ $statusCounts['completed'] }})
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('customer-orders.index') }}" class="relative min-w-[260px]">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-stone-400 text-xs">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nomor pesanan / pemesan..."
                   class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-stone-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 shadow-sm transition">
        </form>
    </div>

    <!-- Orders Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($orders as $order)
            <div class="bg-white rounded-3xl border border-stone-200/80 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                
                <!-- Card Header -->
                <div class="p-5 border-b border-stone-100 bg-stone-50/50 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-xs font-mono text-espresso">{{ $order->sale_number }}</span>
                        
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border
                            {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-800 border-amber-200' : '' }}
                            {{ $order->status === 'confirmed' ? 'bg-sky-100 text-sky-800 border-sky-200' : '' }}
                            {{ $order->status === 'preparing' ? 'bg-indigo-100 text-indigo-800 border-indigo-200 animate-pulse' : '' }}
                            {{ $order->status === 'ready' ? 'bg-teal-100 text-teal-800 border-teal-200' : '' }}
                            {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : '' }}
                            {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800 border-red-200' : '' }}
                        ">
                            {{ $order->status_label }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-[11px] text-stone-500">
                        <span>Pemesan: <strong class="text-stone-800">{{ $order->customer_name }}</strong></span>
                        <span class="font-semibold text-coffee-700 bg-coffee-50 px-2 py-0.5 rounded">{{ $order->order_type_label }}</span>
                    </div>
                </div>

                <!-- Items & Notes -->
                <div class="p-5 space-y-3 flex-grow">
                    <div class="space-y-1.5 text-xs">
                        @foreach($order->items as $item)
                            <div class="flex justify-between">
                                <span class="text-stone-700">{{ $item->product->name ?? 'Menu' }} &times; {{ $item->quantity }}</span>
                                <span class="font-medium text-espresso font-serif">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    @if($order->notes)
                        <div class="p-2.5 bg-stone-50 rounded-xl text-[10px] text-stone-600 border border-stone-100 italic">
                            <span class="font-bold not-italic text-stone-700">Catatan:</span> “{{ $order->notes }}”
                        </div>
                    @endif

                    @if($order->customer_phone)
                        <p class="text-[11px] text-stone-500">
                            <i class="fa-solid fa-phone text-stone-400 mr-1"></i> WA: <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}" target="_blank" class="text-coffee-700 hover:underline font-semibold">{{ $order->customer_phone }}</a>
                        </p>
                    @endif
                </div>

                <!-- Footer: Status Changer Actions & Total -->
                <div class="p-5 border-t border-stone-100 bg-stone-50/30 space-y-3">
                    <div class="flex justify-between items-baseline">
                        <span class="text-[10px] text-stone-400 uppercase font-semibold">Total Tagihan</span>
                        <span class="text-base font-bold font-serif text-coffee-900">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Workflow Update Status Form -->
                    @if($order->status !== 'completed' && $order->status !== 'cancelled')
                        <div class="pt-2 border-t border-stone-100 flex flex-wrap gap-1.5">
                            @if($order->status === 'pending')
                                <form method="POST" action="{{ route('customer-orders.update-status', $order) }}" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="w-full py-1.5 bg-sky-600 hover:bg-sky-700 text-white text-[11px] font-bold rounded-xl shadow-sm transition">
                                        <i class="fa-solid fa-check mr-1"></i> Konfirmasi
                                    </button>
                                </form>
                            @elseif($order->status === 'confirmed')
                                <form method="POST" action="{{ route('customer-orders.update-status', $order) }}" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="preparing">
                                    <button type="submit" class="w-full py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-bold rounded-xl shadow-sm transition">
                                        <i class="fa-solid fa-mug-saucer mr-1"></i> Mulai Disiapkan
                                    </button>
                                </form>
                            @elseif($order->status === 'preparing')
                                <form method="POST" action="{{ route('customer-orders.update-status', $order) }}" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="ready">
                                    <button type="submit" class="w-full py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-[11px] font-bold rounded-xl shadow-sm transition">
                                        <i class="fa-solid fa-bell-concierge mr-1"></i> Siap Diambil
                                    </button>
                                </form>
                            @elseif($order->status === 'ready')
                                <form method="POST" action="{{ route('customer-orders.update-status', $order) }}" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="w-full py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold rounded-xl shadow-sm transition">
                                        <i class="fa-solid fa-circle-check mr-1"></i> Selesaikan Pesanan
                                    </button>
                                </form>
                            @endif

                            @if(auth()->user()->isAdmin())
                                <!-- Cancel Order Button (Khusus Owner / Admin) -->
                                <form method="POST" action="{{ route('customer-orders.update-status', $order) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan otomatis dipulihkan ke etalase.')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="px-2.5 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-[11px] font-semibold rounded-xl border border-red-200 transition" title="Batalkan Pesanan (Khusus Owner)">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        <div class="text-center pt-1">
                            <a href="{{ route('sales.show', $order) }}" class="text-xs text-coffee-700 hover:text-espresso font-semibold">
                                <i class="fa-solid fa-receipt mr-1"></i> Buka Nota Penjualan
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-stone-200/80 shadow-sm space-y-2">
                <i class="fa-solid fa-inbox text-3xl text-stone-300"></i>
                <h3 class="text-base font-bold text-espresso">Tidak Ada Pesanan Ditemukan</h3>
                <p class="text-xs text-stone-400">Tidak ada pesanan pelanggan online yang sesuai dengan filter.</p>
            </div>
        @endforelse
    </div>

    @if($orders->hasPages())
        <div class="pt-4">
            {{ $orders->links() }}
        </div>
    @endif

</div>
@endsection
