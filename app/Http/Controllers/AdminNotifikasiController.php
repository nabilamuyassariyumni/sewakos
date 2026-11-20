<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminNotifikasiController extends Controller
{
    // Menampilkan seluruh notifikasi Admin
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $notifikasi = $user->notifications()
            ->latest()
            ->paginate(10);

        return view('admin.notifikasi.index', compact('notifikasi'));
    }

    // Menandai notifikasi sebagai sudah dibaca
    // Kemudian membuka detail kos terkait
    public function show(string $id)
    {
        /** @var User $user */
        $user = Auth::user();

        $notifikasi = $user->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notifikasi->markAsRead();

        $kosId = $notifikasi->data['kos_id'] ?? null;

        if ($kosId && Kos::whereKey($kosId)->exists()) {
            return redirect()->route('admin.kos.show', $kosId);
        }

        return redirect()
            ->route('admin.notifikasi.index')
            ->with('success', 'Notifikasi berhasil dibaca.');
    }
}

