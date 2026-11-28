{{-- Menggunakan layout utama aplikasi --}}
<x-app-layout>

    {{-- Memanggil CSS khusus halaman riwayat booking --}}
    <link rel="stylesheet" href="{{ asset('css/pencari/riwayat-booking.css') }}">

    {{-- Navbar halaman --}}
    <nav class="riwayat-navbar">
        <a href="{{ route('pencari.kos.index') }}" class="brand">
            SewaKos
        </a>

        <div class="nav-menu">
            <a href="{{ route('pencari.kos.index') }}">Beranda</a>
            <a href="{{ route('pencari.booking.index') }}" class="active">Riwayat Booking</a>
            <a href="#">Favorit</a>
            <a href="#">Notifikasi</a>
        </div>
    </nav>

    <main class="riwayat-container">

        {{-- Judul halaman --}}
        <div class="riwayat-header">
            <h1>Riwayat Booking</h1>
            <p>Lihat semua pengajuan sewa kos yang pernah kamu lakukan.</p>
        </div>

        {{-- Pesan jika belum ada booking --}}
        @if ($bookings->isEmpty())

            <div class="empty-booking">
                <h3>Belum Ada Riwayat Booking</h3>
                <p>Kamu belum pernah mengajukan sewa kos.</p>

                <a href="{{ route('pencari.kos.index') }}" class="btn-primary">
                    Cari Kos Sekarang
                </a>
            </div>
        @else
            {{-- Daftar booking --}}
            <div class="booking-list">

                @foreach ($bookings as $booking)
                    <div class="booking-card">

                        <div class="booking-card-header">
                            <span class="booking-code">
                                Booking #{{ $booking->id }}
                            </span>

                            {{-- Status booking --}}
                            <span class="status status-{{ strtolower($booking->status) }}">
                                @if ($booking->status === 'menunggu')
                                    Menunggu Persetujuan
                                @elseif ($booking->status === 'disetujui')
                                    Disetujui
                                @elseif ($booking->status === 'ditolak')
                                    Ditolak
                                @else
                                    {{ ucfirst($booking->status) }}
                                @endif
                            </span>
                        </div>

                        <div class="booking-card-body">

                            <div class="booking-info">
                                <h3>
                                    {{ $booking->kos->nama_kos ?? 'Kos tidak tersedia' }}
                                </h3>

                                <p>
                                    {{ $booking->kos->alamat ?? 'Alamat tidak tersedia' }}
                                </p>

                                <div class="booking-details">
                                    <div>
                                        {{-- Tanggal Mulai --}}
                                        <span>Tanggal Mulai</span>
                                        <strong>
                                            {{ $booking->tanggal_mulai->format('d M Y') }}
                                        </strong>
                                    </div>

                                    {{-- Durasi Sewa --}}
                                    <div>
                                        <span>Durasi Sewa</span>
                                        <strong>
                                            {{ $booking->durasi_bulan }} Bulan
                                        </strong>
                                    </div>

                                    {{-- Total Harga --}}
                                    <div>
                                        <span>Total Harga</span>
                                        <strong>
                                            Rp{{ number_format($booking->total_harga, 0, ',', '.') }}
                                        </strong>
                                    </div>

                                    {{-- Status Pembayaran --}}
                                    <div>
                                        <span>Status Pembayaran</span>
                                        <strong>
                                            @php
                                                $statusClass = str_replace('_', '-', $booking->status_pembayaran);
                                                $statusLabel = match ($booking->status_pembayaran) {
                                                    'belum_ada' => 'Belum Bayar',
                                                    'menunggu_pembayaran' => 'Menunggu',
                                                    'berhasil' => 'Lunas',
                                                    'gagal' => 'Gagal',
                                                    'kedaluwarsa' => 'Kedaluwarsa',
                                                    default => $booking->status_pembayaran,
                                                };
                                            @endphp
                                            <span class="status-pembayaran {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </strong>
                                    </div>

                                    {{-- Tanggal Pengajuan --}}
                                    <div>
                                        <span>Tanggal Pengajuan</span>
                                        <strong>
                                            {{ $booking->created_at->format('d M Y') }}
                                        </strong>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="booking-card-footer">
                            <span>
                                Pengajuan sewa kos
                            </span>

                            <a href="{{ route('pencari.booking.show', $booking->id) }}" class="btn-detail">
                                Lihat Detail
                            </a>
                        </div>

                    </div>
                @endforeach

            </div>

            {{-- Navigasi halaman --}}
            <div class="pagination-wrapper">
                {{ $bookings->links() }}
            </div>

        @endif

    </main>

</x-app-layout>
