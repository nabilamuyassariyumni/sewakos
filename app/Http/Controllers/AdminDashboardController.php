<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\User;
use App\Models\Booking; // <-- Tambahkan ini
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    // Menampilkan dashboard admin dengan statistik dan pengajuan terbaru
    public function index()
    {
        $totalPengguna = User::count();
        $totalKos = Kos::count();
        $kosMenunggu = Kos::where('status_verifikasi', 'menunggu')->count();
        $kosDisetujui = Kos::where('status_verifikasi', 'disetujui')->count();
        $kosDitolak = Kos::where('status_verifikasi', 'ditolak')->count();

        // Hitung total booking yang diajukan oleh semua pengguna
        $totalBooking = Booking::count();

        $pengajuanTerbaru = Kos::with('pemilik')
            ->where('status_verifikasi', 'menunggu')
            ->latest()
            ->take(5)
            ->get();

        /** @var User $admin */
        $admin = Auth::user();
        $jumlahNotifikasiBelumDibaca = $admin->unreadNotifications()->count();

        return view('admin.dashboard-admin', compact(
            'totalPengguna',
            'totalKos',
            'kosMenunggu',
            'kosDisetujui',
            'kosDitolak',
            'totalBooking', // <-- Tambahkan ini
            'pengajuanTerbaru',
            'jumlahNotifikasiBelumDibaca'
        ));
    }
}
