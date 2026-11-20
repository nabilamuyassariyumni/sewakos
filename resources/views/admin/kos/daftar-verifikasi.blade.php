<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Verifikasi Kos - SewaKos</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #F4F7FF;
            color: #15336F;
            padding: 35px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        h1 {
            margin-bottom: 8px;
        }

        .subtitle {
            color: #718096;
            margin-bottom: 25px;
        }

        .table-container {
            background: white;
            padding: 24px;
            border-radius: 12px;
            overflow-x: auto;
            border: 1px solid #E2E8F0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #E2E8F0;
            font-size: 14px;
        }

        th {
            background: #DCE9FF;
            color: #15336F;
        }

        .status {
            background: #FFF3CD;
            color: #856404;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .btn-detail {
            display: inline-block;
            background: #2166F3;
            color: white;
            padding: 8px 13px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
        }

        .empty {
            text-align: center;
            color: #718096;
            padding: 25px;
        }

        .pagination {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container">

        <h1>Daftar Verifikasi Kos</h1>
        <p class="subtitle">
            Periksa pengajuan data kos dari pemilik sebelum ditampilkan kepada pencari kos.
        </p>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kos</th>
                        <th>Pemilik</th>
                        <th>Kategori</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($kos as $index => $item)
                        <tr>
                            <td>{{ $kos->firstItem() + $index }}</td>
                            <td>{{ $item->nama_kos }}</td>
                            <td>{{ $item->pemilik->name ?? '-' }}</td>
                            <td>{{ $item->kategori }}</td>
                            <td>{{ $item->alamat }}</td>
                            <td>
                                <span class="status">Menunggu Verifikasi</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.kos.show', $item->id) }}" class="btn-detail">
                                    Periksa
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty">
                                Belum ada pengajuan kos yang perlu diverifikasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $kos->links() }}
            </div>
        </div>

    </div>

</body>

</html>
