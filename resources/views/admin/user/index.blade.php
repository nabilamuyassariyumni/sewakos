<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna - SewaKos</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/user.css') }}">
</head>

<body>
    <div class="container">
        <h1>Manajemen Pengguna</h1>
        <p class="subtitle">Kelola seluruh data pengguna SewaKos.</p>

        <!-- Tab Filter Role -->
        <div class="tabs">
            <a href="{{ route('admin.user.index') }}" class="tab {{ !$role ? 'active' : '' }}">
                Semua <span class="badge">{{ $jumlahPencari + $jumlahPemilik + $jumlahAdmin }}</span>
            </a>
            <a href="{{ route('admin.user.index', ['role' => 'pencari']) }}"
                class="tab {{ $role == 'pencari' ? 'active' : '' }}">
                Pencari <span class="badge">{{ $jumlahPencari }}</span>
            </a>
            <a href="{{ route('admin.user.index', ['role' => 'pemilik']) }}"
                class="tab {{ $role == 'pemilik' ? 'active' : '' }}">
                Pemilik <span class="badge">{{ $jumlahPemilik }}</span>
            </a>
            <a href="{{ route('admin.user.index', ['role' => 'admin']) }}"
                class="tab {{ $role == 'admin' ? 'active' : '' }}">
                Admin <span class="badge">{{ $jumlahAdmin }}</span>
            </a>
        </div>

        <!-- Tabel Pengguna -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge role-{{ $user->role }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.user.show', $user->id) }}" class="btn-detail">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty">Belum ada pengguna dengan role ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="pagination">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</body>

</html>
