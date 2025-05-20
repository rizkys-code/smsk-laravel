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

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('surat-masuk')->group(function () {
            Route::get('/', [SuratMasukController::class, 'index'])->name('surat-masuk');
            Route::post('/', [SuratMasukController::class, 'store'])->name('surat-masuk.store');
            Route::get('/{id}', [SuratMasukController::class, 'show'])->name('surat-masuk.show');
            Route::get('/{id}/edit', [SuratMasukController::class, 'edit'])->name('surat-masuk.edit');
            Route::get('/{id}/review', [SuratMasukController::class, 'review'])->name('surat-masuk.review');
            Route::post('/{id}/review', [SuratMasukController::class, 'submitReview'])->name('surat-masuk.submit-review');
            Route::put('/{id}', [SuratMasukController::class, 'update'])->name('surat-masuk.update');
            Route::delete('/{id}', [SuratMasukController::class, 'destroy'])->name('surat-masuk.destroy');
            Route::get('/{id}/revision', [SuratMasukController::class, 'revision'])->name('surat-masuk.revision');
            Route::post('/{id}/revision', [SuratMasukController::class, 'processRevision'])->name('surat-masuk.process-revision');
            Route::post('/{id}/disposisi', [SuratMasukController::class, 'disposisi'])->name('surat-masuk.disposisi');
            Route::patch('/{id}/update-file', [SuratMasukController::class, 'updateFile'])->name('surat-masuk.update-file');
        });


        Route::prefix('surat-revisi')->group(function () {
            Route::get('/', [SuratRevisiController::class, 'index'])->name('surat-revisi');
            // Route::patch('/{id}', [SuratRevisiController::class, 'update'])->name('surat-revisi.update');
            Route::get('/{id}/edit', [SuratRevisiController::class, 'edit'])->name('surat-revisi.edit');
            Route::put('/{id}', action: [SuratRevisiController::class, 'update'])->name('surat-revisi.update');

        });

        Route::prefix('surat-keluar')->group(function () {
            Route::get('/', [SuratKeluarController::class, 'index'])->name('surat-keluar');
            Route::post('/store', [SuratKeluarController::class, 'store'])->name('surat-keluar.store');
            Route::get('/{id}', [SuratKeluarController::class, 'show'])->name('surat-keluar.show');
            Route::delete('/{id}', [SuratKeluarController::class, 'destroy'])->name('surat-keluar.destroy');
            Route::get('/{id}/review', [SuratKeluarController::class, 'review'])->name('surat-keluar.review');
            Route::get('/{id}/print', [SuratKeluarController::class, 'cetak'])->name('surat-keluar.print');
            Route::patch('/{id}/approval', [SuratKeluarController::class, 'approval'])->name('surat-keluar.approval');
            Route::post('/{id}/comment', [SuratKeluarController::class, 'tambahKomentar'])->name('surat-keluar.comment');

        });
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get("/tester", [LoginController::class, "registrasi"]);
