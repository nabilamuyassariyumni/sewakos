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
                <strong>{{ ucfirst(str_replace('_', ' ', $booking->status_pembayaran)) }}</strong>
            </div>

            <div class="payment-total">
                <span>Total Pembayaran</span>
                <h2>
                    Rp{{ number_format($booking->total_harga, 0, ',', '.') }}
                </h2>
            </div>

            @if ($booking->status_pembayaran == 'belum_ada' || $booking->status_pembayaran == 'menunggu_pembayaran')
                <button type="button" id="pay-button" class="payment-button">
                    Bayar Sekarang dengan Midtrans
                </button>
            @elseif ($booking->status_pembayaran == 'gagal')
                <button type="button" class="payment-button"
                    onclick="window.location.href='{{ route('pencari.booking.pembayaran', $booking->id) }}?retry=true'"
                    style="background-color: #ff4d4f; color: white; cursor: pointer; width: 100%; border: none; padding: 12px; border-radius: 5px; font-weight: bold;">
                    Coba Bayar Lagi
                </button>
            @else
                <button type="button" class="payment-button"
                    style="background-color: #6c757d; cursor: not-allowed;">
                    Pembayaran Selesai
                </button>
            @endif

            <button type="button" class="back-button"
                onclick="window.location.href='{{ route('pencari.booking.show', $booking->id) }}'">
                Kembali ke konfirmasi booking
            </button>

        </div>

    </main>

    <!-- PINDAHKAN SCRIPT KE SINI, TEPAT SEBELUM TAG PENUTUP -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var payButton = document.getElementById('pay-button');

            if (payButton) {
                payButton.onclick = function() {
                    // Cek apakah token ada
                    var token = '{{ $booking->snap_token }}';

                    if (!token || token === '') {
                        alert('Token pembayaran tidak ditemukan. Silakan refresh halaman.');
                        return;
                    }

                    snap.pay(token, {
                        onSuccess: function(result) {
                            alert("Pembayaran berhasil!");
                            window.location.href = "{{ route('pencari.booking.index') }}";
                        },
                        onPending: function(result) {
                            alert("Menunggu pembayaran Anda!");
                            window.location.reload();
                        },
                        onError: function(result) {
                            alert("Pembayaran gagal, silakan coba lagi.");
                            window.location.reload();
                        },
                        onClose: function() {
                            alert('Anda menutup pop-up tanpa menyelesaikan pembayaran.');
                        }
                    });
                };
            } else {
                console.log("Tombol bayar tidak ditemukan di DOM.");
            }
        });
    </script>

</x-app-layout>
