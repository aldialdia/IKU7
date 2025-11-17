<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SesiController;
use Illuminate\Support\Facades\Route;

// Import Controller baru kita
use App\Http\Controllers\Dosen\MataKuliahSayaController; 
use App\Http\Controllers\Dosen\InputMetodeController; // <-- TAMBAHKAN INI


Route::middleware(['guest'])->group(function () {
    Route::get('/', [SesiController::class, 'index'])->name('login');
    Route::post('/', [SesiController::class, 'login']);
});
Route::get('/home', function () {
    return redirect('/admin');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index']); 
    Route::get('/admin/rektorat', [AdminController::class, 'rektorat'])->middleware('userAkses:rektorat');
    Route::get('/admin/fakultas', [AdminController::class, 'fakultas'])->middleware('userAkses:fakultas');
    Route::get('/admin/dosen', [AdminController::class, 'dosen'])->middleware('userAkses:dosen');
    Route::get('/logout', [SesiController::class, 'logout']);

    // === GRUP RUTE DOSEN ===
    Route::middleware(['userAkses:dosen'])->prefix('dosen')->group(function () {
        
        // Rute "Mata Kuliah Saya"
        Route::get('/mata-kuliah-saya', [MataKuliahSayaController::class, 'index'])->name('dosen.matkul_saya');
        Route::get('/mata-kuliah-saya/{matakuliah}/detail', [MataKuliahSayaController::class, 'show'])->name('dosen.matkul_saya.show');
        Route::delete('/mata-kuliah-saya/{matakuliah}/reset', [MataKuliahSayaController::class, 'reset'])->name('dosen.matkul_saya.reset');

        // Rute "Input Metode Pembelajaran"
        Route::get('/input-metode', [InputMetodeController::class, 'index'])->name('dosen.input_metode.index');
        Route::get('/input-metode/{matakuliah}/edit', [InputMetodeController::class, 'edit'])->name('dosen.input_metode.edit');
        Route::put('/input-metode/{matakuliah}/update', [InputMetodeController::class, 'update'])->name('dosen.input_metode.update');

    });
    
    // === RUTE API UNTUK DROPDOWN DINAMIS ===
    Route::get('/api/get-departemen', [InputMetodeController::class, 'getDepartemenByFakultas'])
         ->name('api.get_departemen');

});