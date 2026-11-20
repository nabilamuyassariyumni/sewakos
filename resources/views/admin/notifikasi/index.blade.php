<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Notifikasi Admin - SewaKos</title>

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

        /* Konten Utama */
        .main {
            margin-left: 250px;
            padding: 30px 35px;
        }

        /* Navbar */
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

        /* Judul Halaman */
        .page-description {
            color: #718096;
            font-size: 13px;
            margin-bottom: 25px;
        }

        /* Daftar Notifikasi */
        .notification-container {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            padding: 24px;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .notification-header h3 {
            font-size: 16px;
            font-weight: 800;
        }

        .notification-count {
            font-size: 11px;
            color: #2166F3;
            background: #DCE9FF;
            padding: 7px 12px;
            border-radius: 20px;
            font-weight: 700;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 20px 15px;
            border-bottom: 1px solid #E2E8F0;
            text-decoration: none;
            color: inherit;
            transition: 0.2s;
            border-radius: 9px;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background: #F4F7FF;
        }

        .notification-item.unread {
            background: #F4F8FF;
        }

        .notification-icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #DCE9FF;
            color: #2166F3;
            border-radius: 12px;
            font-size: 19px;
        }

        .notification-content {
            flex: 1;
        }

        .notification-content h4 {
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .notification-content p {
            font-size: 12px;
            color: #718096;
            line-height: 1.7;
            margin-bottom: 8px;
        }

        .notification-time {
            font-size: 10px;
            color: #94A3B8;
        }

        .unread-label {
            display: inline-block;
            background: #E53935;
            color: white;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
            margin-top: 8px;
        }

        .read-label {
            display: inline-block;
            background: #E8F7EF;
            color: #16A765;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
            margin-top: 8px;
        }

        /* Notifikasi Kosong */
        .empty-notification {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-notification .icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .empty-notification h3 {
            font-size: 16px;
            margin-bottom: 8px;
        }

        .empty-notification p {
            font-size: 12px;
            color: #94A3B8;
        }

        /* Pagination */
        .pagination {
            margin-top: 20px;
        }

        .pagination nav {
            display: flex;
            justify-content: center;
        }

        .pagination a,
        .pagination span {
            font-size: 12px;
        }

        /* Tombol Kembali */
        .btn-back {
            display: inline-block;
            margin-top: 22px;
            padding: 11px 17px;
            background: #2166F3;
            color: white;
            text-decoration: none;
            border-radius: 9px;
            font-size: 12px;
            font-weight: 700;
        }

        .btn-back:hover {
            background: #15336F;
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

            .navbar {
                gap: 15px;
            }

            .notification-container {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <aside class="sidebar">

        <div class="logo">SewaKos.</div>

        <p class="menu-title">MENU UTAMA</p>

        <nav class="menu">

            <a href="{{ route('dashboard.admin') }}">
                Dashboard
            </a>

            <a href="{{ route('admin.kos.index') }}">
                Verifikasi Kos

                @if ($kosMenunggu = \App\Models\Kos::where('status_verifikasi', 'menunggu')->count())
                    <span
                        style="background:#FFF4D6;color:#B7791F;padding:3px 8px;border-radius:20px;font-size:10px;margin-left:5px;">
                        {{ $kosMenunggu }}
                    </span>
                @endif
            </a>

            <a href="{{ route('admin.notifikasi.index') }}" class="active">
                Notifikasi
            </a>

        </nav>

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit"
                style="border:none;background:#FFF0F0;color:#DC4545;padding:12px 15px;border-radius:9px;cursor:pointer;width:100%;text-align:left;font-family:inherit;font-weight:700;margin-top:35px;">
                Logout
            </button>
        </form>

    </aside>

    <!-- Konten Utama -->
    <main class="main">

        <!-- Navbar -->
        <div class="navbar">

            <h2>Notifikasi</h2>

            <div class="admin-info">

                <div class="avatar">A</div>

                <span>{{ Auth::user()->name }}</span>

            </div>

        </div>

        <p class="page-description">
            Lihat informasi dan pemberitahuan terbaru mengenai pengajuan kos.
        </p>

        <!-- Daftar Notifikasi -->
        <section class="notification-container">

            <div class="notification-header">

                <h3>Semua Notifikasi</h3>

                <span class="notification-count">
                    {{ Auth::user()->unreadNotifications()->count() }} Belum Dibaca
                </span>

            </div>

            @forelse ($notifikasi as $item)
                <a href="{{ route('admin.notifikasi.baca', $item->id) }}"
                    class="notification-item {{ $item->read_at === null ? 'unread' : '' }}">

                    <div class="notification-icon">
                        🔔
                    </div>

                    <div class="notification-content">

                        <h4>
                            {{ $item->data['judul'] ?? 'Notifikasi Baru' }}
                        </h4>

                        <p>
                            {{ $item->data['pesan'] ?? 'Ada pemberitahuan baru untuk Admin.' }}
                        </p>

                        <span class="notification-time">
                            {{ $item->created_at->format('d M Y, H:i') }}
                        </span>

                        <br>

                        @if ($item->read_at === null)
                            <span class="unread-label">
                                Belum Dibaca
                            </span>
                        @else
                            <span class="read-label">
                                Sudah Dibaca
                            </span>
                        @endif

                    </div>

                </a>

            @empty

                <div class="empty-notification">

                    <div class="icon">🔔</div>

                    <h3>Belum Ada Notifikasi</h3>

                    <p>
                        Notifikasi pengajuan kos akan muncul di halaman ini.
                    </p>

                </div>
            @endforelse

            <!-- Pagination -->
            @if ($notifikasi->hasPages())
                <div class="pagination">
                    {{ $notifikasi->links() }}
                </div>
            @endif

        </section>

        <a href="{{ route('dashboard.admin') }}" class="btn-back">
            ← Kembali ke Dashboard
        </a>

    </main>

</body>

</html>
