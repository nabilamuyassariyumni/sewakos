<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Verifikasi Kos | SewaKos</title>

    <!-- Tambahkan font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Panggil CSS Eksternal -->
    <link rel="stylesheet" href="{{ asset('css/admin/detail-verifikasi.css') }}">
</head>

<body>
    <div class="container">
        <a href="{{ route('admin.kos.index') }}" class="back-link">
            &larr; Kembali ke Daftar Verifikasi
        </a>

        <div class="header">
            <h1>Detail Verifikasi Kos</h1>
            <p>Periksa informasi kos sebelum memberikan persetujuan.</p>
        </div>

        <div class="card">
            <h2 class="section-title">Foto Kos</h2>
            @if ($kos->foto)
                <img src="{{ asset('storage/' . $kos->foto) }}" alt="Foto {{ $kos->nama_kos }}" class="photo">
            @else
                <div class="no-photo">
                    Foto kos belum tersedia.
                </div>
            @endif
        </div>

        <div class="card">
            <h2 class="section-title">Informasi Kos</h2>
            <div class="info">
                <div class="info-label">Nama Kos</div>
                <div class="info-value">{{ $kos->nama_kos }}</div>

                <div class="info-label">Alamat</div>
                <div class="info-value">{{ $kos->alamat }}</div>

                <div class="info-label">Harga</div>
                <div class="info-value">Rp {{ number_format($kos->harga, 0, ',', '.') }}</div>

                <div class="info-label">Kategori</div>
                <div class="info-value">{{ $kos->kategori }}</div>

                <div class="info-label">Deskripsi</div>
                <div class="info-value">{{ $kos->deskripsi ?: '-' }}</div>

                <div class="info-label">Jumlah Kamar</div>
                <div class="info-value">{{ $kos->jumlah_kamar }}</div>

                <div class="info-label">Fasilitas</div>
                <div class="info-value">{{ $kos->fasilitas ?: '-' }}</div>

                <div class="info-label">Aturan Kos</div>
                <div class="info-value">{{ $kos->aturan ?: '-' }}</div>

                <div class="info-label">Ketersediaan</div>
                <div class="info-value">{{ $kos->tersedia ? 'Tersedia' : 'Tidak tersedia' }}</div>

                <div class="info-label">Status Verifikasi</div>
                <div class="info-value">
                    <span class="status">{{ ucfirst($kos->status_verifikasi) }}</span>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="section-title">Informasi Pemilik</h2>
            <div class="info">
                <div class="info-label">Nama Pemilik</div>
                <div class="info-value">{{ $kos->pemilik->name ?? '-' }}</div>

                <div class="info-label">Email Pemilik</div>
                <div class="info-value">{{ $kos->pemilik->email ?? '-' }}</div>
            </div>
        </div>

        @if ($kos->status_verifikasi === 'menunggu')
            <div class="card">
                <h2 class="section-title">Keputusan Verifikasi</h2>
                <p>Silakan tentukan apakah data kos ini layak ditampilkan kepada pencari kos.</p>

                <div class="actions">
                    <form action="{{ route('admin.kos.update', $kos->id) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menyetujui kos ini?')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_verifikasi" value="disetujui">
                        <button type="submit" class="btn btn-approve">Setujui Kos</button>
                    </form>

                    <form action="{{ route('admin.kos.update', $kos->id) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menolak kos ini?')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status_verifikasi" value="ditolak">
                        <button type="submit" class="btn btn-reject">Tolak Kos</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
