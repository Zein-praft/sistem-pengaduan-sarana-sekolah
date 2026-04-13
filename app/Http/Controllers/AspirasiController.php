<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Pengaduan;
use App\Models\Category;
use Illuminate\Http\Request;

class AspirasiController extends Controller
{
    // Menampilkan halaman form pengaduan untuk siswa
    public function index()
    {
        // Ambil ID siswa dari session login
        $siswa_id = session('siswa_id');
        $nama_siswa = session('nama');

        // Ambil history pengaduan milik siswa ini saja
        $history = \App\Models\Pengaduan::where('student_id', $siswa_id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('aspirasi.dashboard', compact('history', 'nama_siswa'));
    }

    // Fungsi AJAX untuk mengambil data siswa berdasarkan kelas
    public function getSiswaByKelas($kelas)
    {
        // Cari siswa yang kelasnya sesuai dengan pilihan di dropdown
        $siswa = Siswa::where('kelas', $kelas)->get();
        
        // Kirim data dalam format JSON agar bisa dibaca JavaScript
        return response()->json($siswa);
    }

    // Menyimpan data pengaduan ke database
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'message' => 'required|min:5',
        ]);

        // 2. Simpan ke database
        \App\Models\Pengaduan::create([
            'student_id' => session('siswa_id'), // Ambil ID dari session login siswa
            'message'    => $request->message,
            'status'     => 'menunggu', // Status awal otomatis menunggu
            'date'       => now()->toDateString(),
        ]);

        // 3. Balik ke dashboard dengan pesan sukses
        return redirect()->route('siswa.dashboard')->with('success', 'Aspirasi berhasil dikirim!');
    }
}