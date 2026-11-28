<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Verifikasi Kos - SewaKos</title>

    <!-- Tambahkan font agar seragam -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Panggil CSS Eksternal -->
    <link rel="stylesheet" href="{{ asset('css/admin/verifikasi.css') }}">
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
