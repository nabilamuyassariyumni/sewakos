<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom status verifikasi
     * pada tabel kos.
     */
    public function up(): void
    {
        Schema::table('kos', function (Blueprint $table) {

            // Status awal setiap kost adalah menunggu verifikasi
            $table->enum('status_verifikasi', [
                'menunggu',
                'disetujui',
                'ditolak'
            ])->default('menunggu')->after('tersedia');

        });
    }

    /**
     * Menghapus kolom jika migration di-rollback.
     */
    public function down(): void
    {
        Schema::table('kos', function (Blueprint $table) {

            $table->dropColumn('status_verifikasi');

        });
    }
};
