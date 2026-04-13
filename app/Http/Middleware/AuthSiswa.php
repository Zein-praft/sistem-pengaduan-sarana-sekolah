<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AuthSiswa
{
    public function handle(Request $request, Closure $next): Response
    {
        $sudahLogin = Session::has('siswa_id');
        
        // Ambil nama route saat ini
        $routeName = $request->route()->getName();

        // LOGIKA 1: Kalau mau ke halaman LOGIN tapi SUDAH login
        if ($sudahLogin && $routeName == 'siswa.login') {
            return redirect()->route('siswa.dashboard');
        }

        // LOGIKA 2: Kalau mau ke halaman DASHBOARD tapi BELUM login
        if (!$sudahLogin && $routeName != 'siswa.login' && $routeName != 'siswa.login.proses') {
            return redirect()->route('siswa.login')->with('error', 'Silakan login dulu!');
        }

        return $next($request);
    }
}