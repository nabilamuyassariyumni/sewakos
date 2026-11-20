<?php

namespace App\Http\Controllers;

use App\Models\Kos;
use Illuminate\Http\Request;

class AdminVerifikasiKosController extends Controller
{
    // Menampilkan daftar kos yang menunggu verifikasi
    public function index()
    {
        $kos = Kos::with('pemilik')
            ->where('status_verifikasi', 'menunggu')
            ->latest()
            ->paginate(10);

        return view('admin.kos.daftar-verifikasi', compact('kos'));
    }

    // Menampilkan detail kos untuk diperiksa Admin
    public function show($id)
    {
        $kos = Kos::with('pemilik')->findOrFail($id);
        return view('admin.kos.detail-verifikasi', compact('kos'));
    }

    // Menyetujui atau menolak pengajuan kos
    public function update(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:disetujui,ditolak',
        ]);

        $kos = Kos::findOrFail($id);

        // Mengubah status verifikasi
        $kos->status_verifikasi = $request->status_verifikasi;

        // Menyimpan perubahan ke database
        $kos->save();

        // Memastikan perubahan berhasil
        $kos->refresh();

        return redirect()
            ->route('admin.kos.index')
            ->with(
                'success',
                'Status kos berhasil diubah menjadi ' . $kos->status_verifikasi
            );
    }
}
