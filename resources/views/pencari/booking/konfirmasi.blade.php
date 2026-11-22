{{-- Halaman konfirmasi booking Pencari --}}
<x-app-layout>

    {{-- CSS khusus halaman konfirmasi --}}
    <link rel="stylesheet" href="{{ asset('css/pencari/konfirmasi-booking.css') }}">

    {{-- Navbar --}}
    <nav class="konfirmasi-navbar">
        <a href="{{ route('pencari.kos.index') }}" class="konfirmasi-logo">
            SewaKos
        </a>

        <div class="konfirmasi-nav-menu">
            <a href="{{ route('pencari.kos.index') }}">Beranda</a>
            <a href="#">Favorit</a>
            <a href="#">Notifikasi</a>
        </div>

        <a href="{{ route('profile.edit') }}" class="konfirmasi-profile">
            {{ Auth::user()->name }}
        </a>
    </nav>

    <main class="konfirmasi-container">

        {{-- Pesan berhasil --}}
        <div class="konfirmasi-success">
            <div class="konfirmasi-success-icon">✓</div>
            <div>
                <h1>Pengajuan Berhasil Dikirim!</h1>
                <p>
                    Pengajuan sewa kos kamu sudah tersimpan.
                    Silakan tunggu konfirmasi dari pemilik kos.
                </p>
            </div>
        </div>

        {{-- Informasi status --}}
        <section class="konfirmasi-status">
            <span class="status-label">Status Pengajuan</span>
            <span class="status-badge">
                Menunggu Persetujuan Pemilik
            </span>
        </section>

        {{-- Ringkasan booking --}}
        <section class="konfirmasi-card">

            <h2>Ringkasan Pengajuan Sewa</h2>

            <div class="konfirmasi-kos">

                {{-- Foto kos --}}
                @if ($booking->kos && $booking->kos->foto)
                    <img src="{{ asset('storage/' . $booking->kos->foto) }}" alt="{{ $booking->kos->nama_kos }}"
                        class="konfirmasi-kos-image">
                @else
                    <div class="konfirmasi-kos-placeholder">
                        Foto kos belum tersedia
                    </div>
                @endif

                {{-- Informasi kos --}}
                <div class="konfirmasi-kos-info">
                    <span class="konfirmasi-category">
                        {{ $booking->kos->kategori ?? 'Kategori kos' }}
                    </span>

                    <h3>{{ $booking->kos->nama_kos ?? 'Kos tidak ditemukan' }}</h3>

                    <p>{{ $booking->kos->alamat ?? '-' }}</p>

                    <strong>
                        Rp{{ number_format($booking->kos->harga ?? 0, 0, ',', '.') }}
                        / bulan
                    </strong>
                </div>

            </div>

            <div class="konfirmasi-divider"></div>

            {{-- Detail booking --}}
            <div class="konfirmasi-details">

                <div class="konfirmasi-detail-row">
                    <span>Nomor Booking</span>
                    <strong>#{{ $booking->id }}</strong>
                </div>

                <div class="konfirmasi-detail-row">
                    <span>Nama Pencari</span>
                    <strong>{{ $booking->pencari->name ?? Auth::user()->name }}</strong>
                </div>

                <div class="konfirmasi-detail-row">
                    <span>Nama Pemilik</span>
                    <strong>{{ $booking->pemilik->name ?? '-' }}</strong>
                </div>

                <div class="konfirmasi-detail-row">
                    <span>Tanggal Mulai Sewa</span>
                    <strong>
                        {{ $booking->tanggal_mulai->format('d M Y') }}
                    </strong>
                </div>

                <div class="konfirmasi-detail-row">
                    <span>Durasi Sewa</span>
                    <strong>{{ $booking->durasi_bulan }} Bulan</strong>
                </div>

                <div class="konfirmasi-detail-row">
                    <span>Catatan</span>
                    <strong>{{ $booking->catatan ?: 'Tidak ada catatan' }}</strong>
                </div>

            </div>

            <div class="konfirmasi-divider"></div>

            {{-- Total pembayaran --}}
            <div class="konfirmasi-total">
                <span>Total Biaya Sewa</span>
                <strong>
                    Rp{{ number_format($booking->total_harga, 0, ',', '.') }}
                </strong>
            </div>

            <p class="konfirmasi-note">
                Pembayaran belum dilakukan. Silakan menunggu persetujuan
                pemilik kos sebelum melanjutkan ke tahap pembayaran.
            </p>

        </section>

        {{-- Tombol navigasi --}}
        <div class="konfirmasi-actions">
            <!-- TAMBAHKAN TOMBOL INI -->
            @if ($booking->status === 'disetujui')
                <a href="{{ route('pencari.booking.pembayaran', $booking->id) }}" class="btn-kembali"
                    style="background-color: #007bff; color: white; margin-bottom: 10px; display: block; text-align: center;">
                    Lanjut ke Pembayaran
                </a>
            @endif

            <a href="{{ route('pencari.kos.index') }}" class="btn-kembali">
                Kembali ke Beranda
            </a>
        </div>

        <div class="konfirmasi-actions">
            <a href="{{ route('pencari.kos.index') }}" class="btn-kembali">
                Kembali ke Beranda
            </a>
        </div>

    </main>

</x-app-layout>
