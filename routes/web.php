<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// --- IMPORT CONTROLLER BARU ---
use App\Http\Controllers\Dosen\MataKuliahSayaController; 
use App\Http\Controllers\Dosen\InputMetodeController; 
use App\Http\Controllers\Fakultas\VerifikasiController;
use App\Http\Controllers\Fakultas\ManajemenDosenController;
use App\Http\Controllers\Rektorat\ManajemenFakultasController;
use App\Http\Controllers\Rektorat\PenilaianController;

// Import Controller Dashboard
use App\Http\Controllers\Rektorat\DashboardController as RektoratDashboard;
use App\Http\Controllers\Fakultas\DashboardController as FakultasDashboard;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === RUTE TAMU (BELUM LOGIN) ===
Route::middleware(['guest'])->group(function () {
    Route::get('/', [SesiController::class, 'index'])->name('login');
    Route::post('/', [SesiController::class, 'login']);
});

Route::get('/home', function () {
    return redirect('/admin');
});

// === RUTE USER LOGIN (AUTH) ===
Route::middleware(['auth'])->group(function () {
    
    // --- RUTE UMUM (Profil & Logout) ---
    Route::get('/admin', [AdminController::class, 'index']); // Opsional jika sudah ada dashboard masing-masing
    Route::get('/logout', [SesiController::class, 'logout']);
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/tentang-aplikasi', [ProfileController::class, 'tentang'])->name('profile.tentang');


    // ==========================================================
    // GRUP RUTE DOSEN
    // ==========================================================
    Route::middleware(['userAkses:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
        
        // Dashboard Dosen (INI YANG MEMPERBAIKI ERROR ANDA)
        Route::get('/dashboard', [DosenDashboard::class, 'index'])->name('dashboard');

        // Mata Kuliah Saya
        Route::get('/mata-kuliah-saya', [MataKuliahSayaController::class, 'index'])->name('matkul_saya');
        Route::get('/mata-kuliah-saya/{matakuliah}/detail', [MataKuliahSayaController::class, 'show'])->name('matkul_saya.show');
        Route::delete('/mata-kuliah-saya/{matakuliah}/reset', [MataKuliahSayaController::class, 'reset'])->name('matkul_saya.reset');

        // Input Metode Pembelajaran
        Route::get('/input-metode', [InputMetodeController::class, 'index'])->name('input_metode.index');
        Route::get('/input-metode/{matakuliah}/edit', [InputMetodeController::class, 'edit'])->name('input_metode.edit');
        Route::put('/input-metode/{matakuliah}/update', [InputMetodeController::class, 'update'])->name('input_metode.update');
    });


    // ==========================================================
    // GRUP RUTE ADMIN FAKULTAS
    // ==========================================================
    Route::middleware(['userAkses:fakultas'])->prefix('fakultas')->name('fakultas.')->group(function () {
        
        // Dashboard Fakultas
        Route::get('/dashboard', [FakultasDashboard::class, 'index'])->name('dashboard');

        // Verifikasi Mata Kuliah
        Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
        Route::get('/verifikasi/{matakuliah}/detail', [VerifikasiController::class, 'show'])->name('verifikasi.show');
        Route::patch('/verifikasi/{matakuliah}/verify', [VerifikasiController::class, 'verify'])->name('verifikasi.verify');
        Route::get('/dashboard/departemen/{departemen}', [FakultasDashboard::class, 'showDepartemenDetail'])->name('dashboard.departemen');
            Route::get('/dashboard/matkul/{matakuliah}', [FakultasDashboard::class, 'showMatkulDetail'])->name('dashboard.matkul');
        // Manajemen Dosen (CRUD)
        Route::resource('/manajemen-dosen', ManajemenDosenController::class);
    });


    // ==========================================================
    // GRUP RUTE ADMIN REKTORAT
    // ==========================================================
    Route::middleware(['userAkses:rektorat'])->prefix('rektorat')->name('rektorat.')->group(function () {
        
        // Dashboard Rektorat
        Route::get('/dashboard', [RektoratDashboard::class, 'index'])->name('dashboard');
        Route::get('/dashboard/fakultas/{fakultas}', [RektoratDashboard::class, 'showFakultasDetail'])->name('dashboard.fakultas');
        Route::get('/dashboard/departemen/{departemen}', [RektoratDashboard::class, 'showDepartemenDetail'])->name('dashboard.departemen');
        Route::get('/dashboard/matkul/{matakuliah}', [RektoratDashboard::class, 'showMatkulDetail'])->name('dashboard.matkul');
        // Manajemen Akun Fakultas (CRUD)
        Route::resource('manajemen-fakultas', ManajemenFakultasController::class)
             ->parameters(['manajemen-fakultas' => 'user']); // Agar parameter URL jadi {user}

        // Penilaian Mahasiswa
        Route::get('/penilaian-list', [PenilaianController::class, 'list'])->name('penilaian.list');
        Route::get('/penilaian/{matakuliah}', [PenilaianController::class, 'index'])->name('penilaian.index');
        Route::post('/penilaian/{matakuliah}/mahasiswa', [PenilaianController::class, 'storeMahasiswa'])->name('penilaian.store_mahasiswa');
        Route::post('/penilaian/{matakuliah}/nilai', [PenilaianController::class, 'storeNilai'])->name('penilaian.store_nilai');
    });


    // ==========================================================
    // RUTE API (Internal)
    // ==========================================================
    Route::get('/api/get-departemen', [InputMetodeController::class, 'getDepartemenByFakultas'])->name('api.get_departemen');

});