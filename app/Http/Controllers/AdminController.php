<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Siswa;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Statistik untuk Dashboard
        $stats = [
            'total' => Pengaduan::count(),
            'menunggu' => Pengaduan::where('status', 'menunggu')->count(),
            'proses' => Pengaduan::where('status', 'proses')->count(),
            'selesai' => Pengaduan::where('status', 'selesai')->count(),
            'siswa' => Siswa::count(),
        ];

        // 2. Mengambil data pengaduan dengan relasi siswa (Eager Loading)
        $pengaduans = Pengaduan::with('siswa')->orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact('stats', 'pengaduans'));
    }

    /**
     * Masalah Pesan ke-4 dst Fixed: Menggunakan save() dan validasi yang tepat
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'required|string|min:5',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        
        // Mengisi data secara manual untuk memastikan perubahan tersimpan ke database
        $pengaduan->feedback = $request->feedback;
        $pengaduan->status = 'selesai';
        
        if ($pengaduan->save()) {
            return back()->with('success', 'Tanggapan berhasil disimpan dan status menjadi Selesai!');
        }

        return back()->with('error', 'Gagal memperbarui status.');
    }

    /**
     * Fitur Baru: Menampilkan Halaman Manajemen Siswa
     */
    public function indexSiswa()
    {
        $siswas = Siswa::orderBy('nama', 'asc')->get();
        return view('admin.siswa', compact('siswas'));
    }

    /**
     * Fitur Baru: Admin Input Siswa Baru
     */
    public function storeSiswa(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nis' => 'required|unique:siswas',
            'kelas' => 'required',
            'jurusan' => 'required'
        ]);

        // Password default adalah tanggal_lahir atau NIS jika tidak ada kolom tgl_lahir
        \App\Models\Siswa::create([
            'nama' => $request->nama,
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'password' => bcrypt($request->nis), 
        ]);

        return back()->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function destroySiswa($id) {
        \App\Models\Siswa::destroy($id);
        return back()->with('success', 'Siswa berhasil dihapus!');
    }

    /**
     * Menghapus Aspirasi
     */
    public function destroy($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->delete();

        return back()->with('success', 'Aspirasi berhasil dihapus!');
    }
}