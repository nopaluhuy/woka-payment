<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\PesertaController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\KwitansiController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('peserta.dashboard');
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // REGISTER KURSUS SAJA
    Route::get('/register/kursus', [\App\Http\Controllers\PesertaController::class, 'showKursusRegistrationForm'])
        ->name('register.kursus');

    Route::post('/register/kursus', [\App\Http\Controllers\PesertaController::class, 'storeKursusRegistration'])
        ->name('register.kursus.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/payments-data', [DashboardController::class, 'paymentsData'])->name('admin.dashboard.payments-data');


        // =========================
        // PESERTA (SEMUA)
        // =========================
        Route::get('/peserta', [PesertaController::class, 'index'])
            ->name('peserta.index');

        Route::get('/peserta/kursus', [PesertaController::class, 'kursus'])
            ->name('peserta.kursus');

        Route::get('/peserta/pkl', [PesertaController::class, 'pkl'])
            ->name('peserta.pkl');

        // CREATE (KURSUS / PKL) ⬅️ INI YANG DIPAKAI TOMBOL
        Route::get('/peserta/create/{jenis}', [PesertaController::class, 'create'])
            ->name('peserta.create');

        Route::post('/peserta', [PesertaController::class, 'store'])
            ->name('peserta.store');

        Route::get('/peserta/{peserta}/edit', [PesertaController::class, 'edit'])
            ->name('peserta.edit');

        Route::put('/peserta/{peserta}', [PesertaController::class, 'update'])
            ->name('peserta.update');

        Route::delete('/peserta/{peserta}', [PesertaController::class, 'destroy'])
            ->name('peserta.destroy');

        // =========================
        // PENDAFTARAN (PENDING)
        // =========================
        Route::get('/pendaftaran', [PesertaController::class, 'indexPendaftaran'])
            ->name('peserta.pendaftaran');

        Route::get('/peserta/{peserta}/confirm', [PesertaController::class, 'confirm'])
            ->name('peserta.confirm');

        Route::post('/peserta/{peserta}/accept', [PesertaController::class, 'accept'])
            ->name('peserta.accept');

        // =========================
        // PEMBAYARAN
        // =========================
        // Semua pembayaran
        Route::get('pembayaran', [PembayaranController::class, 'index'])
            ->name('pembayaran.index');

        // Pembayaran khusus kursus
        Route::get('pembayaran/kursus', [PembayaranController::class, 'indexKursus'])
            ->name('pembayaran.kursus');

        // Pembayaran khusus PKL
        Route::get('pembayaran/pkl', [PembayaranController::class, 'indexPkl'])
            ->name('pembayaran.pkl');

        // Tambah pembayaran
        Route::get('pembayaran/create', [PembayaranController::class, 'create'])
            ->name('pembayaran.create');

        // Simpan pembayaran
        Route::post('pembayaran', [PembayaranController::class, 'store'])
            ->name('pembayaran.store');

        // Edit pembayaran
        Route::get('pembayaran/{pembayaran}/edit', [PembayaranController::class, 'edit'])
            ->name('pembayaran.edit');

        // Update pembayaran
        Route::put('pembayaran/{pembayaran}', [PembayaranController::class, 'update'])
            ->name('pembayaran.update');

        // Hapus pembayaran
        Route::delete('pembayaran/{pembayaran}', [PembayaranController::class, 'destroy'])
            ->name('pembayaran.destroy');

        // Terima pembayaran (custom)
        Route::post('pembayaran/{pembayaran}/accept', [PembayaranController::class, 'accept'])
            ->name('pembayaran.accept');



        // =========================
        // KWITANSI
        // =========================
        // LIST semua kwitansi
        Route::get('kwitansi', [KwitansiController::class, 'index'])
            ->name('kwitansi.index');

        // FORM create kwitansi
        Route::get('kwitansi/create', [KwitansiController::class, 'create'])
            ->name('kwitansi.create');

        // STORE kwitansi
        Route::post('kwitansi', [KwitansiController::class, 'store'])
            ->name('kwitansi.store');

        // DELETE kwitansi
        Route::delete('kwitansi/{kwitansi}', [KwitansiController::class, 'destroy'])
            ->name('kwitansi.destroy');

        // DOWNLOAD kwitansi
        Route::get('kwitansi/{kwitansi}/download', [KwitansiController::class, 'download'])
            ->name('kwitansi.download');

        // =========================
        // LAPORAN (FIX)
        // =========================
        Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])
            ->name('laporan.index');
    });


// ======================
// ROUTE PESERTA
// ======================
Route::middleware(['auth', 'role:peserta', 'peserta.active'])
    ->prefix('peserta')
    ->name('peserta.')
    ->group(function () {

        Route::get('/', [\App\Http\Controllers\PesertaController::class, 'index'])->name('index');

        Route::get('/kursus/saya', [\App\Http\Controllers\PesertaController::class, 'kursus'])->name('kursus.saya');
        Route::get('/pkl/saya', [\App\Http\Controllers\PesertaController::class, 'pkl'])->name('pkl.saya');

        Route::get('/pembayaran', [\App\Http\Controllers\PesertaController::class, 'pembayaran'])->name('pembayaran.index');
        Route::post('/pembayaran/{peserta}', [\App\Http\Controllers\PesertaController::class, 'storePembayaran'])->name('pembayaran.store');

        Route::get('/kwitansi/{kwitansi}/download', [\App\Http\Controllers\PesertaController::class, 'downloadKwitansi'])
            ->name('kwitansi.download');

        Route::get('/profile', [\App\Http\Controllers\PesertaController::class, 'profile'])->name('profile');
        Route::put('/profile', [\App\Http\Controllers\PesertaController::class, 'updateProfile'])->name('profile.update');
        Route::post('/kursus/keluar', [\App\Http\Controllers\PesertaController::class, 'keluar'])
            ->name('kursus.keluar');

    });