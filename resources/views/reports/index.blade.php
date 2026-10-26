@extends('layouts.app')

@section('title', 'Laporan Penjualan & Pengadaan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 text-xs font-semibold text-coffee-700 bg-coffee-100 px-3 py-1 rounded-full mb-1">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Laporan & Ringkasan Keuangan</span>
            </div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">Laporan Penjualan & Pembelian</h1>
            <p class="text-xs text-stone-500 mt-1">Rekapitulasi arus kas masuk (penjualan) dan arus kas keluar (pembelian stok) berdasarkan rentang tanggal</p>
        </div>

        <a href="{{ route('reports.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="px-4 py-2.5 bg-stone-800 hover:bg-stone-900 text-white text-xs font-semibold rounded-xl shadow-sm transition inline-flex items-center space-x-2">
            <i class="fa-solid fa-print text-[10px]"></i>
            <span>Cetak / Ekspor Laporan</span>
        </a>
    </div>

    <!-- Date Range Filter Form (PPT Slide 18: Filter tanggal transaksi & status) -->
    <div class="bg-white p-5 rounded-3xl border border-stone-200 shadow-sm">
        <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            
            <div class="sm:col-span-4">
                <label for="start_date" class="block text-xs font-semibold text-stone-600 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                       class="w-full px-3 py-2 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
            </div>

            <div class="sm:col-span-4">
                <label for="end_date" class="block text-xs font-semibold text-stone-600 mb-1">Tanggal Selesai</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                       class="w-full px-3 py-2 text-xs bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
            </div>

            <div class="sm:col-span-4 flex items-center gap-2">
                <button type="submit" class="w-full py-2 bg-coffee-800 hover:bg-coffee-900 text-white text-xs font-semibold rounded-xl transition">
                    Terapkan Rentang Tanggal
                </button>
            </div>

        </form>
    </div>

    <!-- Aggregate Financial Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Total Penjualan -->
        <div class="bg-white p-5 rounded-3xl border border-stone-200 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Total Penjualan (Pemasukan)</span>
            <div class="text-2xl font-bold text-emerald-700 mt-2">
                Rp {{ number_format($totalSales, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-stone-500 mt-1">
                {{ $salesCount }} transaksi selesai
            </div>
        </div>

        <!-- Total Pembelian -->
        <div class="bg-white p-5 rounded-3xl border border-stone-200 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Total Pengadaan (Pengeluaran)</span>
            <div class="text-2xl font-bold text-blue-700 mt-2">
                Rp {{ number_format($totalPurchases, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-stone-500 mt-1">
                {{ $purchasesCount }} transaksi pengadaan stok
            </div>
        </div>

        <!-- Selisih Arus Kas -->
        <div class="bg-white p-5 rounded-3xl border border-stone-200 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Selisih Bersih (Laba Kotor Kas)</span>
            <div class="text-2xl font-bold {{ $grossProfit >= 0 ? 'text-coffee-900' : 'text-red-600' }} mt-2">
                Rp {{ number_format($grossProfit, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-stone-500 mt-1">
                Penjualan dikurangi biaya belanja stok
            </div>
        </div>
    </div>

    <!-- Sales Transactions in Date Range -->
    <div class="bg-white rounded-3xl border border-stone-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-stone-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-coffee-950 font-serif">Rincian Penjualan pada Periode Terpilih</h3>
            <span class="text-xs text-stone-500">{{ $salesCount }} Transaksi</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-100">
                    <tr>
                        <th class="py-3 px-6">No. Invoice</th>
                        <th class="py-3 px-6">Tanggal</th>
                        <th class="py-3 px-6">Pelanggan</th>
                        <th class="py-3 px-6">Metode</th>
                        <th class="py-3 px-6 text-right">Total Transaksi</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-700">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-stone-50/50 transition">
                            <td class="py-3.5 px-6 font-mono font-bold text-coffee-900">{{ $sale->sale_number }}</td>
                            <td class="py-3.5 px-6 text-stone-600">{{ $sale->sold_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3.5 px-6 font-semibold">{{ $sale->customer_name }}</td>
                            <td class="py-3.5 px-6 uppercase font-mono">{{ $sale->payment_method }}</td>
                            <td class="py-3.5 px-6 text-right font-bold text-emerald-700">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-6 text-right">
                                <a href="{{ route('sales.show', $sale) }}" class="text-coffee-700 hover:text-coffee-900 font-semibold underline">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-stone-400">Tidak ada transaksi penjualan pada rentang tanggal ini</td>
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
