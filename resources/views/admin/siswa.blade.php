<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Siswa | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        :root {
            --sidebar-bg: #1e293b; /* Biru gelap sesuai foto */
            --primary: #3b82f6;
            --bg-body: #f1f5f9;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { display: flex; background: var(--bg-body); min-height: 100vh; }

        /* Sidebar Sesuai Foto */
        .sidebar { width: 240px; background: var(--sidebar-bg); color: white; padding: 20px 0; position: fixed; height: 100%; }
        .sidebar-brand { padding: 0 24px 20px; font-size: 1.25rem; font-weight: 700; border-bottom: 1px solid #334155; }
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 24px; color: #94a3b8; text-decoration: none; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: #334155; color: white; border-left: 4px solid var(--primary); }

        .main-content { margin-left: 240px; flex: 1; padding: 30px; }
        .card { background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 20px; }
        
        /* Form Tambah Sederhana */
        .form-group { margin-bottom: 15px; }
        input, select { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; margin-top: 5px; }
        .btn-add { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; padding: 12px; background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #64748b; font-size: 0.8rem; text-transform: uppercase; }
        td { padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">Pengaduan</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="ph ph-squares-four"></i> Dashboard</a>
        <a href="{{ route('admin.siswa') }}" class="nav-link active"><i class="ph ph-users"></i> Siswa</a>
        <a href="#" class="nav-link"><i class="ph ph-clock-counter-clockwise"></i> Riwayat</a>
    </div>

    <div class="main-content">
        <h2 style="margin-bottom: 20px;">Daftar Siswa</h2>

        @if(session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 10px; border-radius: 6px; margin-bottom: 15px;">{{ session('success') }}</div>
        @endif

        <div class="card">
            <h4>Tambah Siswa Baru</h4>
            <form action="{{ route('admin.siswa.store') }}" method="POST" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px; align-items: end; margin-top: 15px;">
                @csrf
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" required placeholder="Nama Siswa">
                </div>
                <div class="form-group">
                    <label>NIS</label>
                    <input type="text" name="nis" required placeholder="NIS">
                </div>
                <div class="form-group">
                    <label>Kelas</label>
                    <input type="text" name="kelas" required placeholder="Contoh: XII">
                </div>
                <div class="form-group">
                    <label>Jurusan</label>
                    <input type="text" name="jurusan" required placeholder="Contoh: PPLG">
                </div>
                <button type="submit" class="btn-add">Simpan</button>
            </form>
        </div>

        <div class="card" style="margin-top: 25px;">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswas as $key => $s)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td><strong>{{ $s->nama }}</strong></td>
                        <td>{{ $s->nis }}</td>
                        <td>{{ $s->kelas }}</td>
                        <td>
                            <form action="{{ route('admin.siswa.destroy', $s->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:none; border:none; color:#ef4444; cursor:pointer;"><i class="ph ph-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>