<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Inventaris</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f1f5f9;
            font-family: Arial, sans-serif;
            color: #111827;
        }

        .dashboard-container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .dashboard-title {
            font-size: 44px;
            margin-bottom: 10px;
        }

        .welcome {
            font-size: 24px;
            margin-bottom: 40px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 28px;
        }

        .card {
            background: white;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .card h3 {
            color: #64748b;
            font-size: 23px;
            margin: 0 0 25px;
        }

        .number {
            font-size: 50px;
            font-weight: bold;
        }

        .chart-card {
            background: white;
            border-radius: 18px;
            padding: 35px;
            margin-top: 35px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .chart-card h2 {
            margin-top: 0;
            font-size: 26px;
        }

        .chart {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 280px;
            padding: 30px 20px 0;
            border-bottom: 2px solid #cbd5e1;
        }

        .bar-wrapper {
            width: 20%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
        }

        .bar {
            width: 70px;
            min-height: 5px;
            border-radius: 10px 10px 0 0;
        }

        .bar-masuk {
            background: #22c55e;
            height: {{ max($barangMasuk * 30, 5) }}px;
        }

        .bar-keluar {
            background: #ef4444;
            height: {{ max($barangKeluar * 30, 5) }}px;
        }

        .bar-stok {
            background: #3b82f6;
            height: {{ max($stokSaatIni * 15, 5) }}px;
        }

        .bar-menipis {
            background: #f59e0b;
            height: {{ max($stokMenipis * 30, 5) }}px;
        }

        .bar-value {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .bar-label {
            margin-top: 12px;
            font-weight: bold;
            color: #475569;
        }

        @media (max-width: 900px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .cards {
                grid-template-columns: 1fr;
            }

            .dashboard-title {
                font-size: 34px;
            }

            .welcome {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>

    @include('partials.navigation')

    <div class="dashboard-container">

        <h1 class="dashboard-title">Dashboard</h1>

        <p class="welcome">
            Selamat datang di Sistem Inventaris Barang.
        </p>

        <div class="cards">

            <div class="card">
                <h3>Total Barang</h3>
                <div class="number">{{ $totalBarang }}</div>
            </div>

            <div class="card">
                <h3>Barang Masuk</h3>
                <div class="number">{{ $barangMasuk }}</div>
            </div>

            <div class="card">
                <h3>Barang Keluar</h3>
                <div class="number">{{ $barangKeluar }}</div>
            </div>

            <div class="card">
                <h3>Stok Menipis</h3>
                <div class="number">{{ $stokMenipis }}</div>
            </div>

        </div>

        <div class="chart-card">

            <h2>📊 Statistik Inventaris</h2>

            <div class="chart">

                <div class="bar-wrapper">
                    <div class="bar-value">{{ $barangMasuk }}</div>
                    <div class="bar bar-masuk"></div>
                    <div class="bar-label">Barang Masuk</div>
                </div>

                <div class="bar-wrapper">
                    <div class="bar-value">{{ $barangKeluar }}</div>
                    <div class="bar bar-keluar"></div>
                    <div class="bar-label">Barang Keluar</div>
                </div>

                <div class="bar-wrapper">
                    <div class="bar-value">{{ $stokSaatIni }}</div>
                    <div class="bar bar-stok"></div>
                    <div class="bar-label">Stok Saat Ini</div>
                </div>

                <div class="bar-wrapper">
                    <div class="bar-value">{{ $stokMenipis }}</div>
                    <div class="bar bar-menipis"></div>
                    <div class="bar-label">Stok Menipis</div>
                </div>

            </div>

        </div>

    </div>
        <div class="chart-card">

            <h2>📅 Riwayat Transaksi Harian</h2>

            @php
                $nilaiMaksimum = max(
                    $transaksiHarian->max('masuk') ?? 1,
                    $transaksiHarian->max('keluar') ?? 1,
                    1
                );
            @endphp

            @if($transaksiHarian->count() > 0)

                @foreach($transaksiHarian as $transaksi)

                    <div style="margin-bottom: 28px;">

                        <div style="font-weight: bold; margin-bottom: 8px;">
                            {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y') }}
                        </div>

                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">

                            <span style="width: 110px;">
                                Barang Masuk
                            </span>

                            <div style="
                                height: 25px;
                                width: {{ ($transaksi->masuk / $nilaiMaksimum) * 60 }}%;
                                min-width: {{ $transaksi->masuk > 0 ? '5px' : '0' }};
                                background: #22c55e;
                                border-radius: 6px;
                            "></div>

                            <strong>{{ $transaksi->masuk }}</strong>

                        </div>

                        <div style="display: flex; align-items: center; gap: 10px;">

                            <span style="width: 110px;">
                                Barang Keluar
                            </span>

                            <div style="
                                height: 25px;
                                width: {{ ($transaksi->keluar / $nilaiMaksimum) * 60 }}%;
                                min-width: {{ $transaksi->keluar > 0 ? '5px' : '0' }};
                                background: #ef4444;
                                border-radius: 6px;
                            "></div>

                            <strong>{{ $transaksi->keluar }}</strong>

                        </div>

                    </div>

                @endforeach

            @else

                <p>Belum ada transaksi.</p>

            @endif

        </div>

</body>
</html>