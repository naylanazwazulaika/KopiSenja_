<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f6fa;
            color: #172033;
        }

        .container {
            max-width: 1250px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        h1 {
            margin: 0;
            font-size: 30px;
        }

        h2 {
            margin: 0 0 20px;
            font-size: 22px;
        }

        .card {
            background: white;
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
            overflow-x: auto;
        }

        .btn {
            display: inline-block;
            padding: 11px 18px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 7px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 13px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background: #f8fafc;
        }

        .masuk {
            color: #15803d;
            font-weight: bold;
        }

        .keluar {
            color: #dc2626;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            padding: 25px;
            color: #6b7280;
        }

        .date {
            margin-top: 8px;
            color: #64748b;
            font-size: 14px;
        }

        @media print {
            body {
                background: white;
            }

            .main-navigation,
            .no-print {
                display: none !important;
            }

            .container {
                max-width: none;
                margin: 10px;
                padding: 0;
            }

            .card {
                box-shadow: none;
                border: 1px solid #ddd;
                padding: 12px;
                break-inside: avoid;
            }

            th, td {
                padding: 8px;
                white-space: normal;
            }
        }
    </style>
</head>

<body>

    @include('partials.navigation')
<div style="
    max-width: 1100px;
    margin: 30px auto;
    padding: 25px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
">

    <h2 style="margin-top: 0;">
        🔎 Filter Laporan Transaksi
    </h2>

    <form method="GET" action="{{ route('laporan.index') }}"
          style="
              display: flex;
              flex-wrap: wrap;
              gap: 15px;
              align-items: end;
          ">

        <div>
            <label style="display:block; margin-bottom:6px; font-weight:bold;">
                Dari Tanggal
            </label>

            <input
                type="date"
                name="start_date"
                value="{{ $startDate ?? '' }}"
                style="
                    padding: 10px;
                    border: 1px solid #cbd5e1;
                    border-radius: 7px;
                "
            >
        </div>

        <div>
            <label style="display:block; margin-bottom:6px; font-weight:bold;">
                Sampai Tanggal
            </label>

            <input
                type="date"
                name="end_date"
                value="{{ $endDate ?? '' }}"
                style="
                    padding: 10px;
                    border: 1px solid #cbd5e1;
                    border-radius: 7px;
                "
            >
        </div>

        <button
            type="submit"
            style="
                padding: 11px 20px;
                background: #2563eb;
                color: white;
                border: none;
                border-radius: 7px;
                cursor: pointer;
            "
        >
            🔎 Filter
        </button>

        <a
            href="{{ route('laporan.index') }}"
            style="
                padding: 11px 20px;
                background: #64748b;
                color: white;
                text-decoration: none;
                border-radius: 7px;
            "
        >
            Reset
        </a>

    </form>

</div>

    <div class="container">

        <div class="heading">
            <div>
                <h1>Laporan Inventaris</h1>
                <div class="date">
                    Dicetak pada: {{ now()->format('d-m-Y H:i') }}
                </div>
            </div>

           <a href="{{ route('laporan.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
   target="_blank"
   class="btn no-print">
    📄 Cetak PDF
</a>
        </div>

        <div class="card">
            <h2>Laporan Data Barang</h2>

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
                    @forelse ($barangs as $barang)
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
                    @empty
                        <tr>
                            <td colspan="8" class="empty">
                                Belum ada data barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card">
            <h2>Laporan Riwayat Transaksi</h2>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jenis Transaksi</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($transaksis as $transaksi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $transaksi->created_at->format('d-m-Y') }}</td>
                            <td>{{ $transaksi->barang->kode_barang ?? '-' }}</td>
                            <td>{{ $transaksi->barang->nama_barang ?? '-' }}</td>

                            <td>
                                @if ($transaksi->jenis == 'masuk')
                                    <span class="masuk">Barang Masuk</span>
                                @else
                                    <span class="keluar">Barang Keluar</span>
                                @endif
                            </td>

                            <td>{{ $transaksi->jumlah }}</td>
                            <td>{{ $transaksi->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty">
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