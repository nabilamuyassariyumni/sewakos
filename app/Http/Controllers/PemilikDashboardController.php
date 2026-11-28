<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class PemilikDashboardController extends Controller
{
    public function index()
    {
        // Pastikan hanya pemilik yang bisa akses
        if (Auth::user()->role !== 'pemilik') {
            return redirect()->route('dashboard');
        }

        // 1. Ambil semua kos milik pemilik
        $kos = Kos::where('user_id', Auth::id())->latest()->get();

        // 2. Hitung statistik
        $totalKos = $kos->count();
        $totalKamar = $kos->sum('jumlah_kamar');
        $kosAktif = $kos->where('tersedia', true)->count();

        // 3. Hitung booking masuk (yang masih menunggu persetujuan)
        $bookingMasuk = Booking::where('pemilik_id', Auth::id())
            ->where('status', 'menunggu')
            ->count();

        // 4. Ambil 3 booking terbaru untuk ditampilkan di dashboard (Poin 26)
        $bookingTerbaru = Booking::with(['kos', 'pencari'])
            ->where('pemilik_id', Auth::id())
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard-pemilik', compact(
            'kos',
            'totalKos',
            'totalKamar',
            'kosAktif',
            'bookingMasuk',
            'bookingTerbaru'
        ));
    }
}
