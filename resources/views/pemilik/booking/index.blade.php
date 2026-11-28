<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/pemilik/booking.css') }}">

    <div class="booking-owner-container">

        <div class="booking-owner-header">
            <h1>Manajemen Booking</h1>
            <p>Kelola pengajuan sewa yang masuk untuk kos milikmu.</p>
        </div>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($bookings->isEmpty())

            <div class="empty-booking">
                <h3>Belum Ada Pengajuan Sewa</h3>
                <p>Pengajuan sewa dari Pencari akan muncul di halaman ini.</p>
            </div>
        @else
            <div class="owner-booking-list">

                @foreach ($bookings as $booking)
                    <div class="owner-booking-card">

                        <div class="owner-booking-top">
                            <div>
                                <span class="booking-code">
                                    Booking #{{ $booking->id }}
                                </span>

                                <h3>
                                    {{ $booking->kos->nama_kos ?? 'Kos tidak tersedia' }}
                                </h3>
                            </div>

                            <span class="status status-{{ strtolower($booking->status) }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>

                        {{-- Detail Booking --}}
                        <div class="owner-booking-details">
                            {{-- Nama Pencari --}}
                            <div>
                                <span>Nama Pencari</span>
                                <strong>
                                    {{ $booking->pencari->name ?? 'Tidak tersedia' }}
                                </strong>
                            </div>

                            {{-- Tanggal Mulai --}}
                            <div>
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

                        </div>

                        @if ($booking->catatan)
                            <div class="owner-booking-note">
                                <span>Catatan Pencari</span>
                                <p>{{ $booking->catatan }}</p>
                            </div>
                        @endif

                        {{-- Tombol tindakan hanya untuk booking yang menunggu --}}
                        @if ($booking->status === 'menunggu')
                            <div class="booking-owner-actions">

                                {{-- Form untuk menerima pengajuan --}}
                                <form action="{{ route('pemilik.booking.terima', $booking->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah kamu yakin ingin menerima pengajuan booking ini?')">
                                    @csrf

                                    <button type="submit" class="btn-booking-accept">
                                        ✓ Terima Pengajuan
                                    </button>
                                </form>

                                {{-- Form untuk menolak pengajuan --}}
                                <form action="{{ route('pemilik.booking.tolak', $booking->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah kamu yakin ingin menolak pengajuan booking ini?')">
                                    @csrf

                                    <button type="submit" class="btn-booking-reject">
                                        ✕ Tolak Pengajuan
                                    </button>
                                </form>

                            </div>
                        @endif

                    </div>
                @endforeach

            </div>

            <div class="booking-pagination">
                {{ $bookings->links() }}
            </div>

        @endif

    </div>

</x-app-layout>
