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

    // Menampilkan SEMUA kos untuk pengelolaan Admin (Poin 21 & 22)
    public function semuaKos(Request $request)
    {
        $status = $request->query('status');
        $query = Kos::with('pemilik');

        if (in_array($status, ['menunggu', 'disetujui', 'ditolak'])) {
            $query->where('status_verifikasi', $status);
        }

        $kos = $query->latest()->paginate(10)->withQueryString();

        return view('admin.kos.semua-kos', compact('kos', 'status'));
    }

    // Menampilkan detail kos
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
        $kos->status_verifikasi = $request->status_verifikasi;
        $kos->save();

        return redirect()
            ->route('admin.kos.index')
            ->with(
                'success',
                'Status kos berhasil diubah menjadi ' . $kos->status_verifikasi
            );
    }
}
