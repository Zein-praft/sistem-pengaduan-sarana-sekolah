<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Tampilan Login Siswa
    public function showLoginSiswa() {
        return view('auth.login-siswa');
    }

    // Proses Login Siswa (Manual Session)
    public function loginSiswa(Request $request) {
        // Cari siswa berdasarkan NIS
        $siswa = Siswa::where('nis', $request->nis)->first();

        // Cek apakah siswa ada DAN tanggal lahirnya cocok
        if ($siswa && $siswa->tgl_lahir == $request->tgl_lahir) {
            // Simpan ke Session
            session([
                'siswa_id' => $siswa->id,
                'nama'     => $siswa->nama,
                'role'     => 'siswa'
            ]);

            return redirect()->route('siswa.dashboard');
        }

        return back()->with('error', 'NIS atau Tanggal Lahir salah!');
    }

    // Tampilan Login Admin
    public function showLoginAdmin() {
        return view('auth.login-admin');
    }

    // Proses Login Admin (Pake Auth Bawaan Laravel)
    public function loginAdmin(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Email atau Password admin salah!');
    }

    // Logout Semua
    public function logoutSiswa() {
        Session::flush();
        return redirect()->route('siswa.login');
    }
}