<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\PemeriksaanWebController;
use App\Http\Controllers\Web\PejabatWebController;
use App\Http\Controllers\Web\StamformasiWebController;

/*
|--------------------------------------------------------------------------
| Web Admin Portal Routes
|--------------------------------------------------------------------------
*/

// Redirect root to dashboard
Route::get('/', [AdminController::class, 'dashboard'])->name('web.dashboard');
Route::get('/dashboard', [AdminController::class, 'dashboard']);

// Monitoring Riwayat Pemeriksaan
Route::prefix('pemeriksaan')->name('web.pemeriksaan.')->group(function () {
    Route::get('/', [PemeriksaanWebController::class, 'index'])->name('index');
    Route::get('/{id}', [PemeriksaanWebController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [PemeriksaanWebController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PemeriksaanWebController::class, 'update'])->name('update');
    Route::put('/{id}/dokumen', [PemeriksaanWebController::class, 'updateDokumen'])->name('dokumen.update');
    Route::get('/{id}/print', [PemeriksaanWebController::class, 'print'])->name('print');
    Route::delete('/{id}', [PemeriksaanWebController::class, 'destroy'])->name('destroy');
    Route::delete('/{id}/detail/{idDetail}', [PemeriksaanWebController::class, 'destroyDetail'])->name('detail.destroy');
});

// Master Pejabat Penandatangan
Route::prefix('pejabat')->name('web.pejabat.')->group(function () {
    Route::get('/', [PejabatWebController::class, 'index'])->name('index');
    Route::post('/', [PejabatWebController::class, 'store'])->name('store');
    Route::put('/{id}', [PejabatWebController::class, 'update'])->name('update');
    Route::delete('/{id}', [PejabatWebController::class, 'destroy'])->name('destroy');
});

// Master Stamformasi & Sarana Kereta (termasuk Import / Transpose)
Route::prefix('stamformasi')->name('web.stamformasi.')->group(function () {
    Route::get('/', [StamformasiWebController::class, 'index'])->name('index');
    Route::get('/import', [StamformasiWebController::class, 'importForm'])->name('import');
    Route::post('/import', [StamformasiWebController::class, 'importProcess'])->name('import.process');
    Route::put('/sarana/{id}', [StamformasiWebController::class, 'updateSarana'])->name('sarana.update');
    Route::delete('/sarana/{id}', [StamformasiWebController::class, 'destroySarana'])->name('sarana.destroy');
    Route::delete('/kereta/{id}', [StamformasiWebController::class, 'destroyKereta'])->name('kereta.destroy');
    Route::post('/kereta/{id}/sarana', [StamformasiWebController::class, 'storeSarana'])->name('sarana.store');
    Route::post('/reset-all', [StamformasiWebController::class, 'resetAll'])->name('reset');
});
