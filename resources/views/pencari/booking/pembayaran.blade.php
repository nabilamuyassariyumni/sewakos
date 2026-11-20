
<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/pencari/pembayaran.css') }}">

    <main class="payment-container">

        <div class="payment-header">
            <h1>Pembayaran Sewa Kos</h1>
            <p>Periksa kembali rincian booking sebelum melanjutkan pembayaran.</p>
        </div>

        <div class="payment-card">

            <h2>Rincian Booking</h2>

            <div class="payment-detail">
                <span>Nama Kos</span>
                <strong>{{ $booking->kos->nama_kos }}</strong>
            </div>

            <div class="payment-detail">
                <span>Nama Pemilik</span>
                <strong>{{ $booking->pemilik->name }}</strong>
            </div>

            <div class="payment-detail">
                <span>Tanggal Mulai</span>
                <strong>{{ $booking->tanggal_mulai->format('d M Y') }}</strong>
            </div>

            <div class="payment-detail">
                <span>Durasi Sewa</span>
                <strong>{{ $booking->durasi_bulan }} Bulan</strong>
            </div>

            <div class="payment-detail">
                <span>Status Booking</span>
                <strong>{{ ucfirst($booking->status) }}</strong>
            </div>

            <div class="payment-detail">
                <span>Status Pembayaran</span>
                <strong>Menunggu Pembayaran</strong>
            </div>

            <div class="payment-total">
                <span>Total Pembayaran</span>
                <h2>
                    Rp{{ number_format($booking->total_harga, 0, ',', '.') }}
                </h2>
            </div>

            <button type="button" class="payment-button" disabled>
                Pembayaran Midtrans (Segera Hadir)
            </button>

            <button type="button" class="back-button" onclick="window.location.href='{{ route('pencari.booking.show', $booking->id) }}'">
                Kembali ke konfirmasi booking
            </button>

        </div>

    </main>

</x-app-layout>
