<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AspirasiController;
use App\Http\Controllers\AdminController;

// --- REDIRECT UTAMA ---
// Memastikan pertama kali buka (/) langsung ke login siswa
Route::get('/', function () { 
    return redirect()->route('siswa.login'); 
});

// --- AREA SISWA ---
// Link disingkat menjadi /siswa
Route::get('/siswa', [AuthController::class, 'showLoginSiswa'])->name('siswa.login');
Route::post('/siswa/login', [AuthController::class, 'loginSiswa'])->name('siswa.login.proses');

// Dashboard Siswa (Harus Login)
Route::middleware(['auth.siswa'])->group(function () {
    Route::get('/siswa/dashboard', [AspirasiController::class, 'index'])->name('siswa.dashboard');
    Route::post('/siswa/kirim', [AspirasiController::class, 'store'])->name('siswa.store');
    Route::delete('/siswa/hapus/{id}', [AspirasiController::class, 'destroy'])->name('siswa.delete');
    Route::post('/siswa/logout', [AuthController::class, 'logoutSiswa'])->name('siswa.logout');
});

// --- AREA ADMIN ---
// Link disingkat menjadi /admin
Route::get('/admin', [AuthController::class, 'showLoginAdmin'])->name('admin.login');
Route::post('/admin', [AuthController::class, 'loginAdmin'])->name('admin.login.proses');

// Group Route Admin
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/tanggapi/{id}', [AdminController::class, 'updateStatus'])->name('admin.tanggapi');
    Route::delete('/admin/hapus/{id}', [AdminController::class, 'destroy'])->name('admin.delete');
    Route::post('/admin/logout', [AuthController::class, 'logoutAdmin'])->name('admin.logout');
    Route::get('/admin/siswa', [AdminController::class, 'indexSiswa'])->name('admin.siswa');
    Route::post('/admin/siswa/store', [AdminController::class, 'storeSiswa'])->name('admin.siswa.store');
    Route::delete('/admin/siswa/{id}', [AdminController::class, 'destroySiswa'])->name('admin.siswa.destroy');
    
    // Fitur Manajemen Siswa
    Route::get('/admin/siswa', [AdminController::class, 'indexSiswa'])->name('admin.siswa');
    Route::post('/admin/siswa/store', [AdminController::class, 'storeSiswa'])->name('admin.siswa.store');
});