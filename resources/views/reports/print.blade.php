<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan & Penjualan Kopi Senja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { font-size: 11px; }
        }
    </style>
</head>
<body class="p-8 text-stone-800 bg-white max-w-4xl mx-auto">
    <div class="no-print mb-6 flex justify-between items-center bg-stone-100 p-4 rounded-xl">
        <span class="text-xs font-semibold">Gunakan tombol di samping untuk mencetak atau simpan sebagai PDF.</span>
        <button onclick="window.print()" class="px-4 py-2 bg-stone-800 text-white rounded-lg text-xs font-semibold hover:bg-stone-900">
            Cetak Dokumen Sekarang
        </button>
    </div>

    <!-- Letterhead -->
    <div class="text-center pb-6 border-b-2 border-stone-800 mb-6">
        <h1 class="text-2xl font-bold uppercase tracking-wider">Kopi Senja Roastery & Cafe</h1>
        <p class="text-xs text-stone-600">Sistem Informasi Manajemen Inventaris & Penjualan Terpadu</p>
        <p class="text-xs text-stone-500">Jl. Roastery Senja No. 1, Jakarta Selatan • Telp: 0812-3456-0001</p>
        <h2 class="text-sm font-bold uppercase tracking-widest mt-4">Rekapitulasi Penjualan & Pengadaan</h2>
        <p class="text-xs text-stone-500 font-mono">Periode: {{ date('d M Y', strtotime($startDate)) }} s/d {{ date('d M Y', strtotime($endDate)) }}</p>
    </div>

    <!-- Summary Box -->
    <div class="grid grid-cols-3 gap-4 mb-6 text-xs">
        <div class="p-3 border rounded-lg bg-stone-50">
            <span class="text-stone-500 block uppercase text-[10px] font-bold">Total Pemasukan Penjualan</span>
            <span class="text-base font-bold text-stone-900">Rp {{ number_format($totalSales, 0, ',', '.') }}</span>
            <span class="block text-[10px] text-stone-500">{{ $sales->count() }} Transaksi Selesai</span>
        </div>
        <div class="p-3 border rounded-lg bg-stone-50">
            <span class="text-stone-500 block uppercase text-[10px] font-bold">Total Pengadaan Stok</span>
            <span class="text-base font-bold text-stone-900">Rp {{ number_format($totalPurchases, 0, ',', '.') }}</span>
            <span class="block text-[10px] text-stone-500">{{ $purchases->count() }} Transaksi Masuk</span>
        </div>
        <div class="p-3 border rounded-lg bg-stone-50">
            <span class="text-stone-500 block uppercase text-[10px] font-bold">Selisih Bersih (Laba Kotor)</span>
            <span class="text-base font-bold text-stone-900">Rp {{ number_format($netProfit, 0, ',', '.') }}</span>
            <span class="block text-[10px] text-stone-500">Arus Kas Bersih</span>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="mb-6">
        <h3 class="text-xs font-bold uppercase tracking-wider mb-2">1. Rincian Penjualan (Kasir & Online Order)</h3>
        <table class="w-full text-left text-xs border border-stone-200">
            <thead class="bg-stone-100 font-bold">
                <tr>
                    <th class="p-2 border">No. Faktur</th>
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border">Pelanggan</th>
                    <th class="p-2 border">Metode</th>
                    <th class="p-2 border text-right">Total Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $s)
                    <tr>
                        <td class="p-2 border font-mono">{{ $s->sale_number }}</td>
                        <td class="p-2 border">{{ $s->sold_at->format('d/m/Y H:i') }}</td>
                        <td class="p-2 border">{{ $s->customer_name }}</td>
                        <td class="p-2 border uppercase font-mono">{{ $s->payment_method }}</td>
                        <td class="p-2 border text-right font-bold">Rp {{ number_format($s->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center border text-stone-400">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="font-bold bg-stone-50">
                <tr>
                    <td colspan="4" class="p-2 border text-right uppercase">Total Penjualan:</td>
                    <td class="p-2 border text-right">Rp {{ number_format($totalSales, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Signature -->
    <div class="grid grid-cols-2 mt-12 text-xs pt-8 border-t border-stone-200">
        <div>
            <p>Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
            <p>Oleh: {{ auth()->user()->name ?? 'Administrator' }}</p>
        </div>
        <div class="text-center">
            <p>Mengetahui,</p>
            <p class="font-bold uppercase mt-16">Owner / Management Kopi Senja</p>
        </div>
    </div>
</body>
</html>
