
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan status pembayaran pada tabel bookings.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('status_pembayaran', [
                'belum_ada',
                'menunggu_pembayaran',
                'berhasil',
                'gagal',
                'kedaluwarsa'
            ])->default('belum_ada')->after('status');
        });
    }

    /**
     * Menghapus kolom status pembayaran.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('status_pembayaran');
        });
    }
};
