@extends('layouts.app')

@section('title', 'Kasir POS - Buat Transaksi Penjualan Kopi')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-coffee-950 font-serif">Kasir POS - Penjualan Kopi (Stock-Out)</h1>
            <p class="text-xs text-stone-500 mt-1">Pemrosesan pesanan pelanggan. Stok produk akan dikurangi otomatis secara atomik.</p>
        </div>
        <a href="{{ route('sales.index') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900">&larr; Kembali</a>
    </div>

    <form method="POST" action="{{ route('sales.store') }}" id="salesForm" class="space-y-6">
        @csrf

        <!-- Customer & Payment Info -->
        <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-stone-400">1. Data Pelanggan & Pembayaran</h2>

            <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
                <div>
                    <label for="customer_name" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Nama Pelanggan</label>
                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', 'Pelanggan Walk-In') }}"
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition"
                           placeholder="Nama Pembeli">
                </div>

                <div>
                    <label for="customer_phone" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">No. HP / WhatsApp</label>
                    <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}"
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition"
                           placeholder="0812-xxxx-xxxx">
                </div>

                <div>
                    <label for="order_type" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Tipe Pesanan *</label>
                    <select name="order_type" id="order_type" required
                            class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                        <option value="pos" {{ old('order_type') === 'pos' ? 'selected' : '' }}>Kasir Walk-in</option>
                        <option value="dine_in" {{ old('order_type') === 'dine_in' ? 'selected' : '' }}>Dine In (Makan di Tempat)</option>
                        <option value="take_away" {{ old('order_type') === 'take_away' ? 'selected' : '' }}>Take Away (Bungkus)</option>
                    </select>
                </div>

                <div>
                    <label for="payment_method" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Metode Bayar *</label>
                    <select name="payment_method" id="payment_method" required
                            class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                        <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                        <option value="qris" {{ old('payment_method') === 'qris' ? 'selected' : '' }}>QRIS / E-Wallet</option>
                        <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    </select>
                </div>

                <div>
                    <label for="sold_at" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Waktu Transaksi *</label>
                    <input type="datetime-local" name="sold_at" id="sold_at" value="{{ old('sold_at', date('Y-m-d\TH:i')) }}" required
                           class="w-full px-4 py-2.5 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition">
                </div>
            </div>

            <div>
                <label for="notes" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Catatan Pesanan Khusus</label>
                <input type="text" name="notes" id="notes" value="{{ old('notes') }}"
                       class="w-full px-4 py-2 text-sm bg-stone-50 border border-stone-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600 transition"
                       placeholder="Misal: Gilingan medium fine untuk V60, less sugar">
            </div>
        </div>

        <!-- Items Table (PPT Slide 13: Add multiple items per transaction) -->
        <div class="bg-white p-6 rounded-3xl border border-stone-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-stone-400">2. Produk Kopi yang Dipesan</h2>
                    <p class="text-[11px] text-stone-500">Pilih produk dan tentukan jumlah. Sistem akan menolak jika melebihi stok yang tersedia.</p>
                </div>
                <button type="button" id="addRowBtn" class="px-3.5 py-1.5 bg-coffee-100 hover:bg-coffee-200 text-coffee-800 text-xs font-semibold rounded-xl transition inline-flex items-center space-x-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Tambah Menu Kopi</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs" id="itemsTable">
                    <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider border-b border-stone-100">
                        <tr>
                            <th class="py-2.5 px-4 w-5/12">Pilih Produk Kopi</th>
                            <th class="py-2.5 px-4 w-2/12 text-center">Stok Ada</th>
                            <th class="py-2.5 px-4 w-2/12 text-center">Jumlah Beli</th>
                            <th class="py-2.5 px-4 w-2/12 text-right">Harga Jual</th>
                            <th class="py-2.5 px-4 w-2/12 text-right">Subtotal</th>
                            <th class="py-2.5 px-2 text-center w-12">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100" id="itemsContainer">
                        <!-- Dynamic sales item rows -->
                    </tbody>
                    <tfoot class="bg-stone-50/80 font-bold border-t border-stone-200">
                        <tr>
                            <td colspan="4" class="py-3 px-4 text-right uppercase tracking-wider text-stone-600">Total Pembayaran:</td>
                            <td class="py-3 px-4 text-right text-lg text-emerald-700 font-bold" id="grandTotalDisplay">Rp 0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('sales.index') }}" class="px-5 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-semibold rounded-xl transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold rounded-xl shadow-md shadow-emerald-900/10 transition flex items-center space-x-2">
                <i class="fa-solid fa-cash-register text-xs"></i>
                <span>Selesaikan Transaksi & Kurangi Stok</span>
            </button>
        </div>
    </form>
