@extends('layouts.app')

@section('title', 'Daftar Penjualan (Stock-Out)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 text-xs font-semibold text-coffee-700 bg-coffee-100 px-3 py-1 rounded-full mb-1">
                <i class="fa-solid fa-cash-register"></i>
                <span>Kasir POS & Transaksi Penjualan</span>
            </div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">Riwayat Transaksi Penjualan</h1>
            <p class="text-xs text-stone-500 mt-1">Daftar struk penjualan coffee shop, pemesanan roast beans, dan pesanan online pelanggan</p>
        </div>

        <a href="{{ route('sales.create') }}" class="px-4 py-2.5 bg-coffee-800 hover:bg-coffee-900 text-white text-xs font-semibold rounded-xl shadow-sm transition inline-flex items-center space-x-2">
            <i class="fa-solid fa-plus text-[10px]"></i>
            <span>Transaksi Kasir Baru (POS)</span>
        </a>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white p-4 rounded-2xl border border-stone-200 shadow-sm">
        <form method="GET" action="{{ route('sales.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            
            <div class="sm:col-span-5 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-stone-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. Struk atau nama pelanggan..."
                       class="w-full pl-9 pr-4 py-2 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
            </div>

            <div class="sm:col-span-3">
                <select name="status" class="w-full px-3 py-2 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                    <option value="">Semua Status</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                </select>
            </div>

            <div class="sm:col-span-4 flex items-center gap-2">
                <button type="submit" class="w-full py-2 bg-stone-800 hover:bg-stone-900 text-white text-xs font-semibold rounded-xl transition">
                    Filter
                </button>
                @if($search || $status || $startDate || $endDate)
                    <a href="{{ route('sales.index') }}" class="p-2 text-stone-500 hover:text-stone-800 text-xs" title="Reset">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Sales Table -->
    <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-100">
                    <tr>
                        <th class="py-3 px-6">Nomor Invoice</th>
                        <th class="py-3 px-6">Waktu Penjualan</th>
                        <th class="py-3 px-6">Nama Pembeli</th>
                        <th class="py-3 px-6">Metode Bayar</th>
                        <th class="py-3 px-6 text-center">Jumlah Item</th>
                        <th class="py-3 px-6 text-right">Total Tagihan</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-700">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-stone-50/50 transition">
                            <td class="py-3.5 px-6 font-mono font-bold text-coffee-900">
                                <a href="{{ route('sales.show', $sale) }}" class="hover:underline">
                                    {{ $sale->sale_number }}
                                </a>
                            </td>
                            <td class="py-3.5 px-6 text-stone-600">
                                {{ $sale->sold_at->format('d M Y H:i') }}
                            </td>
                            <td class="py-3.5 px-6 font-semibold text-stone-900">
                                {{ $sale->customer_name }}
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="uppercase font-mono text-[11px] font-bold px-2 py-0.5 rounded bg-stone-100 text-stone-700">
                                    {{ $sale->payment_method }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 text-center font-medium">
                                {{ $sale->items->count() }} jenis
                            </td>
                            <td class="py-3.5 px-6 text-right font-bold text-stone-900">
                                Rp {{ number_format($sale->total, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-6 text-center">
                                @if($sale->status === 'completed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-check mr-1 text-[9px]"></i> Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                        <i class="fa-solid fa-ban mr-1 text-[9px]"></i> Dibatalkan
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <a href="{{ route('sales.show', $sale) }}" class="px-3 py-1 bg-stone-100 hover:bg-stone-200 text-stone-700 font-semibold rounded-lg text-xs transition inline-flex items-center space-x-1">
                                    <i class="fa-solid fa-receipt"></i>
                                    <span>Struk</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-stone-400">
                                Belum ada riwayat transaksi penjualan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
            <div class="p-4 border-t border-stone-100">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
