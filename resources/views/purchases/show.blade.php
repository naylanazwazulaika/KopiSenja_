@extends('layouts.app')

@section('title', 'Detail Pembelian: ' . $purchase->purchase_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="inline-flex items-center space-x-2 text-xs font-semibold text-coffee-700 bg-coffee-100 px-3 py-1 rounded-full mb-1">
                <i class="fa-solid fa-file-invoice"></i>
                <span>Surat Penerimaan Barang (Stock-In)</span>
            </div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">{{ $purchase->purchase_number }}</h1>
            <p class="text-xs text-stone-500 mt-0.5">Dicatat pada {{ $purchase->purchased_at->format('d F Y, H:i') }} WIB</p>
        </div>

        <div class="flex items-center space-x-2">
            @if(auth()->user()->isAdmin() && $purchase->status === 'completed')
                <form method="POST" action="{{ route('purchases.cancel', $purchase) }}" onsubmit="return confirm('PERINGATAN: Membatalkan transaksi pembelian ini akan mengurangi kembali stok produk yang sebelumnya ditambahkan. Lanjutkan pembatalan?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 text-xs font-semibold rounded-xl transition inline-flex items-center space-x-1.5">
                        <i class="fa-solid fa-ban"></i>
                        <span>Batalkan Transaksi (Pulihkan Stok)</span>
                    </button>
                </form>
            @endif
            <a href="{{ route('purchases.index') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900">&larr; Kembali</a>
        </div>
    </div>

    <!-- Header Invoice Card -->
    <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div>
            <span class="text-[11px] font-semibold text-stone-400 uppercase tracking-wider block">Pemasok / Kebun</span>
            <div class="text-base font-bold text-stone-900 mt-1">{{ $purchase->supplier->name ?? '-' }}</div>
            <div class="text-xs text-stone-500 mt-0.5">{{ $purchase->supplier->phone ?? '-' }}</div>
            <div class="text-xs text-stone-500">{{ $purchase->supplier->address ?? '-' }}</div>
        </div>

        <div>
            <span class="text-[11px] font-semibold text-stone-400 uppercase tracking-wider block">Penerima / Staf</span>
            <div class="text-base font-bold text-stone-900 mt-1">{{ $purchase->user->name ?? 'User' }}</div>
            <div class="text-xs text-stone-500 mt-0.5">Role: {{ ucfirst($purchase->user->role ?? '-') }}</div>
            <div class="text-xs text-stone-500">{{ $purchase->user->email ?? '-' }}</div>
        </div>

        <div>
            <span class="text-[11px] font-semibold text-stone-400 uppercase tracking-wider block">Status Transaksi</span>
            <div class="mt-1">
                @if($purchase->status === 'completed')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                        <i class="fa-solid fa-check mr-1 text-[10px]"></i> Selesai (Stok Ditambahkan)
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                        <i class="fa-solid fa-ban mr-1 text-[10px]"></i> Transaksi Dibatalkan
                    </span>
                @endif
            </div>
            @if($purchase->notes)
                <div class="text-xs text-stone-600 mt-2 bg-stone-50 p-2 rounded-xl">
                    <strong>Catatan:</strong> {{ $purchase->notes }}
                </div>
            @endif
        </div>
    </div>

    <!-- Items Detail Table -->
    <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-stone-100">
            <h3 class="text-sm font-bold text-coffee-950 font-serif">Rincian Produk Kopi yang Diterima</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-100">
                    <tr>
                        <th class="py-3 px-6">No</th>
                        <th class="py-3 px-6">SKU</th>
                        <th class="py-3 px-6">Nama Produk Kopi</th>
                        <th class="py-3 px-6 text-center">Kuantitas Masuk</th>
                        <th class="py-3 px-6 text-right">Harga Beli Satuan</th>
                        <th class="py-3 px-6 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-700">
                    @foreach($purchase->items as $index => $item)
                        <tr class="hover:bg-stone-50/50 transition">
                            <td class="py-3.5 px-6 font-mono text-stone-400">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-6 font-mono font-bold text-coffee-900">{{ $item->product->sku ?? '-' }}</td>
                            <td class="py-3.5 px-6 font-bold text-stone-900">{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                            <td class="py-3.5 px-6 text-center font-bold text-blue-700">+{{ $item->quantity }}</td>
                            <td class="py-3.5 px-6 text-right font-medium">Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-6 text-right font-bold text-coffee-950">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-stone-50/90 font-bold border-t border-stone-200">
                    <tr>
                        <td colspan="5" class="py-3.5 px-6 text-right uppercase tracking-wider text-stone-600">Total Biaya Pengadaan:</td>
                        <td class="py-3.5 px-6 text-right text-base text-coffee-950 font-bold">
                            Rp {{ number_format($purchase->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
