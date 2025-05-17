<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\SuratKeluarController;
use App\Http\Controllers\Dashboard\SuratMasukController;
use App\Http\Controllers\Dashboard\SuratRevisiController;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

// Route::get('/check-auth', function () {
//     return auth()->check() ? 'Logged In' : 'Not Logged In';
// });

Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::middleware(['auth'])->group(function () {
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('surat-masuk')->group(function () {
            Route::get('/', [SuratMasukController::class, 'index'])->name('surat-masuk');
            Route::get('/create', [SuratMasukController::class, 'create'])->name('surat-masuk.create');
        });

        Route::prefix('surat-revisi')->group(function () {
            Route::get('/', [SuratRevisiController::class, 'index'])->name('surat-revisi');
        });

        Route::prefix('surat-keluar')->group(function () {
            Route::get('/', [SuratKeluarController::class, 'index'])->name('surat-keluar');
            Route::post('/store', [SuratKeluarController::class, 'store'])->name('surat-keluar.store');
            Route::get('/{id}', [SuratKeluarController::class, 'show'])->name('surat-keluar.show');
            Route::delete('/{id}', [SuratKeluarController::class, 'destroy'])->name('surat-keluar.destroy');
            Route::get('/{id}/review', [SuratKeluarController::class, 'review'])->name('surat-keluar.review');
            Route::get('/{id}/cetak', [SuratKeluarController::class, 'cetak'])->name('surat-keluar.cetak');
            Route::patch('/{id}/approval', [SuratKeluarController::class, 'approval'])->name('surat-keluar.approval');
            Route::post('/{id}/komentar', [SuratKeluarController::class, 'tambahKomentar'])->name('surat-keluar.komentar');




        });
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get("/tester", [LoginController::class, "registrasi"]);
