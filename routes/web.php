<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\SuratKeluarController;
use App\Http\Controllers\Dashboard\SuratMasukController;
use App\Http\Controllers\Dashboard\SuratRevisiController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

// Route::middleware(['auth', 'role:superadmin'])->group(function () {
//     Route::get('/revisi', ...);
// });

Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('/login', [LoginController::class, 'index']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::prefix('/dashboard/surat-masuk')->group(function () {
    Route::get('/', [SuratMasukController::class, 'index'])->name('surat-masuk');
    Route::get('/create', [SuratMasukController::class, 'create'])->name('surat-masuk.create');
    // Route::put('/edit/{id}', [SuratMasukController::class, 'update'])->name('surat-masuk.update');
    // Route::delete('/delete/{id}', [SuratMasukController::class, 'destroy'])->name('surat-masuk.destroy');
    // Route::get('/detail/{id}', [SuratMasukController::class, 'show'])->name('surat-masuk.show');
    // Route::get('/cetak/{id}', [SuratMasukController::class, 'cetak'])->name('surat-masuk.cetak');

});

Route::prefix(('dashboard/surat-revisi'))->group(function () {
    Route::get('/', [SuratRevisiController::class, 'index'])->name('surat-revisi');
});

Route::prefix('/dashboard/surat-keluar')->group(function () {
    Route::get('/', [SuratKeluarController::class, 'index'])->name('surat-keluar');
    Route::post('/create', [SuratKeluarController::class, 'create'])->name('surat-keluar.create');
});


Route::get("/tester", [LoginController::class, "registrasi"]);
