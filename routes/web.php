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

// --- AREA ADMIN ---
// Halaman Login Admin
Route::get('/admin', [AuthController::class, 'showLoginAdmin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'loginAdmin'])->name('admin.login.proses');

// Dashboard Admin (Harus Login Bawaan Laravel)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    // Di sini nanti tempat Admin input Siswa (Nama, NIS, Kelas, Jurusan, Tgl Lahir)
});