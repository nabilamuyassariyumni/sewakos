{{-- Menggunakan layout utama aplikasi --}}
<x-app-layout>

    {{-- Memanggil CSS khusus halaman detail kost --}}
    <link rel="stylesheet" href="{{ asset('css/pencari/detail-kos.css') }}">

    {{-- Navbar halaman detail --}}
    <nav class="detail-navbar">
        <a href="{{ route('pencari.kos.index') }}" class="detail-logo">
            SewaKos
        </a>

        <div class="detail-nav-menu">
            <a href="{{ route('pencari.kos.index') }}">Beranda</a>
            <a href="#">Favorit</a>
            <a href="#">Notifikasi</a>
        </div>

        <a href="{{ route('profile.edit') }}" class="detail-profile">
            {{ Auth::user()->name }}
        </a>
    </nav>

    <main class="detail-container">

        {{-- Breadcrumb --}}
        <div class="detail-breadcrumb">
            <a href="{{ route('pencari.kos.index') }}">Beranda</a>
            <span>/</span>
            <span>Detail Kost</span>
        </div>

        <div class="detail-layout">

            {{-- Konten utama --}}
            <div class="detail-main">

                {{-- Foto kost --}}
                <section class="detail-gallery">
                    @if ($kos->foto)
                        <img src="{{ asset('storage/' . $kos->foto) }}" alt="{{ $kos->nama_kos }}"
                            class="detail-main-image">
                    @else
                        <div class="detail-image-placeholder">
                            Foto kost belum tersedia
                        </div>
                    @endif
                </section>

                {{-- Informasi utama kost --}}
                <section class="detail-heading">
                    <div>
                        <span class="detail-category">
                            {{ $kos->kategori }}
                        </span>

                        <h1>{{ $kos->nama_kos }}</h1>

                        <p class="detail-address">
                            {{ $kos->alamat }}
                        </p>
                    </div>

                    <div class="detail-price">
                        <strong>
                            Rp{{ number_format($kos->harga, 0, ',', '.') }}
                        </strong>
                        <span>/ bulan</span>
                    </div>
                </section>

                <div class="detail-divider"></div>

                {{-- Deskripsi kost --}}
                <section class="detail-section">
                    <h2>Deskripsi Kost</h2>

                    <p class="detail-description">
                        {{ $kos->deskripsi ?: 'Belum ada deskripsi untuk kost ini.' }}
                    </p>
                </section>

                <div class="detail-divider"></div>

                {{-- Fasilitas kost --}}
                <section class="detail-section">
                    <h2>Fasilitas</h2>

                    @if (count($fasilitas) > 0)
                        <ul class="detail-facility-list">
                            @foreach ($fasilitas as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="detail-empty">
                            Belum ada informasi fasilitas.
                        </p>
                    @endif
                </section>

                <div class="detail-divider"></div>

                {{-- Fasilitas umum --}}
                <section class="detail-section">
                    <h2>Fasilitas Umum</h2>

                    @if (count($fasilitasUmum) > 0)
                        <ul class="detail-facility-list">
                            @foreach ($fasilitasUmum as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="detail-empty">
                            Belum ada informasi fasilitas umum.
                        </p>
                    @endif
                </section>

                <div class="detail-divider"></div>

                {{-- Peraturan kost --}}
                <section class="detail-section">
                    <h2>Peraturan Kos</h2>

                    @if (count($aturan) > 0)
                        <ol class="detail-rule-list">
                            @foreach ($aturan as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ol>
                    @else
                        <p class="detail-empty">
                            Belum ada informasi peraturan kost.
                        </p>
                    @endif
                </section>

                <div class="detail-divider"></div>

                {{-- Informasi kamar --}}
                <section class="detail-section">
                    <h2>Informasi Kamar</h2>

                    <div class="detail-room-info">
                        <div>
                            <span>Jumlah kamar</span>
                            <strong>{{ $kos->jumlah_kamar ?? 0 }} kamar</strong>
                        </div>

                        <div>
                            <span>Status ketersediaan</span>

                            <strong>
                                {{ $kos->tersedia ? 'Tersedia' : 'Tidak tersedia' }}
                            </strong>
                        </div>
                    </div>
                </section>

                <div class="detail-divider"></div>

                {{-- Lokasi kost --}}
                <section class="detail-section">
                    <h2>Lokasi Kost</h2>

                    <p class="detail-address">
                        {{ $kos->alamat }}
                    </p>

                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($kos->alamat) }}"
                        target="_blank" rel="noopener noreferrer" class="detail-map-link">
                        Lihat lokasi di Google Maps
                    </a>
                </section>

            </div>

            {{-- Sidebar --}}
            <aside class="detail-sidebar">

                {{-- Pesan berhasil --}}
                @if (session('success'))
                    <div class="alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Pesan gagal --}}
                @if (session('error'))
                    <div class="alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Pesan validasi --}}
                @if ($errors->any())
                    <div class="alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Harga dan pengajuan sewa --}}
                <div class="detail-sidebar-box">

                    <span class="detail-sidebar-label">Harga sewa</span>

                    <h2>
                        Rp{{ number_format($kos->harga, 0, ',', '.') }}
                    </h2>

                    <span class="detail-month">per bulan</span>

                    {{-- Form pengajuan booking --}}
                    <form action="{{ route('pencari.booking.store', $kos->id) }}" method="POST" id="form-booking">
                        @csrf

                        {{-- Durasi sewa --}}
                        <div class="detail-duration">
                            <label for="durasi-sewa">Pilih lama sewa</label>

                            <select id="durasi-sewa" name="durasi_bulan" class="detail-duration-select"
                                data-monthly-price="{{ (int) $kos->harga }}" required>
                                <option value="1">1 Bulan</option>
                                <option value="3">3 Bulan</option>
                                <option value="6">6 Bulan</option>
                                <option value="12">1 Tahun</option>
                            </select>
                        </div>

                        {{-- Tanggal mulai --}}
                        <div class="detail-duration">
                            <label for="tanggal-mulai">
                                Tanggal mulai sewa
                            </label>

                            <input type="date" id="tanggal-mulai" name="tanggal_mulai" class="detail-duration-select"
                                min="{{ date('Y-m-d') }}" value="{{ old('tanggal_mulai') }}" required>
                        </div>

                        {{-- Catatan --}}
                        <div class="detail-duration">
                            <label for="catatan-booking">
                                Catatan tambahan (opsional)
                            </label>

                            <textarea id="catatan-booking" name="catatan" class="detail-duration-select" rows="3" maxlength="1000"
                                placeholder="Tuliskan pertanyaan atau informasi tambahan...">{{ old('catatan') }}</textarea>
                        </div>

                        {{-- Total harga --}}
                        <div class="detail-total-price">
                            <span>Total biaya sewa</span>

                            <strong id="total-harga-sewa">
                                Rp{{ number_format($kos->harga, 0, ',', '.') }}
                            </strong>

                            <small id="rincian-harga-sewa">
                                Rp{{ number_format($kos->harga, 0, ',', '.') }} × 1 bulan
                            </small>
                        </div>

                        {{-- Status ketersediaan --}}
                        <div class="detail-availability">
                            {{ $kos->tersedia ? 'Tersedia' : 'Tidak tersedia' }}
                        </div>

                        {{-- Tombol pengajuan --}}
                        <button type="submit" class="detail-booking-button" {{ !$kos->tersedia ? 'disabled' : '' }}>
                            Ajukan Sewa
                        </button>

                    </form>

                    {{-- Tombol tanya pemilik --}}
                    <button type="button" class="detail-contact-button">
                        Tanya Pemilik
                    </button>

                </div>

                {{-- Informasi pemilik --}}
                <div class="detail-sidebar-box detail-owner-box">

                    <h3>Informasi Pemilik</h3>

                    <div class="detail-owner-info">

                        <div class="detail-owner-avatar">
                            {{ strtoupper(substr($kos->pemilik->name ?? 'P', 0, 1)) }}
                        </div>

                        <div>
                            <strong>
                                {{ $kos->pemilik->name ?? 'Pemilik Kost' }}
                            </strong>

                            <span>Pemilik Kost</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="detail-footer">
        <p>&copy; {{ date('Y') }} SewaKos. Semua hak dilindungi.</p>
    </footer>

    {{-- Memanggil JavaScript khusus halaman detail kost --}}

    <script>
        // Menunggu halaman selesai dimuat.
        document.addEventListener('DOMContentLoaded', function() {

            // Mengambil elemen dropdown durasi sewa.
            const dropdownDurasi = document.getElementById('durasi-sewa');

            // Mengambil elemen untuk menampilkan total harga.
            const totalHarga = document.getElementById('total-harga-sewa');

            // Mengambil elemen untuk menampilkan rincian perhitungan.
            const rincianHarga = document.getElementById('rincian-harga-sewa');

            // Memastikan seluruh elemen tersedia.
            if (!dropdownDurasi || !totalHarga || !rincianHarga) {
                return;
            }

            // Mengambil harga bulanan dari data kost.
            const hargaBulanan = Number(dropdownDurasi.dataset.monthlyPrice);

            // Fungsi menghitung total biaya sewa.
            function hitungTotalHarga() {

                // Mengambil durasi yang dipilih pengguna.
                const durasi = Number(dropdownDurasi.value);

                // Menghitung harga berdasarkan durasi.
                const total = hargaBulanan * durasi;

                // Format angka menjadi format Rupiah.
                const formatRupiah = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });

                // Menampilkan total harga.
                totalHarga.textContent = formatRupiah.format(total);

                // Menampilkan rincian perhitungan.
                rincianHarga.textContent =
                    formatRupiah.format(hargaBulanan) + ' × ' + durasi + ' bulan';
            }

            // Menghitung ulang ketika pilihan dropdown berubah.
            dropdownDurasi.addEventListener('change', hitungTotalHarga);

            // Menghitung harga pertama kali ketika halaman dibuka.
            hitungTotalHarga();

        });
    </script>
</x-app-layout>
