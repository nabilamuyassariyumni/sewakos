<?php

namespace App\Http\Controllers;

use App\Models\Kos;

class DetailKosController extends Controller
{
    /**
     * Menampilkan halaman detail kost berdasarkan ID.
     */
    public function show($id)
    {
        // Mengambil data kost dan data pemilik dari database.
        $kos = Kos::with('pemilik')->findOrFail($id);

        // Mengubah data fasilitas menjadi array.
        $semuaFasilitas = $this->ubahKeDaftar($kos->fasilitas);

        // Menentukan daftar fasilitas umum.
        $daftarFasilitasUmum = [
            'wifi',
            'laundry',
            'penjagakost',
            'penguruskost',
            'cctv',
        ];

        // Memisahkan fasilitas umum dari fasilitas lainnya.
        $fasilitasUmum = [];
        $fasilitas = [];

        foreach ($semuaFasilitas as $item) {

            // Menyamakan format nama agar mudah dibandingkan.
            $namaNormal = strtolower(
                str_replace([' ', '-', '_'], '', trim($item))
            );

            // Memasukkan fasilitas ke kelompok yang sesuai.
            if (in_array($namaNormal, $daftarFasilitasUmum)) {
                $fasilitasUmum[] = $item;
            } else {
                $fasilitas[] = $item;
            }
        }

        // Mengubah data aturan menjadi array.
        $aturan = $this->ubahKeDaftar($kos->aturan);

        // Mengirim data kost ke halaman Blade.
        return view('pencari.kos.detail', compact(
            'kos',
            'fasilitas',
            'fasilitasUmum',
            'aturan'
        ));
    }

    /**
     * Mengubah data teks atau JSON menjadi array.
     */
    private function ubahKeDaftar($data)
    {
        // Jika data kosong, kembalikan array kosong.
        if (empty($data)) {
            return [];
        }

        // Jika data sudah berupa array, gunakan langsung.
        if (is_array($data)) {
            return array_values(array_filter($data));
        }

        // Mencoba membaca data dalam format JSON.
        $hasilJson = json_decode($data, true);

        // Jika JSON valid dan berbentuk array, gunakan hasilnya.
        if (json_last_error() === JSON_ERROR_NONE && is_array($hasilJson)) {
            return array_values(array_filter($hasilJson));
        }

        // Jika bukan JSON, pisahkan berdasarkan koma, titik koma, atau baris baru.
        $daftar = preg_split('/[,;\r\n]+/', $data);

        // Membersihkan spasi dan menghapus data kosong.
        return array_values(array_filter(
            array_map('trim', $daftar)
        ));
    }
}
