<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    // Menampilkan dashboard Admin
    public function index()
    {
        // Menghitung total pengguna
        $totalPengguna = User::count();

        // Menghitung seluruh data kos
        $totalKos = Kos::count();

        // Menghitung kos yang menunggu verifikasi
        $kosMenunggu = Kos::where('status_verifikasi', 'menunggu')->count();

        // Menghitung kos yang sudah disetujui
        $kosDisetujui = Kos::where('status_verifikasi', 'disetujui')->count();

        // Menghitung kos yang ditolak
        $kosDitolak = Kos::where('status_verifikasi', 'ditolak')->count();

        // Mengambil pengajuan kos terbaru
        $pengajuanTerbaru = Kos::with('pemilik')
            ->where('status_verifikasi', 'menunggu')
            ->latest()
            ->take(5)
            ->get();

        // Menghitung notifikasi yang belum dibaca oleh Admin
        /** @var User $admin */
        $admin = Auth::user();

        $jumlahNotifikasiBelumDibaca = $admin
            ->unreadNotifications()
            ->count();
        // Menampilkan dashboard Admin
        return view('admin.dashboard-admin', compact(
            'totalPengguna',
            'totalKos',
            'kosMenunggu',
            'kosDisetujui',
            'kosDitolak',
            'pengajuanTerbaru',
            'jumlahNotifikasiBelumDibaca'
        ));
    }
}
