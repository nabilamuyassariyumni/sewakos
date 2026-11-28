<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class AdminTransaksiController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $keyword = $request->query('keyword');

        $query = Booking::with(['kos', 'pencari', 'pemilik'])
            ->whereNotNull('midtrans_order_id'); // Hanya booking yang sudah masuk Midtrans

        // Filter berdasarkan status pembayaran
        if (in_array($status, ['belum_ada', 'menunggu_pembayaran', 'berhasil', 'gagal', 'kedaluwarsa'])) {
            $query->where('status_pembayaran', $status);
        }

        // Pencarian berdasarkan order_id / nama pencari / nama kos
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('midtrans_order_id', 'like', "%{$keyword}%")
                    ->orWhereHas('pencari', function ($sub) use ($keyword) {
                        $sub->where('name', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('kos', function ($sub) use ($keyword) {
                        $sub->where('nama_kos', 'like', "%{$keyword}%");
                    });
            });
        }

        $transaksi = $query->latest()->paginate(10)->withQueryString();

        // Hitung statistik untuk card ringkasan
        $totalTransaksi = Booking::whereNotNull('midtrans_order_id')->count();
        $totalBerhasil = Booking::where('status_pembayaran', 'berhasil')->count();
        $totalPendapatan = Booking::where('status_pembayaran', 'berhasil')->sum('total_harga');

        return view('admin.transaksi.index', compact(
            'transaksi',
            'status',
            'keyword',
            'totalTransaksi',
            'totalBerhasil',
            'totalPendapatan'
        ));
    }
}
