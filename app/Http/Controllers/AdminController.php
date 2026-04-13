<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Siswa;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Ambil Statistik Ringkas
        $stats = [
            'total' => Pengaduan::count(),
            'menunggu' => Pengaduan::where('status', 'menunggu')->count(),
            'proses' => Pengaduan::where('status', 'proses')->count(),
            'selesai' => Pengaduan::where('status', 'selesai')->count(),
            'siswa' => Siswa::count(),
        ];

        // 2. Ambil Semua Pengaduan (Terbaru di atas)
        // Kita gunakan 'with' agar relasi ke siswa ikut terbawa (Eager Loading)
        $pengaduans = Pengaduan::with('siswa')->orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact('stats', 'pengaduans'));
    }

    /**
     * Fungsi untuk Update Status dan Berikan Tanggapan (Feedback)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'feedback' => 'nullable|string',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->update([
            'status' => $request->status,
            'feedback' => $request->feedback,
        ]);

        return back()->with('success', 'Status dan balasan berhasil diperbarui!');
    }
}