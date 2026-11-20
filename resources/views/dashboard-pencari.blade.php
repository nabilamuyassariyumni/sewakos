<x-app-layout>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/pencari/dashboard-pencari.css') }}">

    <div class="pencari-dashboard">

        {{-- NAVBAR --}}
        <nav class="sewa-navbar">
            <a href="{{ route('dashboard.pencari') }}" class="sewa-logo">
                <i class="fa-solid fa-house-chimney"></i>
                SewaKos
            </a>

            <div class="sewa-nav-menu">
                <a href="{{ route('dashboard.pencari') }}" class="active">
                    <i class="fa-solid fa-house"></i> Beranda
                </a>

                <a href="#">
                    <i class="fa-regular fa-heart"></i> Favorit
                </a>

                <a href="#">
                    <i class="fa-regular fa-bell"></i> Notifikasi
                </a>
                <a href="{{ route('pencari.booking.index') }}">
                    Riwayat Booking
                </a>
            </div>

            <div class="sewa-profile">
                <div class="profile-icon">
                    <i class="fa-solid fa-user"></i>
                </div>

                <span>{{ Auth::user()->name }}</span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-button" title="Keluar">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </nav>

        <main class="sewa-container">

            {{-- HERO DAN PENCARIAN --}}
            <section class="welcome-banner">
                <div class="welcome-content">

                    <p class="welcome-greeting">
                        Halo, {{ Auth::user()->name }}
                    </p>

                    <h1>
                        Temukan Kos Impianmu
                        <span>di SewaKos</span>
                    </h1>

                    <p class="welcome-description">
                        Cari kos terbaik dengan mudah, cepat, dan terpercaya
                        untuk kebutuhan tempat tinggalmu.
                    </p>

                    {{-- KOLOM PENCARIAN TEPAT DI BAWAH DESKRIPSI --}}
                    <form action="{{ route('pencari.kos.index') }}" method="GET" class="hero-search-form"
                        id="searchForm">

                        <i class="fas fa-search"></i>

                        <div class="search-autocomplete">

                            <input type="text" name="keyword" id="searchInput"
                                placeholder="Cari nama kos atau lokasi..." value="{{ request('keyword') }}"
                                autocomplete="off">

                            <div class="autocomplete-dropdown" id="autocompleteDropdown">

                                <div id="autocompleteResults"></div>

                            </div>

                        </div>

                        <button type="submit">
                            Cari Kos
                        </button>

                    </form>

                </div>

                <div class="welcome-illustration">
                    <i class="fa-solid fa-building"></i>
                </div>
            </section>

            {{-- FILTER DAN DAFTAR KOS --}}
            <section class="search-layout">

                {{-- SIDEBAR FILTER --}}
                <aside class="filter-panel">

                    <div class="filter-panel-heading">
                        <h2>
                            <i class="fa-solid fa-sliders"></i>
                            Filter Pencarian
                        </h2>

                        <a href="{{ route('pencari.kos.index') }}">
                            Reset
                        </a>
                    </div>

                    <form action="{{ route('pencari.kos.index') }}" method="GET">

                        {{-- Mempertahankan kata pencarian --}}
                        <input type="hidden" name="keyword" value="{{ request('keyword') }}">

                        <div class="filter-section">
                            <label for="kategori">
                                <i class="fa-solid fa-house"></i>
                                Kategori Kos
                            </label>

                            <select name="kategori" id="kategori">
                                <option value="">Semua Kategori</option>

                                <option value="Putra" {{ request('kategori') == 'Putra' ? 'selected' : '' }}>
                                    Putra
                                </option>

                                <option value="Putri" {{ request('kategori') == 'Putri' ? 'selected' : '' }}>
                                    Putri
                                </option>

                                <option value="Campur" {{ request('kategori') == 'Campur' ? 'selected' : '' }}>
                                    Campur
                                </option>

                                <option value="Pasutri/Keluarga"
                                    {{ request('kategori') == 'Pasutri/Keluarga' ? 'selected' : '' }}>
                                    Pasutri/Keluarga
                                </option>
                            </select>
                        </div>

                        <div class="filter-section">
                            <label for="harga_max">
                                <i class="fa-solid fa-tag"></i>
                                Harga Maksimal per Bulan
                            </label>

                            <input type="number" name="harga_max" id="harga_max" min="1"
                                value="{{ request('harga_max') }}" placeholder="Contoh: 1000000">
                        </div>

                        <div class="filter-section">
                            <label for="fasilitas">
                                <i class="fa-solid fa-star"></i>
                                Fasilitas
                            </label>

                            <input type="text" name="fasilitas" id="fasilitas" value="{{ request('fasilitas') }}"
                                placeholder="Contoh: Wi-Fi">
                        </div>

                        <div class="filter-availability">
                            <label>
                                <input type="checkbox" name="tersedia" value="1"
                                    {{ request('tersedia') ? 'checked' : '' }}>

                                Hanya kos yang tersedia
                            </label>
                        </div>

                        <button type="submit" class="apply-filter-button">
                            <i class="fa-solid fa-filter"></i>
                            Terapkan Filter
                        </button>

                    </form>
                </aside>

                {{-- DAFTAR KOS --}}
                <div class="results-panel">

                    <div class="results-heading">
                        <div>
                            <h2>Daftar Kos</h2>
                            <p>
                                @if (method_exists($kos, 'total'))
                                    Menampilkan {{ $kos->count() }}
                                    dari {{ $kos->total() }} kos
                                @else
                                    Menampilkan {{ $kos->count() }} kos
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="listing-grid">

                        @forelse ($kos as $item)
                            <article class="listing-card">

                                <div class="listing-image">

                                    @if ($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_kos }}">
                                    @else
                                        <div class="listing-no-image">
                                            <i class="fa-solid fa-house"></i>
                                            Foto belum tersedia
                                        </div>
                                    @endif

                                    <span class="listing-category">
                                        {{ $item->kategori }}
                                    </span>

                                    <button type="button" class="listing-favorite" aria-label="Favorit">
                                        <i class="fa-regular fa-heart"></i>
                                    </button>

                                </div>

                                <div class="listing-content">

                                    <h3>{{ $item->nama_kos }}</h3>

                                    <p class="listing-location">
                                        <i class="fa-solid fa-location-dot"></i>
                                        {{ $item->alamat }}
                                    </p>

                                    <p class="listing-price">
                                        Rp{{ number_format($item->harga, 0, ',', '.') }}
                                        <small>/bulan</small>
                                    </p>

                                    <div class="listing-facility">
                                        <i class="fa-solid fa-bed"></i>
                                        {{ $item->jumlah_kamar }} Kamar

                                        @if ($item->fasilitas)
                                            <span>
                                                <i class="fa-solid fa-wifi"></i>
                                                {{ $item->fasilitas }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="listing-footer">
                                        @if ($item->tersedia)
                                            <span class="listing-status available">
                                                Tersedia
                                            </span>
                                        @else
                                            <span class="listing-status unavailable">
                                                Tidak Tersedia
                                            </span>
                                        @endif

                                        {{-- Mengarahkan pengguna ke halaman detail sesuai ID kost. --}}
                                        <a href="{{ route('pencari.kos.detail', $item->id) }}"
                                            class="listing-detail">

                                            Detail
                                            <i class="fa-solid fa-arrow-right"></i>

                                        </a>
                                    </div>

                                </div>
                            </article>

                        @empty

                            <div class="listing-empty">
                                <i class="fa-solid fa-house-circle-xmark"></i>
                                <h3>Kos Tidak Ditemukan</h3>
                                <p>
                                    Belum ada kos yang sesuai dengan
                                    pencarian atau filter kamu.
                                </p>

                                <a href="{{ route('pencari.kos.index') }}">
                                    Tampilkan Semua Kos
                                </a>
                            </div>
                        @endforelse

                    </div>

                    @if (method_exists($kos, 'links'))
                        <div class="listing-pagination">
                            {{ $kos->links() }}
                        </div>
                    @endif

                </div>
            </section>

        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const searchInput = document.getElementById('searchInput');
            const dropdown = document.getElementById('autocompleteDropdown');
            const results = document.getElementById('autocompleteResults');
            const searchForm = document.getElementById('searchForm');

            let timer;

            searchInput.addEventListener('input', function() {

                clearTimeout(timer);

                const keyword = this.value.trim();

                if (keyword.length === 0) {
                    dropdown.style.display = 'none';
                    results.innerHTML = '';
                    return;
                }

                timer = setTimeout(async function() {

                    try {

                        const url = "{{ route('pencari.kos.rekomendasi') }}" +
                            "?keyword=" + encodeURIComponent(keyword);

                        const response = await fetch(url);

                        if (!response.ok) {
                            throw new Error('Gagal mengambil rekomendasi');
                        }

                        const data = await response.json();

                        results.innerHTML = '';

                        if (data.length === 0) {

                            results.innerHTML = `
                        <div class="autocomplete-empty">
                            Tidak ada rekomendasi pencarian
                        </div>
                    `;

                        } else {

                            data.slice(0, 5).forEach(function(namaKos) {

                                const item = document.createElement('div');

                                item.className = 'autocomplete-item';

                                const icon = document.createElement('i');
                                icon.className = 'fas fa-search';

                                const text = document.createElement('span');
                                text.textContent = namaKos;

                                item.appendChild(icon);
                                item.appendChild(text);

                                item.addEventListener('click', function() {

                                    searchInput.value = namaKos;

                                    dropdown.style.display = 'none';

                                    searchForm.submit();

                                });

                                results.appendChild(item);

                            });

                        }

                        dropdown.style.display = 'block';

                    } catch (error) {

                        console.error(error);

                    }

                }, 200);

            });

            // Menutup dropdown ketika klik di luar search bar
            document.addEventListener('click', function(event) {

                if (!searchForm.contains(event.target)) {
                    dropdown.style.display = 'none';
                }

            });

            // Menampilkan kembali dropdown ketika input difokuskan
            searchInput.addEventListener('focus', function() {

                if (this.value.trim().length > 0 &&
                    results.children.length > 0) {

                    dropdown.style.display = 'block';

                }

            });

        });
    </script>
</x-app-layout>
