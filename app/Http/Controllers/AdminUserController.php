<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    // Menampilkan daftar pengguna dengan filter role dan jumlah masing-masing role
    public function index(Request $request)
    {
        $role = $request->query('role');
        $query = User::query();

        if (in_array($role, ['pencari', 'pemilik', 'admin'])) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        $jumlahPencari = User::where('role', 'pencari')->count();
        $jumlahPemilik = User::where('role', 'pemilik')->count();
        $jumlahAdmin   = User::where('role', 'admin')->count();

        return view('admin.user.index', compact(
            'users',
            'role',
            'jumlahPencari',
            'jumlahPemilik',
            'jumlahAdmin'
        ));
    }

    // Menampilkan detail pengguna beserta jumlah kos dan booking yang diajukan
    public function show(User $user)
    {
        // Hitung jumlah kos dan booking menggunakan relasi
        $totalKos = $user->kos()->count();
        $totalBooking = $user->bookingSebagaiPencari()->count();

        return view('admin.user.show', compact('user', 'totalKos', 'totalBooking'));
    }
}
