<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Verifikasi Kos | SewaKos</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #F4F7FF;
            color: #15336F;
            padding: 30px;
        }

        .container {
            max-width: 950px;
            margin: auto;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 25px;
            margin-bottom: 8px;
        }

        .header p {
            color: #718096;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #2166F3;
            text-decoration: none;
            font-weight: bold;
        }

        .card {
            background: white;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
        }

        .photo {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .no-photo {
            background: #DCE9FF;
            padding: 70px 20px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 20px;
            color: #718096;
        }

        .section-title {
            font-size: 18px;
            margin-bottom: 18px;
        }

        .info {
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 14px;
        }

        .info-label {
            color: #718096;
        }

        .info-value {
            color: #15336F;
            font-weight: 500;
            overflow-wrap: anywhere;
        }

        .status {
            display: inline-block;
            background: #FFF4D6;
            color: #956500;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .btn {
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            text-decoration: none;
        }

        .btn-approve {
            background: #16A765;
            color: white;
        }

        .btn-reject {
            background: #DC3545;
            color: white;
        }

        .btn-back {
            background: #DCE9FF;
            color: #2166F3;
        }

        @media (max-width: 600px) {
            body {
                padding: 15px;
            }

            .info {
                grid-template-columns: 1fr;
                gap: 6px;
            }

            .info-value {
                margin-bottom: 10px;
            }
        }
    </style>
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

        {{-- Informasi foto kos --}}
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

        {{-- Informasi utama kos --}}
        <div class="card">
            <h2 class="section-title">Informasi Kos</h2>

            <div class="info">
                <div class="info-label">Nama Kos</div>
                <div class="info-value">{{ $kos->nama_kos }}</div>

                <div class="info-label">Alamat</div>
                <div class="info-value">{{ $kos->alamat }}</div>

                <div class="info-label">Harga</div>
                <div class="info-value">
                    Rp {{ number_format($kos->harga, 0, ',', '.') }}
                </div>

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
                <div class="info-value">
                    {{ $kos->tersedia ? 'Tersedia' : 'Tidak tersedia' }}
                </div>

                <div class="info-label">Status Verifikasi</div>
                <div class="info-value">
                    <span class="status">{{ ucfirst($kos->status_verifikasi) }}</span>
                </div>
            </div>
        </div>

        {{-- Informasi pemilik --}}
        <div class="card">
            <h2 class="section-title">Informasi Pemilik</h2>

            <div class="info">
                <div class="info-label">Nama Pemilik</div>
                <div class="info-value">
                    {{ $kos->pemilik->name ?? '-' }}
                </div>

                <div class="info-label">Email Pemilik</div>
                <div class="info-value">
                    {{ $kos->pemilik->email ?? '-' }}
                </div>
            </div>
        </div>

        {{-- Tombol persetujuan hanya muncul jika status masih menunggu --}}
        @if ($kos->status_verifikasi === 'menunggu')
            <div class="card">
                <h2 class="section-title">Keputusan Verifikasi</h2>
                <p>Silakan tentukan apakah data kos ini layak ditampilkan kepada pencari kos.</p>

                <div class="actions">


                    {{-- Form menyetujui kos --}}
                    <form action="{{ route('admin.kos.update', $kos->id) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menyetujui kos ini?')">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="status_verifikasi" value="disetujui">

                        <button type="submit" class="btn btn-approve">
                            Setujui Kos
                        </button>
                    </form>

                    {{-- Form menolak kos --}}
                    <form action="{{ route('admin.kos.update', $kos->id) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menolak kos ini?')">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="status_verifikasi" value="ditolak">

                        <button type="submit" class="btn btn-reject">
                            Tolak Kos
                        </button>
                    </form>

                </div>
            </div>
        @endif

    </div>
</body>

</html>
