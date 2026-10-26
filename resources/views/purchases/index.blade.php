@extends('layouts.app')

@section('title', 'Daftar Pembelian (Stock-In)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 text-xs font-semibold text-coffee-700 bg-coffee-100 px-3 py-1 rounded-full mb-1">
                <i class="fa-solid fa-truck-ramp-box"></i>
                <span>Pengadaan & Stok Masuk</span>
            </div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">Riwayat Pembelian (Stock-In)</h1>
            <p class="text-xs text-stone-500 mt-1">Pencatatan pengadaan biji kopi mentah (green beans) dan roast beans dari perkebunan/supplier</p>
        </div>

        <a href="{{ route('purchases.create') }}" class="px-4 py-2.5 bg-coffee-800 hover:bg-coffee-900 text-white text-xs font-semibold rounded-xl shadow-sm transition inline-flex items-center space-x-2">
            <i class="fa-solid fa-plus text-[10px]"></i>
            <span>Tambah Pembelian (Stock-In)</span>
        </a>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white p-4 rounded-2xl border border-stone-200 shadow-sm">
        <form method="GET" action="{{ route('purchases.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            
            <div class="sm:col-span-4 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-stone-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. PO atau nama supplier..."
                       class="w-full pl-9 pr-4 py-2 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
            </div>

            <div class="sm:col-span-3">
                <select name="supplier_id" class="w-full px-3 py-2 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                    <option value="">Semua Pemasok</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ $supplierId == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <select name="status" class="w-full px-3 py-2 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                    <option value="">Semua Status</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="sm:col-span-3 flex items-center gap-2">
                <button type="submit" class="w-full py-2 bg-stone-800 hover:bg-stone-900 text-white text-xs font-semibold rounded-xl transition">
                    Filter
                </button>
                @if($search || $supplierId || $status || $startDate || $endDate)
                    <a href="{{ route('purchases.index') }}" class="p-2 text-stone-500 hover:text-stone-800 text-xs" title="Reset">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Purchases Table -->
    <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-100">
                    <tr>
                        <th class="py-3 px-6">Nomor Transaksi</th>
                        <th class="py-3 px-6">Tanggal Pembelian</th>
                        <th class="py-3 px-6">Pemasok / Kebun</th>
                        <th class="py-3 px-6">Dicatat Oleh</th>
                        <th class="py-3 px-6 text-center">Jumlah Item</th>
                        <th class="py-3 px-6 text-right">Total Transaksi</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-700">
                    @forelse($purchases as $purchase)
                        <tr class="hover:bg-stone-50/50 transition">
                            <td class="py-3.5 px-6 font-mono font-bold text-coffee-900">
                                <a href="{{ route('purchases.show', $purchase) }}" class="hover:underline">
                                    {{ $purchase->purchase_number }}
                                </a>
                            </td>
                            <td class="py-3.5 px-6 text-stone-600">
                                {{ $purchase->purchased_at->format('d M Y H:i') }}
                            </td>
                            <td class="py-3.5 px-6 font-medium text-stone-900">
                                {{ $purchase->supplier->name ?? '-' }}
                            </td>
                            <td class="py-3.5 px-6 text-stone-500">
                                {{ $purchase->user->name ?? 'User' }}
                            </td>
                            <td class="py-3.5 px-6 text-center font-medium">
                                {{ $purchase->items->count() }} macam
                            </td>
                            <td class="py-3.5 px-6 text-right font-bold text-coffee-950">
                                Rp {{ number_format($purchase->total, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-6 text-center">
                                @if($purchase->status === 'completed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                                        <i class="fa-solid fa-check mr-1 text-[9px]"></i> Selesai (Stok Masuk)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                        <i class="fa-solid fa-ban mr-1 text-[9px]"></i> Dibatalkan
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <a href="{{ route('purchases.show', $purchase) }}" class="px-3 py-1 bg-stone-100 hover:bg-stone-200 text-stone-700 font-semibold rounded-lg text-xs transition inline-flex items-center space-x-1">
                                    <i class="fa-regular fa-file-lines"></i>
                                    <span>Detail</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-stone-400">
                                Belum ada riwayat transaksi pembelian (Stock-In)
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchases->hasPages())
            <div class="p-4 border-t border-stone-100">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
