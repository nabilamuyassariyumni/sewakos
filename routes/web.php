<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminNotifikasiController;
use App\Http\Controllers\AdminVerifikasiKosController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DetailKosController;
use App\Http\Controllers\KosController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    // Dashboard utama: arahkan berdasarkan role
    Route::get('/dashboard', function () {

        if (Auth::user()->role === 'admin') {
            return redirect()->route('dashboard.admin');
        }

        if (Auth::user()->role === 'pemilik') {
            return redirect()->route('dashboard.pemilik');
        }

        return redirect()->route('dashboard.pencari');
    })->name('dashboard');


    // ==============================
    // ROUTE PENCARI
    // ==============================

    Route::get('/pencari/kos/rekomendasi', [KosController::class, 'rekomendasi'])
        ->middleware('role:pencari')
        ->name('pencari.kos.rekomendasi');

    // Halaman detail kos
    Route::get('/pencari/kos/{id}', [DetailKosController::class, 'show'])
        ->middleware('role:pencari')
        ->whereNumber('id')
        ->name('pencari.kos.detail');

    // Dashboard pencari
    Route::get('/dashboard/pencari', function () {

        if (Auth::user()->role !== 'pencari') {
            return redirect()->route('dashboard');
        }

        return redirect()->route('pencari.kos.index');
    })->name('dashboard.pencari');

    // Pencarian kos
    Route::get('/pencari/kos', [KosController::class, 'cari'])
        ->middleware('role:pencari')
        ->name('pencari.kos.index');


    // Halaman pembayaran booking
    Route::get('/pencari/booking/{id}/pembayaran', [BookingController::class, 'pembayaran'])
        ->middleware('role:pencari')
        ->whereNumber('id')
        ->name('pencari.booking.pembayaran');



    // ==============================
    // ROUTE PEMILIK
    // ==============================

    // Dashboard Pemilik
    Route::get('/dashboard/pemilik', function () {

        if (Auth::user()->role !== 'pemilik') {
            return redirect()->route('dashboard');
        }

        $kos = \App\Models\Kos::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('dashboard-pemilik', compact('kos'));
    })->name('dashboard.pemilik');


    // Manajemen kos dan booking Pemilik
    Route::middleware('role:pemilik')
        ->prefix('pemilik')
        ->name('pemilik.')
        ->group(function () {

            // ==============================
            // MANAJEMEN KOS
            // ==============================

            Route::get('/kos', [KosController::class, 'index'])
                ->name('kos.index');

            Route::get('/kos/create', [KosController::class, 'create'])
                ->name('kos.create');

            Route::post('/kos', [KosController::class, 'store'])
                ->name('kos.store');

            Route::get('/kos/{ko}/edit', [KosController::class, 'edit'])
                ->name('kos.edit');

            Route::put('/kos/{ko}', [KosController::class, 'update'])
                ->name('kos.update');

            Route::delete('/kos/{ko}', [KosController::class, 'destroy'])
                ->name('kos.destroy');


            // ==============================
            // MANAJEMEN BOOKING
            // ==============================

            Route::get('/booking', [BookingController::class, 'pemilikIndex'])
                ->name('booking.index');

            // Menyetujui pengajuan booking
            Route::post('/booking/{id}/terima', [BookingController::class, 'terima'])
                ->name('booking.terima');

            // Menolak pengajuan booking
            Route::post('/booking/{id}/tolak', [BookingController::class, 'tolak'])
                ->name('booking.tolak');
        });



    // ==============================
    // ROUTE ADMIN
    // ==============================

    // Dashboard Admin (pertahankan nama route lama)
    Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])
        ->middleware('role:admin')
        ->name('dashboard.admin');

    // Fitur Admin
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // Halaman daftar notifikasi
            Route::get('/notifikasi', [AdminNotifikasiController::class, 'index'])
                ->name('notifikasi.index');

            // Membaca notifikasi
            Route::get('/notifikasi/{id}/baca', [AdminNotifikasiController::class, 'show'])
                ->name('notifikasi.baca');

            // Daftar kos yang menunggu verifikasi
            Route::get('/kos', [AdminVerifikasiKosController::class, 'index'])
                ->name('kos.index');

            // Detail kos untuk pemeriksaan Admin
            Route::get('/kos/{id}', [AdminVerifikasiKosController::class, 'show'])
                ->whereNumber('id')
                ->name('kos.show');

            // Proses persetujuan atau penolakan kos
            Route::patch('/kos/{id}/verifikasi', [AdminVerifikasiKosController::class, 'update'])
                ->whereNumber('id')
                ->name('kos.update');
        });


    // ==============================
    // ROUTE PROFILE
    // ==============================

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    // ==============================
    // ROUTE BOOKING PENCARI
    // ==============================

    // Menyimpan pengajuan booking
    Route::post('/pencari/kos/{id}/booking', [BookingController::class, 'store'])
        ->middleware('role:pencari')
        ->name('pencari.booking.store');

    // Menampilkan konfirmasi booking
    Route::get('/pencari/booking/{id}', [BookingController::class, 'show'])
        ->middleware('role:pencari')
        ->whereNumber('id')
        ->name('pencari.booking.show');

    // Menampilkan riwayat booking Pencari
    Route::get('/pencari/booking', [BookingController::class, 'index'])
        ->middleware('role:pencari')
        ->name('pencari.booking.index');
});

require __DIR__ . '/auth.php';
