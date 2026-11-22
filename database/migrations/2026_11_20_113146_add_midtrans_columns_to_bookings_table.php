<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Token untuk memunculkan pop-up Snap Midtrans
            $table->string('snap_token')->nullable()->after('status_pembayaran');

            // ID Order unik dari sistem kita (contoh: BOOKING-1-20261120)
            $table->string('midtrans_order_id')->nullable()->unique()->after('snap_token');

            // ID Transaksi dari Midtrans (didapat dari webhook/callback)
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');

            // Tipe pembayaran (qris, bank_transfer, gopay, dll)
            $table->string('payment_type')->nullable()->after('midtrans_transaction_id');

            // URL pembayaran (jika menggunakan metode redirect)
            $table->string('payment_url')->nullable()->after('payment_type');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'snap_token',
                'midtrans_order_id',
                'midtrans_transaction_id',
                'payment_type',
                'payment_url'
            ]);
        });
    }
};
