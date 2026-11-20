
@csrf

<div class="kos-form-grid">
    <div class="kos-field">
        <label for="nama_kos">Nama Kos</label>
        <input
            id="nama_kos"
            type="text"
            name="nama_kos"
            value="{{ old('nama_kos', $kos->nama_kos ?? '') }}"
            required
        >
        @error('nama_kos') <small>{{ $message }}</small> @enderror
    </div>

    <div class="kos-field">
        <label for="harga">Harga per Bulan (Rp)</label>
        <input
            id="harga"
            type="number"
            name="harga"
            min="1"
            value="{{ old('harga', $kos->harga ?? '') }}"
            required
        >
        @error('harga') <small>{{ $message }}</small> @enderror
    </div>

    <div class="kos-field">
        <label for="kategori">Kategori Kos</label>
        <select id="kategori" name="kategori" required>
            @foreach (['Putra', 'Putri', 'Campur', 'Pasutri/Keluarga'] as $kategori)
                <option
                    value="{{ $kategori }}"
                    @selected(old('kategori', $kos->kategori ?? '') === $kategori)
                >
                    {{ $kategori }}
                </option>
            @endforeach
        </select>
        @error('kategori') <small>{{ $message }}</small> @enderror
    </div>

    <div class="kos-field">
        <label for="jumlah_kamar">Jumlah Kamar</label>
        <input
            id="jumlah_kamar"
            type="number"
            name="jumlah_kamar"
            min="1"
            value="{{ old('jumlah_kamar', $kos->jumlah_kamar ?? 1) }}"
            required
        >
        @error('jumlah_kamar') <small>{{ $message }}</small> @enderror
    </div>

    <div class="kos-field kos-field-full">
        <label for="alamat">Alamat Lengkap</label>
        <textarea id="alamat" name="alamat" rows="3" required>{{ old('alamat', $kos->alamat ?? '') }}</textarea>
        @error('alamat') <small>{{ $message }}</small> @enderror
    </div>

    <div class="kos-field kos-field-full">
        <label for="deskripsi">Deskripsi Kos</label>
        <textarea id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $kos->deskripsi ?? '') }}</textarea>
        @error('deskripsi') <small>{{ $message }}</small> @enderror
    </div>

    <div class="kos-field kos-field-full">
        <label for="fasilitas">Fasilitas</label>
        <textarea
            id="fasilitas"
            name="fasilitas"
            rows="2"
            placeholder="Contoh: Wi-Fi, parkir motor, kamar mandi dalam"
        >{{ old('fasilitas', $kos->fasilitas ?? '') }}</textarea>
        @error('fasilitas') <small>{{ $message }}</small> @enderror
    </div>

    <div class="kos-field kos-field-full">
        <label for="aturan">Aturan Kos</label>
        <textarea
            id="aturan"
            name="aturan"
            rows="2"
            placeholder="Contoh: Tidak boleh membawa hewan"
        >{{ old('aturan', $kos->aturan ?? '') }}</textarea>
        @error('aturan') <small>{{ $message }}</small> @enderror
    </div>

    <div class="kos-field kos-field-full">
        <label for="foto">Foto Kos (JPG, PNG, WEBP, maksimal 2 MB)</label>
        <input id="foto" type="file" name="foto" accept=".jpg,.jpeg,.png,.webp">

        @if (!empty($kos?->foto))
            <p>Foto saat ini:</p>
            <img
                src="{{ asset('storage/' . $kos->foto) }}"
                alt="Foto kos saat ini"
                class="kos-preview"
            >
        @endif

        @error('foto') <small>{{ $message }}</small> @enderror
    </div>

    <div class="kos-field kos-field-full">
        <label for="tersedia">Status Ketersediaan</label>
        <select id="tersedia" name="tersedia" required>
            <option value="1" @selected((string) old('tersedia', isset($kos) ? (int) $kos->tersedia : 1) === '1')>
                Tersedia
            </option>
            <option value="0" @selected((string) old('tersedia', isset($kos) ? (int) $kos->tersedia : 1) === '0')>
                Tidak tersedia
            </option>
        </select>
        @error('tersedia') <small>{{ $message }}</small> @enderror
    </div>
</div>

<div class="kos-form-actions">
    <a href="{{ route('pemilik.kos.index') }}" class="kos-btn kos-btn-secondary">
        Kembali
    </a>

    <button type="submit" class="kos-btn kos-btn-primary">
        {{ isset($kos) ? 'Simpan Perubahan' : 'Simpan Data Kos' }}
    </button>
</div>