</div>

<script>
    const productsData = @json($products);
    let rowCount = 0;

    function addRow(selectedProductId = null, qty = 1) {
        rowCount++;
        const container = document.getElementById('itemsContainer');
        const tr = document.createElement('tr');
        tr.id = `row-${rowCount}`;
        tr.className = 'hover:bg-stone-50/50 transition';

        let optionsHtml = '<option value="">-- Pilih Menu Kopi --</option>';
        productsData.forEach(p => {
            const isSelected = selectedProductId && selectedProductId == p.id ? 'selected' : '';
            optionsHtml += `<option value="${p.id}" data-price="${p.selling_price}" data-stock="${p.stock}" ${isSelected}>${p.name} (${p.sku}) - Rp ${Number(p.selling_price).toLocaleString('id-ID')}</option>`;
        });

        tr.innerHTML = `
            <td class="py-2.5 px-4">
                <select name="items[${rowCount}][product_id]" class="product-select w-full px-3 py-2 text-xs bg-white border border-stone-200 rounded-xl focus:ring-2 focus:ring-coffee-500/20 focus:border-coffee-600" required onchange="onProductSelect(this, ${rowCount})">
                    ${optionsHtml}
                </select>
            </td>
            <td class="py-2.5 px-4 text-center font-bold text-stone-700" id="stockDisplay-${rowCount}">
                -
            </td>
            <td class="py-2.5 px-4">
                <input type="number" name="items[${rowCount}][quantity]" value="${qty}" min="1" class="qty-input w-full px-3 py-2 text-xs bg-white border border-stone-200 rounded-xl text-center focus:ring-2 focus:ring-coffee-500/20" required oninput="calcRow(${rowCount})">
            </td>
            <td class="py-2.5 px-4 text-right font-medium text-stone-600" id="priceDisplay-${rowCount}">
                Rp 0
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
            const selectElem = tr.querySelector('.product-select');
            onProductSelect(selectElem, rowCount);
        }
    }

    function onProductSelect(selectElem, rowId) {
        const option = selectElem.options[selectElem.selectedIndex];
        const price = parseFloat(option.getAttribute('data-price')) || 0;
        const stock = parseInt(option.getAttribute('data-stock')) || 0;

        document.getElementById(`stockDisplay-${rowId}`).innerHTML = `<span class="px-2 py-0.5 rounded-full ${stock <= 5 ? 'bg-red-100 text-red-800' : 'bg-emerald-50 text-emerald-800'} text-xs font-bold">${stock} Unit</span>`;
        document.getElementById(`priceDisplay-${rowId}`).innerText = 'Rp ' + price.toLocaleString('id-ID');

        const qtyInput = document.querySelector(`#row-${rowId} .qty-input`);
        qtyInput.max = stock;

        calcRow(rowId);
    }

    function calcRow(rowId) {
        const row = document.getElementById(`row-${rowId}`);
        if (!row) return;

        const select = row.querySelector('.product-select');
        const option = select.options[select.selectedIndex];
        const price = parseFloat(option?.getAttribute('data-price')) || 0;
        const stock = parseInt(option?.getAttribute('data-stock')) || 0;
        const qty = parseInt(row.querySelector('.qty-input').value) || 0;

        if (stock > 0 && qty > stock) {
            row.querySelector('.qty-input').classList.add('border-red-500', 'bg-red-50');
            row.querySelector('.subtotal-display').innerHTML = `<span class="text-red-600 text-[11px]">Melebihi Stok (${stock})</span>`;
        } else {
            row.querySelector('.qty-input').classList.remove('border-red-500', 'bg-red-50');
            const subtotal = qty * price;
            row.querySelector('.subtotal-display').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        }

        calcGrandTotal();
    }

    function removeRow(rowId) {
        const container = document.getElementById('itemsContainer');
        if (container.children.length <= 1) {
            alert('Transaksi penjualan minimal harus memiliki 1 item pesanan.');
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
            const select = r.querySelector('.product-select');
            const option = select?.options[select.selectedIndex];
            const price = parseFloat(option?.getAttribute('data-price')) || 0;
            const qty = parseInt(r.querySelector('.qty-input')?.value) || 0;
            grandTotal += (qty * price);
        });

        document.getElementById('grandTotalDisplay').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    // Initialize with 1 default row
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('addRowBtn').addEventListener('click', () => addRow());
        if (productsData.length > 0) {
            addRow(productsData[0].id, 1);
        } else {
            addRow();
        }
    });
</script>
@endsection
