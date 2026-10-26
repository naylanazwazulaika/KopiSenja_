@extends('layouts.app')

@section('title', 'Buat Transaksi Pembelian (Stock-In)')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">Tambah Pembelian (Stock-In)</h1>
            <p class="text-xs text-stone-500 mt-1">Penerimaan stok biji kopi dari pemasok (Stok produk otomatis bertambah secara atomik)</p>
        </div>
        <a href="{{ route('purchases.index') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900">&larr; Kembali</a>
    </div>

    <form method="POST" action="{{ route('purchases.store') }}" id="purchaseForm" class="space-y-6">
        @csrf

        <!-- Header Info -->
        <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-stone-400">1. Data Pemasok & Informasi Transaksi</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="supplier_id" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Pemasok / Kebun Kopi *</label>
                    <select name="supplier_id" id="supplier_id" required
                            class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                        <option value="">Pilih Pemasok</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="purchased_at" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Tanggal & Waktu Masuk *</label>
                    <input type="datetime-local" name="purchased_at" id="purchased_at" value="{{ old('purchased_at', date('Y-m-d\TH:i')) }}" required
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                </div>

                <div>
                    <label for="notes" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Catatan / No. Surat Jalan</label>
                    <input type="text" name="notes" id="notes" value="{{ old('notes') }}"
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition"
                           placeholder="Misal: Panen pertama Oktober 2026">
                </div>
            </div>
        </div>

        <!-- Items Table (PPT Slide 13: Add multiple items per transaction) -->
        <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-stone-400">2. Rincian Item Produk Kopi Masuk</h2>
                    <p class="text-[11px] text-stone-500">Pilih satu atau lebih produk yang dibeli dari supplier</p>
                </div>
                <button type="button" id="addRowBtn" class="px-3.5 py-1.5 bg-coffee-100 hover:bg-coffee-200 text-coffee-800 text-xs font-semibold rounded-xl transition inline-flex items-center space-x-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Tambah Baris Item</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs" id="itemsTable">
                    <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-100">
                        <tr>
                            <th class="py-2.5 px-4 w-5/12">Produk Kopi</th>
                            <th class="py-2.5 px-4 w-2/12 text-center">Kuantitas</th>
                            <th class="py-2.5 px-4 w-3/12 text-right">Harga Beli Satuan (Rp)</th>
                            <th class="py-2.5 px-4 w-2/12 text-right">Subtotal</th>
                            <th class="py-2.5 px-2 text-center w-12">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100" id="itemsContainer">
                        <!-- Dynamic rows will be inserted here -->
                    </tbody>
                    <tfoot class="bg-stone-50/80 font-bold border-t border-stone-200">
                        <tr>
                            <td colspan="3" class="py-3 px-4 text-right uppercase tracking-wider text-stone-600">Total Transaksi Pembelian:</td>
                            <td class="py-3 px-4 text-right text-base text-coffee-950 font-bold" id="grandTotalDisplay">Rp 0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('purchases.index') }}" class="px-5 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-semibold rounded-xl transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 bg-coffee-800 hover:bg-coffee-900 text-white text-xs font-semibold rounded-xl shadow-md shadow-coffee-900/10 transition flex items-center space-x-2">
                <i class="fa-solid fa-check text-xs"></i>
                <span>Simpan Transaksi Pembelian & Tambah Stok</span>
            </button>
        </div>
    </form>
</div>

<script>
    const productsData = @json($products);
    let rowCount = 0;

    function addRow(selectedProductId = null, qty = 1, unitCost = 0) {
        rowCount++;
        const container = document.getElementById('itemsContainer');
        const tr = document.createElement('tr');
        tr.id = `row-${rowCount}`;
        tr.className = 'hover:bg-stone-50/50 transition';

        let optionsHtml = '<option value="">-- Pilih Produk Kopi --</option>';
        productsData.forEach(p => {
            const isSelected = selectedProductId && selectedProductId == p.id ? 'selected' : '';
            optionsHtml += `<option value="${p.id}" data-cost="${p.purchase_price}" data-stock="${p.stock}" ${isSelected}>${p.sku} - ${p.name} (Stok Saat Ini: ${p.stock})</option>`;
        });

        tr.innerHTML = `
            <td class="py-2.5 px-4">
                <select name="items[${rowCount}][product_id]" class="product-select w-full px-3 py-2 text-xs bg-white border border-stone-200 rounded-xl focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600" required onchange="onProductSelect(this, ${rowCount})">
                    ${optionsHtml}
                </select>
            </td>
            <td class="py-2.5 px-4">
                <input type="number" name="items[${rowCount}][quantity]" value="${qty}" min="1" class="qty-input w-full px-3 py-2 text-xs bg-white border border-stone-200 rounded-xl text-center focus:ring-2 focus:ring-coffee-500/20" required oninput="calcRow(${rowCount})">
            </td>
            <td class="py-2.5 px-4 text-right">
                <input type="number" name="items[${rowCount}][unit_cost]" value="${unitCost}" min="0" step="100" class="cost-input w-full px-3 py-2 text-xs bg-white border border-stone-200 rounded-xl text-right focus:ring-2 focus:ring-coffee-500/20" required oninput="calcRow(${rowCount})">
            </td>
            <td class="py-2.5 px-4 text-right font-bold text-coffee-950 subtotal-display" id="subtotal-${rowCount}">
                Rp 0
            </td>
            <td class="py-2.5 px-2 text-center">
                <button type="button" onclick="removeRow(${rowCount})" class="p-1.5 text-stone-400 hover:text-red-600 transition" title="Hapus Baris">
                    <i class="fa-regular fa-trash-can"></i>
                </button>
            </td>
        `;

        container.appendChild(tr);
        if (selectedProductId) {
            calcRow(rowCount);
        }
    }

    function onProductSelect(selectElem, rowId) {
        const option = selectElem.options[selectElem.selectedIndex];
        const cost = option.getAttribute('data-cost') || 0;
        const row = document.getElementById(`row-${rowId}`);
        row.querySelector('.cost-input').value = cost;
        calcRow(rowId);
    }

    function calcRow(rowId) {
        const row = document.getElementById(`row-${rowId}`);
        if (!row) return;

        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
        const subtotal = qty * cost;

        row.querySelector('.subtotal-display').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        calcGrandTotal();
    }

    function removeRow(rowId) {
        const container = document.getElementById('itemsContainer');
        if (container.children.length <= 1) {
            alert('Transaksi pembelian minimal harus memiliki 1 item produk.');
            return;
        }
        const row = document.getElementById(`row-${rowId}`);
        if (row) row.remove();
        calcGrandTotal();
    }

    function calcGrandTotal() {
        let grandTotal = 0;
        const rows = document.querySelectorAll('#itemsContainer tr');
        rows.forEach(r => {
            const qty = parseFloat(r.querySelector('.qty-input')?.value) || 0;
            const cost = parseFloat(r.querySelector('.cost-input')?.value) || 0;
            grandTotal += (qty * cost);
        });

        document.getElementById('grandTotalDisplay').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    // Initialize with 1 default row
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('addRowBtn').addEventListener('click', () => addRow());
        addRow();
    });
</script>
@endsection
