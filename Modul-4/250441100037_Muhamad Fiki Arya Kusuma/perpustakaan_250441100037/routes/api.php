<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;

// Route Resource API untuk Buku
Route::apiResource('buku', BukuController::class);

// Route Endpoint Rekap Data (Soal No. 7)
Route::get('rekap/buku-per-kategori', [BukuController::class, 'rekapBukuPerKategori']);
Route::get('rekap/peminjaman-per-buku', [BukuController::class, 'rekapPeminjamanPerBuku']);

