<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Pemilik Kos - SewaKos</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard-pemilik.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <div class="owner-layout">

        {{-- SIDEBAR --}}
        <aside class="owner-sidebar">

            <a href="{{ route('dashboard.pemilik') }}" class="owner-brand">
                <span class="owner-brand-icon">
                    <i class="fa-solid fa-house"></i>
                </span>

                <span>
                    SewaKos
                    <small>Owner Panel</small>
                </span>
            </a>

            <ul class="owner-menu">
                <li>
                    <a href="{{ route('dashboard.pemilik') }}" class="active">
                        <i class="fa-solid fa-border-all menu-icon"></i>
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="{{ route('pemilik.kos.index') }}" class="active">
                        <i class="fa-solid fa-house menu-icon"></i>
                        Data Kos
                    </a>
                </li>

                <li>
                    <a href="#" title="Fitur ini akan dibuat berikutnya">
                        <i class="fa-solid fa-bed menu-icon"></i>
                        Data Kamar
                    </a>
                </li>

                <li>
                    <a href="#" title="Fitur ini akan dibuat berikutnya">
                        <i class="fa-solid fa-calendar-check menu-icon"></i>
                        Booking Masuk
                    </a>
                </li>

                <li>
                    <a a href="{{ route('pemilik.booking.index') }}">
                        <i class="fa-solid fa-calendar-check menu-icon"></i>
                        Manajemen Booking
                    </a>
                </li>

                <li>
                    <a href="#" title="Fitur ini akan dibuat berikutnya">
                        <i class="fa-solid fa-user menu-icon"></i>
                        Profil
                    </a>
                </li>
            </ul>

            <div class="owner-sidebar-bottom">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="owner-logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        &nbsp; Keluar
                    </button>
                </form>
            </div>

        </aside>

        {{-- KONTEN UTAMA --}}
        <main class="owner-main">

            {{-- TOPBAR --}}
            <header class="owner-topbar">
                <div class="owner-topbar-title">
                    Dashboard Pemilik Kos
                </div>

                <div class="owner-profile">
                    <div class="owner-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div>
                        <div class="owner-profile-name">
                            {{ auth()->user()->name }}
                        </div>

                        <div class="owner-profile-role">
                            Pemilik Kos
                        </div>
                    </div>
                </div>
            </header>

            <div class="owner-content">

                {{-- BANNER --}}
                <section class="owner-banner">
                    <div>
                        <div class="owner-banner-label">
                            DASHBOARD PEMILIK
                        </div>

                        <h1>Selamat datang,
                            {{ auth()->user()->name }}!
                        </h1>

                        <p>
                            Kelola informasi kos dan pantau booking penyewa
                            melalui satu halaman.
                        </p>
                    </div>

                </section>

                {{-- STATISTIK --}}
                <div class="owner-section-heading">
                    <h2>Ringkasan Kos</h2>
                    <p>Informasi singkat mengenai kos yang kamu kelola.</p>
                </div>

                <section class="owner-stats">

                    <div class="owner-stat-card">
                        <div class="owner-stat-icon stat-blue">
                            <i class="fa-solid fa-building"></i>
                        </div>

                        <div>
                            <div class="owner-stat-label">Jumlah Kos</div>
                            <div class="owner-stat-number">0</div>
                            <div class="owner-stat-note">Kos yang dikelola</div>
                        </div>
                    </div>

                    <div class="owner-stat-card">
                        <div class="owner-stat-icon stat-cyan">
                            <i class="fa-solid fa-bed">0</i>
                        </div>

                        <div>
                            <div class="owner-stat-label">Jumlah Kamar</div>
                            <div class="owner-stat-number">0</div>
                            <div class="owner-stat-note">Total kamar terdaftar</div>
                        </div>
                    </div>

                    <div class="owner-stat-card">
                        <div class="owner-stat-icon stat-orange">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>

                        <div>
                            <div class="owner-stat-label">Booking Masuk</div>
                            <div class="owner-stat-number">0</div>
                            <div class="owner-stat-note">Perlu diperiksa</div>
                        </div>
                    </div>

                    <div class="owner-stat-card">
                        <div class="owner-stat-icon stat-green">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>

                        <div>
                            <div class="owner-stat-label">Kos Aktif</div>
                            <div class="owner-stat-number">0</div>
                            <div class="owner-stat-note">Kos yang tersedia</div>
                        </div>
                    </div>

                </section>

                {{-- TABEL DATA KOS --}}
                <section class="owner-panel">

                    <div class="owner-panel-header">
                        <div>
                            <h2>Data Kos Saya</h2>
                            <p>Daftar kos yang kamu kelola.</p>
                        </div>

                        <button type="button" class="owner-button"
                            onclick="window.location.href='{{ route('pemilik.kos.create') }}'">
                            <i class="fa-solid fa-plus"></i>
                            Tambah Kos
                        </button>
                    </div>

                    <div class="owner-table-wrap">
                        <table class="owner-table">
                            <thead>
                                <tr>
                                    <th>Nama Kos</th>
                                    <th>Lokasi</th>
                                    <th>Tipe Kos</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($kos as $item)
                                    <tr>
                                        <td>{{ $item->nama_kos }}</td>
                                        <td>{{ $item->alamat }}</td>
                                        <td>{{ $item->kategori }}</td>
                                        <td>
                                            @if ($item->tersedia)
                                                <span>Tersedia</span>
                                            @else
                                                <span>Tidak tersedia</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 40px;">
                                            Belum ada data kos. Silakan tambahkan kos terlebih dahulu.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </section>

                <div class="owner-footer-note">
                    SewaKos &copy; {{ date('Y') }} — Dashboard Pemilik Kos
                </div>

            </div>
        </main>
    </div>

</body>

</html>
