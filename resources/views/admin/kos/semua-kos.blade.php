<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengelolaan Data Kos - SewaKos</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/verifikasi.css') }}">
    <style>
        .filter-tabs { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .filter-tab {
            padding: 10px 16px; background: white; border: 1px solid #E2E8F0;
            border-radius: 8px; text-decoration: none; color: #718096;
            font-size: 13px; font-weight: 600; transition: 0.2s;
        }
        .filter-tab:hover { border-color: #2166F3; }
        .filter-tab.active { background: #2166F3; color: white; border-color: #2166F3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pengelolaan Data Kos</h1>
        <p class="subtitle">Kelola seluruh data kos yang ada di platform SewaKos.</p>

        <!-- Filter Tab (Poin 21) -->
        <div class="filter-tabs">
            <a href="{{ route('admin.kos.semua') }}" class="filter-tab {{ !$status ? 'active' : '' }}">Semua</a>
            <a href="{{ route('admin.kos.semua', ['status' => 'menunggu']) }}" class="filter-tab {{ $status == 'menunggu' ? 'active' : '' }}">Menunggu</a>
            <a href="{{ route('admin.kos.semua', ['status' => 'disetujui']) }}" class="filter-tab {{ $status == 'disetujui' ? 'active' : '' }}">Disetujui</a>
            <a href="{{ route('admin.kos.semua', ['status' => 'ditolak']) }}" class="filter-tab {{ $status == 'ditolak' ? 'active' : '' }}">Ditolak</a>
        </div>

        <!-- Tabel (Poin 22) -->
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
                                @if($item->status_verifikasi === 'menunggu')
                                    <span class="status" style="background: #FFF3CD; color: #856404;">Menunggu</span>
                                @elseif($item->status_verifikasi === 'disetujui')
                                    <span class="status" style="background: #D1FAE5; color: #059669;">Disetujui</span>
                                @else
                                    <span class="status" style="background: #FEE2E2; color: #DC2626;">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.kos.show', $item->id) }}" class="btn-detail">Periksa</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty">Belum ada data kos dengan status ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="pagination">{{ $kos->links() }}</div>
        </div>
    </div>
</body>
</html>
