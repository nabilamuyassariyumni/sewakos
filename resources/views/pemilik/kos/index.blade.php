<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Data Kos
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/kos.css') }}">

    <div class="kos-container">
        @if (session('success'))
            <div class="kos-alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="kos-alert kos-alert-error">
                Data gagal diproses. Periksa kembali formulir.
            </div>
        @endif

        <div class="kos-heading">
            <div>
                <h1>Data Kos Saya</h1>
                <p>Kelola informasi kos yang kamu sewakan.</p>
            </div>

            <a href="{{ route('pemilik.kos.create') }}" class="kos-btn kos-btn-primary">
                + Tambah Kos
            </a>
        </div>

        @forelse ($kos as $item)
            <div class="kos-card">
                @if ($item->foto)
                    <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto {{ $item->nama_kos }}" class="kos-photo">
                @else
                    <div class="kos-photo kos-photo-empty">
                        Foto belum tersedia
                    </div>
                @endif

                <div class="kos-card-content">
                    <h3>{{ $item->nama_kos }}</h3>

                    <p class="kos-price">
                        Rp{{ number_format($item->harga, 0, ',', '.') }}/bulan
                    </p>

                    <p>{{ $item->alamat }}</p>
                    <p>Kategori: {{ $item->kategori }}</p>
                    <p>Jumlah kamar: {{ $item->jumlah_kamar }}</p>

                    {{-- Status ketersediaan kos --}}
                    <p>
                        Status Ketersediaan:
                        @if ($item->tersedia)
                            <span class="kos-status kos-status-available">
                                Tersedia
                            </span>
                        @else
                            <span class="kos-status kos-status-unavailable">
                                Tidak tersedia
                            </span>
                        @endif
                    </p>

                    {{-- Status verifikasi dari admin --}}
                    <p>
                        Status Verifikasi:

                        @if ($item->status_verifikasi == 'menunggu')
                            <span class="verifikasi-status verifikasi-menunggu">
                                Menunggu Verifikasi
                            </span>
                        @elseif ($item->status_verifikasi == 'disetujui')
                            <span class="verifikasi-status verifikasi-disetujui">
                                Disetujui
                            </span>
                        @elseif ($item->status_verifikasi == 'ditolak')
                            <span class="verifikasi-status verifikasi-ditolak">
                                Ditolak
                            </span>
                        @else
                            <span class="verifikasi-status verifikasi-menunggu">
                                Belum Diverifikasi
                            </span>
                        @endif
                    </p>

                    <div class="kos-actions">
                        <a href="{{ route('pemilik.kos.edit', $item) }}" class="kos-btn kos-btn-edit">
                            Edit
                        </a>

                        <form action="{{ route('pemilik.kos.destroy', $item) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus kos ini?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="kos-btn kos-btn-delete">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="kos-empty">
                <h3>Belum Ada Data Kos</h3>
                <p>Tambahkan data kos pertamamu untuk mulai mengelolanya.</p>

                <a href="{{ route('pemilik.kos.create') }}" class="kos-btn kos-btn-primary">
                    Tambah Kos Sekarang
                </a>
            </div>
        @endforelse
    </div>
</x-app-layout>
