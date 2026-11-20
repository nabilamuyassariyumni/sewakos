<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Notifications\PengajuanKosBaru;
use Illuminate\Support\Facades\Notification;
use App\Models\User;


class KosController extends Controller
{
    // Menampilkan kos milik pemilik yang sedang login
    public function index()
    {
        $kos = Kos::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pemilik.kos.index', compact('kos'));
    }

    // Menampilkan rekomendasi pencarian kos
    public function rekomendasi(Request $request)
    {
        $keyword = $request->input('keyword');

        if (!$keyword) {
            return response()->json([]);
        }

        $rekomendasi = Kos::query()
            // Hanya menampilkan kos yang sudah disetujui Admin
            ->where('status_verifikasi', 'disetujui')
            ->where(function ($query) use ($keyword) {
                $query->where('nama_kos', 'like', "%{$keyword}%")
                    ->orWhere('alamat', 'like', "%{$keyword}%");
            })
            ->select('nama_kos')
            ->distinct()
            ->limit(5)
            ->pluck('nama_kos');

        return response()->json($rekomendasi);
    }

    // Menampilkan daftar kos untuk pencari kos
    public function cari(Request $request)
    {
        // Hanya mengambil kos yang sudah disetujui Admin
        $query = Kos::query()
            ->where('status_verifikasi', 'disetujui');

        // Pencarian berdasarkan nama kos atau alamat
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('nama_kos', 'like', "%{$keyword}%")
                    ->orWhere('alamat', 'like', "%{$keyword}%");
            });
        }

        // Filter harga maksimal
        if ($request->filled('harga_max')) {
            $query->where('harga', '<=', $request->harga_max);
        }

        // Filter kategori kos
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter fasilitas
        if ($request->filled('fasilitas')) {
            $fasilitas = $request->fasilitas;

            $query->where(
                'fasilitas',
                'like',
                "%{$fasilitas}%"
            );
        }

        // Filter kos yang masih tersedia
        if ($request->boolean('tersedia')) {
            $query->where('tersedia', true);
        }

        // Mengambil hasil pencarian
        $kos = $query->latest()->paginate(9)->withQueryString();

        return view('dashboard-pencari', compact('kos'));
    }


    // Menampilkan formulir tambah kos
    public function create()
    {
        return view('pemilik.kos.create');
    }

    // Menyimpan kos baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kos' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'harga' => ['required', 'integer', 'min:1'],
            'kategori' => [
                'required',
                Rule::in(['Putra', 'Putri', 'Campur', 'Pasutri/Keluarga']),
            ],
            'deskripsi' => ['nullable', 'string'],
            'foto' => [
                'nullable',
                'file',
                'max:2048',
                function ($attribute, $value, $fail) {

                    // Ekstensi gambar yang diperbolehkan
                    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

                    $extension = strtolower(
                        $value->getClientOriginalExtension()
                    );

                    if (!in_array($extension, $allowed)) {
                        $fail('Format foto harus JPG, JPEG, PNG, atau WEBP.');
                        return;
                    }

                    // Memeriksa struktur gambar tanpa fileinfo
                    if (@getimagesize($value->getPathname()) === false) {
                        $fail('File yang diunggah bukan gambar yang valid.');
                    }
                },
            ],
            'fasilitas' => ['nullable', 'string'],
            'aturan' => ['nullable', 'string'],
            'jumlah_kamar' => ['required', 'integer', 'min:1'],
            'tersedia' => ['required', 'boolean'],
        ]);

        $data['user_id'] = Auth::id();

        // Setiap kos baru harus menunggu verifikasi Admin
        $data['status_verifikasi'] = 'menunggu';

        // Menyimpan foto tanpa menggunakan deteksi MIME Laravel
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');

            // Membuat nama file unik
            $namaFile = Str::uuid() . '.' .
                strtolower($foto->getClientOriginalExtension());

            // Menentukan folder penyimpanan
            $folderFoto = storage_path('app/public/foto-kos');

            // Membuat folder jika belum tersedia
            if (!is_dir($folderFoto)) {
                mkdir($folderFoto, 0755, true);
            }

            // Memindahkan file foto secara langsung
            $foto->move($folderFoto, $namaFile);

            // Menyimpan lokasi foto ke database
            $data['foto'] = 'foto-kos/' . $namaFile;
        }

        // Menyimpan data kos
        $kos = Kos::create($data);

        // Mengambil seluruh akun Admin
        $admin = User::where('role', 'admin')->get();

        // Mengirim notifikasi kepada seluruh Admin
        Notification::send(
            $admin,
            new PengajuanKosBaru($kos)
        );

        return redirect()
            ->route('pemilik.kos.index')
            ->with('success', 'Data kos berhasil ditambahkan dan menunggu verifikasi Admin.');
    }

    // Menampilkan formulir edit kos
    public function edit(Kos $ko)
    {
        $this->pastikanPemilik($ko);

        return view('pemilik.kos.edit', ['kos' => $ko]);
    }

    // Memperbarui data kos
    public function update(Request $request, Kos $ko)
    {
        $this->pastikanPemilik($ko);

        $data = $request->validate([
            'nama_kos' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'harga' => ['required', 'integer', 'min:1'],
            'kategori' => [
                'required',
                Rule::in(['Putra', 'Putri', 'Campur', 'Pasutri/Keluarga']),
            ],
            'deskripsi' => ['nullable', 'string'],
            'foto' => [
                'nullable',
                'file',
                'max:2048',
                function ($attribute, $value, $fail) {
                    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

                    $extension = strtolower(
                        $value->getClientOriginalExtension()
                    );

                    if (!in_array($extension, $allowed)) {
                        $fail('Format foto harus JPG, JPEG, PNG, atau WEBP.');
                        return;
                    }

                    if (@getimagesize($value->getPathname()) === false) {
                        $fail('File yang diunggah bukan gambar yang valid.');
                    }
                },
            ],
            'fasilitas' => ['nullable', 'string'],
            'aturan' => ['nullable', 'string'],
            'jumlah_kamar' => ['required', 'integer', 'min:1'],
            'tersedia' => ['required', 'boolean'],
        ]);

        // Mengganti foto jika pengguna mengunggah foto baru
        if ($request->hasFile('foto')) {

            // Menghapus foto lama
            if ($ko->foto) {
                Storage::disk('public')->delete($ko->foto);
            }

            $foto = $request->file('foto');

            // Membuat nama file unik
            $namaFile = Str::uuid() . '.' .
                strtolower($foto->getClientOriginalExtension());

            // Menentukan folder penyimpanan
            $folderFoto = storage_path('app/public/foto-kos');

            // Membuat folder jika belum tersedia
            if (!is_dir($folderFoto)) {
                mkdir($folderFoto, 0755, true);
            }

            // Memindahkan file foto secara langsung
            $foto->move($folderFoto, $namaFile);

            // Memperbarui lokasi foto di database
            $data['foto'] = 'foto-kos/' . $namaFile;
        }
        // Jangan izinkan user_id diganti dari formulir
        unset($data['user_id']);

        // Kos yang diperbarui harus diverifikasi ulang oleh Admin
        $data['status_verifikasi'] = 'menunggu';

        $ko->update($data);

        return redirect()
            ->route('pemilik.kos.index')
            ->with('success', 'Data kos berhasil diperbarui.');
    }

    // Menghapus data kos
    public function destroy(Kos $ko)
    {
        $this->pastikanPemilik($ko);

        if ($ko->foto) {
            Storage::disk('public')->delete($ko->foto);
        }

        $ko->delete();

        return redirect()
            ->route('pemilik.kos.index')
            ->with('success', 'Data kos berhasil dihapus.');
    }

    // Memastikan kos hanya dikelola pemiliknya
    private function pastikanPemilik(Kos $ko): void
    {
        abort_unless(
            $ko->user_id === Auth::id(),
            403,
            'Kamu tidak memiliki akses ke data kos ini.'
        );
    }
}
