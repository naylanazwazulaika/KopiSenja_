<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - Sistem Inventaris</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }

        .header {
            background: #111827;
            color: white;
            padding: 20px 40px;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        h1 {
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 11px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 15px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #2563eb;
        }

        .error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            padding: 11px 20px;
            border-radius: 7px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 15px;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .alert {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

   @include('partials.navigation')
    <div class="container">

        <div class="card">

            <h1>Edit Barang</h1>

            @if ($errors->any())
                <div class="alert">
                    <strong>Ada kesalahan:</strong>

                    <ul style="margin-top: 8px; margin-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('barang.update', $barang) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="kode_barang">Kode Barang</label>
                    <input
                        type="text"
                        id="kode_barang"
                        name="kode_barang"
                        value="{{ old('kode_barang', $barang->kode_barang) }}"
                        required
                    >

                    @error('kode_barang')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input
                        type="text"
                        id="nama_barang"
                        name="nama_barang"
                        value="{{ old('nama_barang', $barang->nama_barang) }}"
                        required
                    >

                    @error('nama_barang')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <input
                        type="text"
                        id="kategori"
                        name="kategori"
                        value="{{ old('kategori', $barang->kategori) }}"
                        required
                    >

                    @error('kategori')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input
                        type="number"
                        id="stok"
                        name="stok"
                        value="{{ old('stok', $barang->stok) }}"
                        min="0"
                        required
                    >

                    @error('stok')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="satuan">Satuan</label>

                    <select id="satuan" name="satuan" required>
                        <option value="Unit" {{ old('satuan', $barang->satuan) == 'Unit' ? 'selected' : '' }}>
                            Unit
                        </option>

                        <option value="Buah" {{ old('satuan', $barang->satuan) == 'Buah' ? 'selected' : '' }}>
                            Buah
                        </option>

                        <option value="Pcs" {{ old('satuan', $barang->satuan) == 'Pcs' ? 'selected' : '' }}>
                            Pcs
                        </option>

                        <option value="Set" {{ old('satuan', $barang->satuan) == 'Set' ? 'selected' : '' }}>
                            Set
                        </option>
                    </select>

                    @error('satuan')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="lokasi">Lokasi</label>

                    <input
                        type="text"
                        id="lokasi"
                        name="lokasi"
                        value="{{ old('lokasi', $barang->lokasi) }}"
                        required
                    >

                    @error('lokasi')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="kondisi">Kondisi</label>

                    <select id="kondisi" name="kondisi" required>

                        <option value="Baik" {{ old('kondisi', $barang->kondisi) == 'Baik' ? 'selected' : '' }}>
                            Baik
                        </option>

                        <option value="Rusak Ringan" {{ old('kondisi', $barang->kondisi) == 'Rusak Ringan' ? 'selected' : '' }}>
                            Rusak Ringan
                        </option>

                        <option value="Rusak Berat" {{ old('kondisi', $barang->kondisi) == 'Rusak Berat' ? 'selected' : '' }}>
                            Rusak Berat
                        </option>

                    </select>

                    @error('kondisi')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="buttons">

                    <button type="submit" class="btn btn-primary">
                        💾 Simpan Perubahan
                    </button>

                    <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>

                </div>

            </form>

        </div>

    </div>

</body>
</html>