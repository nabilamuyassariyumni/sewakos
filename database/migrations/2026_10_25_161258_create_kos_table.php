<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kos', function (Blueprint $table) {
            $table->id();
            //Pemilik kos dari table users
             $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('nama_kos');
            $table->text('alamat');
            $table->unsignedBigInteger('harga');
            $table->string('kategori');
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->text('fasilitas')->nullable();
            $table->text('aturan')->nullable();
            $table->unsignedInteger('jumlah_kamar')->default(1);
            $table->boolean('tersedia')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kos');
    }
};
