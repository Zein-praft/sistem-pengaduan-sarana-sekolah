<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Siswa::create([
            'nis' => '20237041',
            'nama' => 'Muhamad Fahmi',
            'kelas' => 'XI PPLG',
            'jurusan' => 'PPLG',
            'tgl_lahir' => '20090112', 
        ]);
    }
}
