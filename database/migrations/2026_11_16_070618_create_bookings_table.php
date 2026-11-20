<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration untuk membuat tabel bookings.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Relasi ke kos yang diajukan untuk disewa
            $table->foreignId('kos_id')
                ->constrained('kos')
                ->cascadeOnDelete();

            // Relasi ke pengguna yang mengajukan sewa (Pencari)
            $table->foreignId('pencari_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Relasi ke pemilik kos
            $table->foreignId('pemilik_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Informasi pengajuan sewa
            $table->date('tanggal_mulai');
            $table->unsignedInteger('durasi_bulan');

            // Total harga berdasarkan harga kos dan durasi sewa
            $table->unsignedBigInteger('total_harga');

            // Status pengajuan sewa
            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak',
                'dibatalkan'
            ])->default('menunggu');

            // Catatan tambahan dari Pencari (opsional)
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Membatalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
