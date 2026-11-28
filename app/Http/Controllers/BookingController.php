<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Menyimpan pengajuan sewa dari Pencari.
     */
    public function store(Request $request, int $id)
    {
        // Validasi data pengajuan
        $validated = $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'durasi_bulan' => 'required|integer|min:1|max:24',
            'catatan' => 'nullable|string|max:1000',
        ], [
            'tanggal_mulai.required' => 'Tanggal mulai sewa wajib diisi.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini.',
            'durasi_bulan.required' => 'Durasi sewa wajib diisi.',
            'durasi_bulan.min' => 'Durasi minimal 1 bulan.',
            'durasi_bulan.max' => 'Durasi maksimal 24 bulan.',
        ]);

        // Cari kos yang sudah disetujui dan tersedia
        $kos = Kos::where('id', $id)
            ->where('status_verifikasi', 'disetujui')
            ->where('tersedia', true)
            ->firstOrFail();

        // Cegah pemilik menyewa kos miliknya sendiri
        if ($kos->user_id == Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menyewa kos milik sendiri.');
        }

        // Cegah pengajuan ganda yang masih aktif
        $bookingAktif = Booking::where('kos_id', $kos->id)
            ->where('pencari_id', Auth::id())
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->exists();

        if ($bookingAktif) {
            return back()->with('error', 'Anda sudah memiliki pengajuan aktif untuk kos ini.');
        }

        // Hitung total harga di server
        $totalHarga = $kos->harga * $validated['durasi_bulan'];

        // Simpan data booking
        $booking = Booking::create([
            'kos_id' => $kos->id,
            'pencari_id' => Auth::id(),
            'pemilik_id' => $kos->user_id,
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'durasi_bulan' => $validated['durasi_bulan'],
            'total_harga' => $totalHarga,
            'status' => 'menunggu',
            'catatan' => $validated['catatan'] ?? null,
        ]);

        // Arahkan Pencari ke halaman konfirmasi booking
        return redirect()
            ->route('pencari.booking.show', $booking->id)
            ->with('success', 'Pengajuan sewa berhasil dikirim.');
    }

    /**
     * Menampilkan halaman konfirmasi booking.
     */
    public function show(int $id)
    {
        // Ambil booking beserta data kos dan pemilik
        $booking = Booking::with(['kos', 'pemilik'])
            ->where('id', $id)
            ->where('pencari_id', Auth::id())
            ->firstOrFail();

        // Tampilkan halaman konfirmasi
        return view('pencari.booking.konfirmasi', compact('booking'));
    }

    /**
     * Menampilkan halaman riwayat booking.
     */
    public function index()
    {
        // Mengambil semua booking milik Pencari yang sedang login
        $bookings = Booking::with('kos')
            ->where('pencari_id', Auth::id())
            ->latest()
            ->paginate(10);

        // Mengirim data booking ke halaman riwayat
        return view('pencari.booking.riwayat', compact('bookings'));
    }

    /**
     * Menampilkan daftar pengajuan booking yang masuk
     * untuk semua kos milik Pemilik yang sedang login.
     */
    public function pemilikIndex()
    {
        // Mengambil booking berdasarkan kos milik Pemilik yang login
        $bookings = Booking::with(['kos', 'pencari'])
            ->where('pemilik_id', Auth::id())
            ->latest()
            ->paginate(10);

        // Menampilkan halaman manajemen booking Pemilik
        return view('pemilik.booking.index', compact('bookings'));
    }


    /**
     * Menyetujui pengajuan booking dari Pencari.
     */
    public function terima(int $id)
    {
        $booking = Booking::where('id', $id)
            ->where('pemilik_id', Auth::id())
            ->where('status', 'menunggu')
            ->firstOrFail();

        $booking->update([
            'status' => 'disetujui',
            'status_pembayaran' => 'menunggu_pembayaran',
        ]);

        return redirect()
            ->route('pemilik.booking.index')
            ->with('success', 'Pengajuan booking berhasil disetujui. Silakan menunggu pembayaran dari Pencari.');
    }

    /**
     * Menolak pengajuan booking dari Pencari.
     */
    public function tolak(int $id)
    {
        $booking = Booking::where('id', $id)
            ->where('pemilik_id', Auth::id())
            ->where('status', 'menunggu')
            ->firstOrFail();

        $booking->update([
            'status' => 'ditolak',
            'status_pembayaran' => 'belum_ada', // Pastikan tidak ada pembayaran
        ]);

        return redirect()
            ->route('pemilik.booking.index')
            ->with('success', 'Pengajuan booking berhasil ditolak.');
    }


    /**
     * Menampilkan halaman pembayaran booking.
     */
    // public function pembayaran(int $id)
    // {
    //     // Mengambil booking milik Pencari yang sedang login.
    //     $booking = Booking::with(['kos', 'pemilik'])
    //         ->where('id', $id)
    //         ->where('pencari_id', Auth::id())
    //         ->where('status', 'disetujui')
    //         ->where('status_pembayaran', 'menunggu_pembayaran')
    //         ->firstOrFail();

    //     // Menampilkan halaman pembayaran.
    //     return view('pencari.booking.pembayaran', compact('booking'));
    // }
}
