<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengguna - SewaKos</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin/user.css') }}">
</head>

<body>
    <div class="container">
        <a href="{{ route('admin.user.index') }}" class="back-link">&larr; Kembali ke Daftar Pengguna</a>

        <div class="card">
            <h1>{{ $user->name }}</h1>
            <p class="email">{{ $user->email }}</p>

            <div class="detail-row">
                <div class="label">ID Pengguna</div>
                <div class="value">#{{ $user->id }}</div>
            </div>

            <div class="detail-row">
                <div class="label">Role</div>
                <div class="value">
                    <span class="role-badge role-{{ $user->role }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>

            <div class="detail-row">
                <div class="label">Bergabung Sejak</div>
                <div class="value">{{ $user->created_at->format('d F Y, H:i') }} WIB</div>
            </div>

            <div class="detail-row">
                <div class="label">Terakhir Diperbarui</div>
                <div class="value">{{ $user->updated_at->diffForHumans() }}</div>
            </div>

            @if ($user->role === 'pemilik')
                <div class="detail-row">
                    <div class="label">Total Kos Didaftarkan</div>
                    <div class="value">{{ $totalKos }} Kos</div>
                </div>
            @endif

            @if ($user->role === 'pencari')
                <div class="detail-row">
                    <div class="label">Total Booking</div>
                    <div class="value">{{ $totalBooking }} Transaksi</div>
                </div>
            @endif
        </div>
    </div>
</body>

</html>
