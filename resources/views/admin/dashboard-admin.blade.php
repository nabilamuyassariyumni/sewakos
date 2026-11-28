<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SewaKos</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- PANGGIL FILE CSS EKSTERNAL -->
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard-admin.css') }}">
</head>

<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">SewaKos.</div>
        <p class="menu-title">MENU UTAMA</p>
        <nav class="menu">
            <a href="{{ route('dashboard.admin') }}" class="active">Dashboard</a>

            <a href="{{ route('admin.kos.index') }}">
                Verifikasi Kos
                @if ($kosMenunggu > 0)
                    <span class="notification">{{ $kosMenunggu }}</span>
                @endif
            </a>

            <a href="{{ route('admin.user.index') }}">Manajemen Pengguna</a>

            <a href="{{ route('admin.kos.semua') }}">Pengelolaan Kos</a>

            <a href="{{ route('admin.transaksi.index') }}">Riwayat Transaksi</a>
        </nav>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </aside>

    <!-- Konten Utama -->
    <main class="main">
        <!-- Navbar -->
        <div class="navbar">
            <h2>Dashboard Admin</h2>
            <div class="admin-info">
                <a href="{{ route('admin.notifikasi.index') }}" class="notification-link" title="Notifikasi">
                    🔔
                    @if (Auth::user()->unreadNotifications()->count() > 0)
                        <span class="notification-badge">
                            {{ Auth::user()->unreadNotifications()->count() }}
                        </span>
                    @endif
                </a>
                <div class="avatar">A</div>
                <span>{{ Auth::user()->name }}</span>
            </div>
        </div>

        <!-- Sambutan -->
        <section class="welcome">
            <h2>Selamat Datang, Admin!</h2>
            <p>Kelola pengguna, data kos, dan permintaan verifikasi melalui dashboard SewaKos.</p>
        </section>

        <!-- Statistik -->
        <h3 class="section-title">Ringkasan Sistem</h3>
        <section class="stats">
            <div class="stat-card">
                <p>Total Pengguna</p>
                <h2>{{ $totalPengguna }}</h2>
                <small>Seluruh pengguna terdaftar</small>
            </div>
            <div class="stat-card">
                <p>Total Data Kos</p>
                <h2>{{ $totalKos }}</h2>
                <small>Seluruh kos yang didaftarkan</small>
            </div>
            <div class="stat-card">
                <p>Menunggu Verifikasi</p>
                <h2>{{ $kosMenunggu }}</h2>
                <small>Pengajuan yang perlu diperiksa</small>
            </div>

            <!-- CARD BARU: Total Booking -->
            <div class="stat-card">
                <p>Total Booking</p>
                <h2>{{ $totalBooking }}</h2>
                <small>Seluruh transaksi sewa</small>
            </div>
        </section>

        <!-- Pengajuan Terbaru -->
        <section class="table-container">
            <h3 class="section-title">Pengajuan Verifikasi Terbaru</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama Kos</th>
                        <th>Pemilik</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuanTerbaru as $item)
                        <tr>
                            <td>{{ $item->nama_kos }}</td>
                            <td>{{ $item->pemilik->name ?? '-' }}</td>
                            <td>{{ $item->alamat }}</td>
                            <td><span class="status">Menunggu</span></td>
                            <td>
                                <a href="{{ route('admin.kos.show', $item->id) }}" class="btn-detail">Periksa</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;">Belum ada pengajuan verifikasi kos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </main>

</body>

</html>
