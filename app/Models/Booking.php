<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    // Kolom yang boleh diisi melalui mass assignment
    protected $fillable = [
        'kos_id',
        'pencari_id',
        'pemilik_id',
        'tanggal_mulai',
        'durasi_bulan',
        'total_harga',
        'status',
        'status_pembayaran',
        'catatan',
        // Tambahan untuk Midtrans:
        'snap_token',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'payment_type',
        'payment_url',
    ];

    // Mengubah tanggal mulai menjadi objek tanggal
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
        ];
    }

    // Relasi ke data kos
    public function kos(): BelongsTo
    {
        return $this->belongsTo(Kos::class);
    }

    // Relasi ke pengguna yang mengajukan sewa
    public function pencari(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pencari_id');
    }

    // Relasi ke pemilik kos
    public function pemilik(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pemilik_id');
    }
}
