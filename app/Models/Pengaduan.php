<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'pengaduans';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = ['siswa_id', 'message', 'status', 'feedback', 'date'];

    /**
     * Relasi ke Siswa
     * Laporan ini dimiliki oleh seorang siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'student_id');
    }

    /**
     * Relasi ke Kategori (Jika ada)
     */
    public function kategori()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}