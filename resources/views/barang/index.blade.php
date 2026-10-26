<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang - Sistem Inventaris</title>

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
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        h1 {
            font-size: 28px;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 7px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #f9fafb;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 16px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .actions {
            display: flex;
            gap: 6px;
        }

        .actions form {
            display: inline;
        }
    </style>
</head>

<body>

   @include('partials.navigation')
    <div class="container">

        <div class="top">
            <h1>Data Barang</h1>

            <a href="{{ route('barang.create') }}" class="btn btn-primary">
                + Tambah Barang
            </a>
        </div>
               <form method="GET"
              action="{{ route('barang.index') }}"
              style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">

            <input
                type="text"
                name="search"
                value="{{ $search ?? request('search') }}"
                placeholder="Cari kode, nama, atau kategori barang..."
                style="flex:1; min-width:220px; padding:12px; border:1px solid #d1d5db; border-radius:7px;"
            >

            <button type="submit" class="btn btn-primary">
                Cari
            </button>

            <a href="{{ route('barang.index') }}"
               class="btn"
               style="background:#6b7280; color:white;">
                Reset
            </a>

        </form>

        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-container">

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
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($barangs as $barang)

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $barang->kode_barang }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->kategori }}</td>
                            <td>{{ $barang->stok }}</td>
                            <td>{{ $barang->satuan }}</td>
                            <td>{{ $barang->lokasi }}</td>
                            <td>{{ $barang->kondisi }}</td>

                            <td>
                                <div class="actions">

                                    <a href="{{ route('barang.edit', $barang) }}"
                                       class="btn btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('barang.destroy', $barang) }}"
                                          method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger"
                                                onclick="return confirm('Yakin ingin menghapus barang ini?')">
                                            Hapus
                                        </button>

                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="9" style="text-align:center; padding:30px;">
                                Belum ada data barang.
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>

        </div>

    </div>

</body>
</html>