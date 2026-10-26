<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - Sistem Inventaris</title>

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
            width: 90%;
            margin: 50px auto;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        h1 {
            font-size: 34px;
            margin: 0;
        }

        .btn {
            background: #2563eb;
            color: white;
            padding: 13px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f8fafc;
        }

        .masuk {
            color: #16a34a;
            font-weight: bold;
        }

        .keluar {
            color: #dc2626;
            font-weight: bold;
        }

        .kosong {
            text-align: center;
            padding: 30px;
        }
    </style>
</head>

<body>

@include('partials.navigation')
<div class="container">

    <div class="top">
        <h1>Data Transaksi</h1>

        <a href="{{ route('transaksi.create') }}" class="btn">
            + Tambah Transaksi
        </a>
    </div>

    <div class="card">

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Barang</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($transaksis as $transaksi)

                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{ $transaksi->barang->nama_barang ?? '-' }}
                        </td>

                        <td>
                            @if ($transaksi->jenis == 'masuk')
                                <span class="masuk">Barang Masuk</span>
                            @else
                                <span class="keluar">Barang Keluar</span>
                            @endif
                        </td>

                        <td>{{ $transaksi->jumlah }}</td>

                        <td>{{ $transaksi->keterangan ?? '-' }}</td>

                        <td>{{ $transaksi->created_at->format('d-m-Y') }}</td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="kosong">
                            Belum ada transaksi.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>

    </div>

</div>

</body>
</html>