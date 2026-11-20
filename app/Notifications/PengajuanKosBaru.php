<?php

namespace App\Notifications;

use App\Models\Kos;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengajuanKosBaru extends Notification
{
    use Queueable;

    protected Kos $kos;

    public function __construct(Kos $kos)
    {
        $this->kos = $kos;
    }

    // Menggunakan database sebagai media notifikasi
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    // Data yang disimpan ke tabel notifications
    public function toDatabase(object $notifiable): array
    {
        return [
            'judul' => 'Pengajuan Kos Baru',
            'pesan' => 'Pemilik mengajukan kos "' .
                $this->kos->nama_kos .
                '" dan membutuhkan verifikasi.',

            'kos_id' => $this->kos->id,
            'nama_kos' => $this->kos->nama_kos,
            'status' => 'menunggu',
        ];
    }
}
