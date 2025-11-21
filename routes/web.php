<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SesiController;
use Illuminate\Support\Facades\Route;

// Import Controller baru kita
use App\Http\Controllers\Dosen\MataKuliahSayaController; 
use App\Http\Controllers\Dosen\InputMetodeController; 
use App\Http\Controllers\Fakultas\VerifikasiController;
use App\Http\Controllers\Fakultas\ManajemenDosenController;
use App\Http\Controllers\Rektorat\DashboardController;
use App\Http\Controllers\Rektorat\ManajemenFakultasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Rektorat\PenilaianController;

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
    

    // === GRUP RUTE ADMIN FAKULTAS ===
    Route::middleware(['userAkses:fakultas'])->prefix('fakultas')->name('fakultas.')->group(function () {
        
        // Rute untuk halaman list verifikasi
        Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
        
        // --- TAMBAHKAN RUTE DETAIL BARU INI ---
        Route::get('/verifikasi/{matakuliah}/detail', [VerifikasiController::class, 'show'])->name('verifikasi.show');
        // --- AKHIR TAMBAHAN ---
        
        // Rute untuk aksi memverifikasi (tetap kita pakai)
        Route::patch('/verifikasi/{matakuliah}/verify', [VerifikasiController::class, 'verify'])->name('verifikasi.verify');

        // Ini akan otomatis membuat rute untuk:
        // index, create, store, show, edit, update, destroy
        Route::resource('/manajemen-dosen', ManajemenDosenController::class);

    });

    Route::middleware(['userAkses:rektorat'])->prefix('rektorat')->name('rektorat.')->group(function () {
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/dashboard/fakultas/{fakultas}', [DashboardController::class, 'showFakultasDetail'])->name('dashboard.fakultas');

        Route::get('/dashboard/departemen/{departemen}', [DashboardController::class, 'showDepartemenDetail'])->name('dashboard.departemen');

        Route::resource('manajemen-fakultas', ManajemenFakultasController::class)
             ->parameters(['manajemen-fakultas' => 'user']);

        Route::get('/penilaian-list', [PenilaianController::class, 'list'])->name('penilaian.list');
        Route::get('/penilaian/{matakuliah}', [PenilaianController::class, 'index'])->name('penilaian.index');
        Route::post('/penilaian/{matakuliah}/mahasiswa', [PenilaianController::class, 'storeMahasiswa'])->name('penilaian.store_mahasiswa');
        Route::post('/penilaian/{matakuliah}/nilai', [PenilaianController::class, 'storeNilai'])->name('penilaian.store_nilai');
    });


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/tentang-aplikasi', [ProfileController::class, 'tentang'])->name('profile.tentang');

    // === RUTE API UNTUK DROPDOWN DINAMIS ===
    Route::get('/api/get-departemen', [InputMetodeController::class, 'getDepartemenByFakultas'])
         ->name('api.get_departemen');

});