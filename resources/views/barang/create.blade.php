@include('partials.navigation')

<style>
    body {
        margin: 0;
        background: #f1f5f9;
        font-family: Arial, sans-serif;
    }

    .form-container {
        max-width: 900px;
        margin: 50px auto;
        background: white;
        padding: 35px;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    h1 {
        color: #0f172a;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 8px;
        color: #0f172a;
    }

    input,
    select {
        width: 100%;
        box-sizing: border-box;
        padding: 13px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 16px;
    }

    input:focus,
    select:focus {
        outline: none;
        border-color: #2563eb;
    }

    .error {
        color: #dc2626;
        font-size: 14px;
        margin-top: 5px;
    }

    .buttons {
        display: flex;
        gap: 10px;
        margin-top: 25px;
    }

    .btn {
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        color: white;
        text-decoration: none;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-primary {
        background: #2563eb;
    }

    .btn-secondary {
        background: #64748b;
    }

    .btn:hover {
        opacity: 0.9;
    }
</style>

<div class="form-container">

    <h1>Tambah Barang</h1>

    @if ($errors->any())
        <div style="background:#fee2e2; padding:15px; border-radius:8px; margin-bottom:20px;">
            @foreach ($errors->all() as $error)
                <div class="error">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('barang.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Kode Barang</label>
            <input
                type="text"
                name="kode_barang"
                value="{{ old('kode_barang') }}"
                placeholder="Contoh: BRG002"
                required
            >
        </div>

        <div class="form-group">
            <label>Nama Barang</label>
            <input
                type="text"
                name="nama_barang"
                value="{{ old('nama_barang') }}"
                placeholder="Contoh: Printer"
                required
            >
        </div>

        <div class="form-group">
            <label>Kategori</label>

            <select name="category_id" required>
                <option value="">-- Pilih Kategori --</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input
                type="number"
                name="stok"
                value="{{ old('stok', 0) }}"
                min="0"
                required
            >
        </div>

        <div class="form-group">
            <label>Satuan</label>

            <select name="satuan" required>
                <option value="">-- Pilih Satuan --</option>
                <option value="Unit" {{ old('satuan') == 'Unit' ? 'selected' : '' }}>Unit</option>
                <option value="Buah" {{ old('satuan') == 'Buah' ? 'selected' : '' }}>Buah</option>
                <option value="Pcs" {{ old('satuan') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                <option value="Box" {{ old('satuan') == 'Box' ? 'selected' : '' }}>Box</option>
                <option value="Set" {{ old('satuan') == 'Set' ? 'selected' : '' }}>Set</option>
            </select>
        </div>

        <div class="form-group">
            <label>Lokasi</label>
            <input
                type="text"
                name="lokasi"
                value="{{ old('lokasi') }}"
                placeholder="Contoh: Ruang Lab"
                required
            >
        </div>

        <div class="form-group">
            <label>Kondisi</label>

            <select name="kondisi" required>
                <option value="">-- Pilih Kondisi --</option>
                <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
            </select>
        </div>

        <div class="buttons">
            <button type="submit" class="btn btn-primary">
                Simpan Barang
            </button>

            <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>

    </form>

</div>