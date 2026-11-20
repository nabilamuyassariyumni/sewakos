<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - SewaKos</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F4F7FF;
            color: #15336F;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #FFFFFF;
            padding: 30px 20px;
            border-right: 1px solid #E2E8F0;
        }

        .logo {
            font-size: 25px;
            font-weight: 800;
            color: #2166F3;
            margin-bottom: 45px;
        }

        .menu-title {
            font-size: 11px;
            color: #94A3B8;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .menu a {
            display: block;
            text-decoration: none;
            padding: 13px 15px;
            margin-bottom: 8px;
            border-radius: 10px;
            color: #718096;
            font-size: 13px;
            font-weight: 600;
        }

        .menu a.active,
        .menu a:hover {
            background: #DCE9FF;
            color: #2166F3;
        }

        /* Main Content */
        .main {
            margin-left: 250px;
            padding: 30px 35px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .navbar h2 {
            font-size: 23px;
            font-weight: 800;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            font-weight: 700;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #DCE9FF;
            color: #2166F3;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        /* Welcome */
        .welcome {
            background: #2166F3;
            color: white;
            padding: 28px;
            border-radius: 16px;
            margin-bottom: 28px;
        }

        .welcome h2 {
            font-size: 21px;
            margin-bottom: 8px;
        }

        .welcome p {
            font-size: 13px;
            opacity: .9;
        }

        /* Statistik */
        .section-title {
            font-size: 17px;
            font-weight: 800;
            margin-bottom: 18px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 22px;
            border-radius: 14px;
            border: 1px solid #E2E8F0;
        }

        .stat-card p {
            font-size: 12px;
            color: #718096;
            margin-bottom: 13px;
        }

        .stat-card h2 {
            font-size: 28px;
            font-weight: 800;
            color: #15336F;
        }

        .stat-card small {
            display: block;
            font-size: 11px;
            color: #94A3B8;
            margin-top: 8px;
        }

        /* Tabel */
        .table-container {
            background: white;
            border-radius: 14px;
            padding: 22px;
            border: 1px solid #E2E8F0;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            padding: 15px 12px;
            text-align: left;
            border-bottom: 1px solid #E2E8F0;
            font-size: 12px;
        }

        th {
            color: #718096;
            font-weight: 700;
        }

        td {
            color: #15336F;
        }

        .status {
            background: #FFF4D6;
            color: #B7791F;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .btn-detail {
            display: inline-block;
            background: #DCE9FF;
            color: #2166F3;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 700;
        }

        .btn-detail:hover {
            background: #2166F3;
            color: white;
        }

        .btn-logout {
            border: none;
            background: #FFF0F0;
            color: #DC4545;
            padding: 12px 15px;
            border-radius: 9px;
            cursor: pointer;
            width: 100%;
            text-align: left;
            font-family: inherit;
            font-weight: 700;
            margin-top: 35px;
        }

        .notification {
            background: #FFF4D6;
            color: #B7791F;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 10px;
            margin-left: 5px;
        }

        @media (max-width: 1000px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 650px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }

            .stats {
                grid-template-columns: 1fr;
            }
        }

        /* Notifikasi pada Navbar */
        .notification-link {
            position: relative;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            background: #F4F7FF;
            color: #2166F3;
            border-radius: 50%;
            font-size: 20px;
            transition: 0.2s;
        }

        .notification-link:hover {
            background: #DCE9FF;
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #E53935;
            color: white;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            border: 2px solid white;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <aside class="sidebar">

        <div class="logo">SewaKos.</div>

        <p class="menu-title">MENU UTAMA</p>

        <nav class="menu">

            <a href="{{ route('dashboard.admin') }}" class="active">
                Dashboard
            </a>

            <a href="{{ route('admin.kos.index') }}">
                Verifikasi Kos

                @if ($kosMenunggu > 0)
                    <span class="notification">
                        {{ $kosMenunggu }}
                    </span>
                @endif
            </a>

        </nav>

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit" class="btn-logout">
                Logout
            </button>
        </form>

    </aside>

    <!-- Konten Utama -->
    <main class="main">

        <!-- Navbar -->
        <div class="navbar">

            <h2>Dashboard Admin</h2>

            <div class="admin-info">

                <!-- Ikon Notifikasi -->
                <a href="{{ route('admin.notifikasi.index') }}" class="notification-link" title="Notifikasi">

                    🔔

                    @if (Auth::user()->unreadNotifications()->count() > 0)
                        <span class="notification-badge">
                            {{ Auth::user()->unreadNotifications()->count() }}
                        </span>
                    @endif

                </a>

                <!-- Profil Admin -->
                <div class="avatar">A</div>

                <span>{{ Auth::user()->name }}</span>

            </div>

        </div>

        <!-- Sambutan -->
        <section class="welcome">

            <h2>Selamat Datang, Admin!</h2>

            <p>
                Kelola pengguna, data kos, dan permintaan verifikasi
                melalui dashboard SewaKos.
            </p>

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

            <div class="stat-card">
                <p>Kos Disetujui</p>
                <h2>{{ $kosDisetujui }}</h2>
                <small>Kos yang sudah dapat ditampilkan</small>
            </div>

        </section>

        <!-- Pengajuan Terbaru -->
        <section class="table-container">

            <h3 class="section-title">
                Pengajuan Verifikasi Terbaru
            </h3>

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

                            <td>
                                {{ $item->pemilik->name ?? '-' }}
                            </td>

                            <td>{{ $item->alamat }}</td>

                            <td>
                                <span class="status">
                                    Menunggu
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('admin.kos.show', $item->id) }}" class="btn-detail">
                                    Periksa
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" style="text-align:center;">
                                Belum ada pengajuan verifikasi kos.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </section>

    </main>

</body>

</html>
