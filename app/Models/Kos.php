<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kos extends Model
{
    // Menentukan nama tabel yang digunakan
    protected $table = 'kos';

    // Kolom yang boleh diisi melalui proses mass assignment
    protected $fillable = [
        'user_id',
        'nama_kos',
        'alamat',
        'harga',
        'kategori',
        'deskripsi',
        'foto',
        'fasilitas',
        'aturan',
        'jumlah_kamar',
        'tersedia',

        // Kolom tambahan untuk verifikasi oleh Admin
        'status_verifikasi',
    ];

    // Mengatur tipe data atribut
    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'jumlah_kamar' => 'integer',
            'tersedia' => 'boolean',
        ];
    }

    // Relasi bahwa setiap kost dimiliki oleh satu pengguna
    public function pemilik(): BelongsTo
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'user_id'
        );
    }

    // Relasi ke seluruh pengajuan booking kos
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
