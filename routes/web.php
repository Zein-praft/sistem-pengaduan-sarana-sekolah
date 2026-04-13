<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AspirasiController;
use App\Http\Controllers\AdminController;

// --- AREA SISWA ---
// Halaman Login Siswa
Route::get('/aspirasi-siswa', [AuthController::class, 'showLoginSiswa'])->name('siswa.login');
Route::post('/aspirasi-siswa/login', [AuthController::class, 'loginSiswa'])->name('siswa.login.proses');

// Dashboard Siswa (Harus Login)
Route::middleware(['auth.siswa'])->group(function () {
    Route::get('/aspirasi-siswa/dashboard', [AspirasiController::class, 'index'])->name('siswa.dashboard');
    Route::post('/aspirasi-siswa/kirim', [AspirasiController::class, 'store'])->name('siswa.store');
    Route::delete('/aspirasi-siswa/hapus/{id}', [AspirasiController::class, 'destroy'])->name('siswa.delete');
    Route::post('/aspirasi-siswa/logout', [AuthController::class, 'logoutSiswa'])->name('siswa.logout');
});

// Route Login Admin (Bisa diakses siapa saja)
Route::get('/admin-login', [AuthController::class, 'showLoginAdmin'])->name('admin.login');
Route::post('/admin-login', [AuthController::class, 'loginAdmin'])->name('admin.login.proses');

// Group Route Admin (Hanya bisa diakses kalau sudah login admin)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/tanggapi/{id}', [AdminController::class, 'updateStatus'])->name('admin.tanggapi');
    Route::post('/admin/logout', [AuthController::class, 'logoutAdmin'])->name('admin.logout');
});