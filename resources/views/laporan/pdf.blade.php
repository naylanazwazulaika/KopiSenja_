<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Laporan Inventaris</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        .periode {
            text-align: center;
            margin-bottom: 25px;
            color: #555;
        }

        h2 {
            margin-top: 25px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background: #e5e7eb;
            font-weight: bold;
        }

        th, td {
            border: 1px solid #999;
            padding: 7px;
            text-align: left;
        }

        .masuk {
            color: green;
            font-weight: bold;
        }

        .keluar {
            color: red;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>

<body>

    <h1>LAPORAN INVENTARIS</h1>

    <div class="periode">
        @if($startDate || $endDate)
            Periode:
            {{ $startDate ?? 'Semua' }}
            sampai
            {{ $endDate ?? 'Semua' }}
        @else
            Semua Periode
        @endif
    </div>

    <h2>Data Barang</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Lokasi</th>
                <th>Kondisi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($barangs as $barang)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $barang->kode_barang }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->kategori }}</td>
                    <td>{{ $barang->stok }}</td>
                    <td>{{ $barang->satuan }}</td>
                    <td>{{ $barang->lokasi }}</td>
                    <td>{{ $barang->kondisi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Riwayat Transaksi</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>

        <tbody>
            @foreach($transaksis as $transaksi)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaksi->created_at->format('d-m-Y') }}</td>
                    <td>{{ $transaksi->barang->kode_barang ?? '-' }}</td>
                    <td>{{ $transaksi->barang->nama_barang ?? '-' }}</td>

                    <td>
                        @if($transaksi->jenis == 'masuk')
                            <span class="masuk">Barang Masuk</span>
                        @else
                            <span class="keluar">Barang Keluar</span>
                        @endif
                    </td>

                    <td>{{ $transaksi->jumlah }}</td>
                    <td>{{ $transaksi->keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d-m-Y H:i') }}
    </div>

</body>
</html>