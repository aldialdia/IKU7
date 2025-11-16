<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SesiController;
use Illuminate\Support\Facades\Route;


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
});
