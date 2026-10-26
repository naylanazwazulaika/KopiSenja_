<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi - Sistem Inventaris</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6fa;
            color: #172033;
        }

        header {
            background: #111827;
            color: white;
            padding: 20px 40px;
            font-size: 30px;
            font-weight: bold;
        }

        .container {
            width: 60%;
            margin: 45px auto;
        }

        .card {
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        h1 {
            margin-top: 0;
            margin-bottom: 30px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        input, select, textarea {
            width: 100%;
            padding: 13px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 16px;
        }

        textarea {
            resize: vertical;
        }

        .buttons {
            margin-top: 30px;
            display: flex;
            gap: 10px;
        }

        .btn {
            border: none;
            padding: 13px 22px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            font-size: 15px;
        }

        .btn-simpan {
            background: #2563eb;
            color: white;
        }

        .btn-kembali {
            background: #6b7280;
            color: white;
        }

        .error {
            color: #dc2626;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>

<body>

@include('partials.navigation')
<div class="container">

    <div class="card">

        <h1>Tambah Transaksi</h1>

        <form action="{{ route('transaksi.store') }}" method="POST">

            @csrf

            <div class="form-group">
                <label>Barang</label>

                <select name="barang_id" required>
                    <option value="">-- Pilih Barang --</option>

                    @foreach ($barangs as $barang)
                        <option value="{{ $barang->id }}">
                            {{ $barang->kode_barang }} - {{ $barang->nama_barang }}
                        </option>
                    @endforeach

                </select>

                @error('barang_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Jenis Transaksi</label>

                <select name="jenis" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="masuk">Barang Masuk</option>
                    <option value="keluar">Barang Keluar</option>
                </select>

                @error('jenis')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Jumlah</label>

                <input
                    type="number"
                    name="jumlah"
                    min="1"
                    placeholder="Contoh: 5"
                    required
                >

                @error('jumlah')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Keterangan</label>

                <textarea
                    name="keterangan"
                    rows="4"
                    placeholder="Contoh: Pembelian barang baru"
                ></textarea>
            </div>

            <div class="buttons">

                <button type="submit" class="btn btn-simpan">
                    Simpan Transaksi
                </button>

                <a href="{{ route('transaksi.index') }}" class="btn btn-kembali">
                    Kembali
                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>