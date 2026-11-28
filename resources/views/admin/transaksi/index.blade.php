<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - SewaKos</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/transaksi.css') }}">
</head>
<body>
    <div class="container">
        <h1>Riwayat Transaksi</h1>
        <p class="subtitle">Pantau seluruh transaksi pembayaran di platform SewaKos.</p>

        <!-- Card Statistik -->
        <div class="summary-cards">
            <div class="summary-card">
                <p>Total Transaksi</p>
                <h2>{{ $totalTransaksi }}</h2>
            </div>
            <div class="summary-card">
                <p>Transaksi Berhasil</p>
                <h2 style="color: #059669;">{{ $totalBerhasil }}</h2>
            </div>
            <div class="summary-card">
                <p>Total Pendapatan</p>
                <h2>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="filter-bar">
            <a href="{{ route('admin.transaksi.index') }}" class="filter-tab {{ !$status ? 'active' : '' }}">Semua</a>
            <a href="{{ route('admin.transaksi.index', ['status' => 'berhasil']) }}" class="filter-tab {{ $status == 'berhasil' ? 'active' : '' }}">Lunas</a>
            <a href="{{ route('admin.transaksi.index', ['status' => 'menunggu_pembayaran']) }}" class="filter-tab {{ $status == 'menunggu_pembayaran' ? 'active' : '' }}">Menunggu</a>
            <a href="{{ route('admin.transaksi.index', ['status' => 'gagal']) }}" class="filter-tab {{ $status == 'gagal' ? 'active' : '' }}">Gagal</a>

            <form action="{{ route('admin.transaksi.index') }}" method="GET" class="search-form">
                @if($status)
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif
                <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Cari Order ID / Pencari / Kos...">
                <button type="submit">Cari</button>
            </form>
        </div>

        <!-- Tabel Transaksi -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Order ID</th>
                        <th>Kos</th>
                        <th>Pencari</th>
                        <th>Pemilik</th>
                        <th>Nominal</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $index => $item)
                        <tr>
                            <td>{{ $transaksi->firstItem() + $index }}</td>
                            <td style="font-family: monospace; font-size: 11px;">{{ $item->midtrans_order_id }}</td>
                            <td>{{ $item->kos->nama_kos ?? '-' }}</td>
                            <td>{{ $item->pencari->name ?? '-' }}</td>
                            <td>{{ $item->pemilik->name ?? '-' }}</td>
                            <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                            <td>
                                <span class="status-badge status-{{ $item->status_pembayaran }}">
                                    {{ str_replace('_', ' ', $item->status_pembayaran) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.kos.show', $item->kos_id) }}" style="color: #2166F3; font-size: 12px; font-weight: bold; text-decoration: none;">Lihat Kos</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="empty">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="pagination">{{ $transaksi->links() }}</div>
        </div>
    </div>
</body>
</html>
