<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KeretaController;
use App\Http\Controllers\Api\SaranaController;
use App\Http\Controllers\Api\PemeriksaanController;
use App\Http\Controllers\Api\PejabatController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Daftar Kereta
Route::get('/kereta', [KeretaController::class, 'index']);
Route::get('/kereta/{id}/sarana', [SaranaController::class, 'byKereta']);

// Daftar Pejabat Penandatangan
Route::get('/pejabat', [PejabatController::class, 'index']);

// Cari Sarana berdasarkan kode atau nomor
Route::get('/sarana/search', [SaranaController::class, 'search']);

// Simpan Pemeriksaan
Route::post('/pemeriksaan', [PemeriksaanController::class, 'store']);

Route::get('/pemeriksaan', [PemeriksaanController::class, 'index']);
Route::get('/pemeriksaan/{id}', [PemeriksaanController::class, 'show']);