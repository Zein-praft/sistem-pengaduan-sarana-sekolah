<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Fintrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        :root {
            --primary: #4F46E5; --primary-hover: #4338ca; --bg-body: #F3F4F6;
            --bg-card: #FFFFFF; --text-main: #111827; --text-muted: #6B7280;
            --border: #E5E7EB; --danger: #EF4444; --success: #10B981;
            --warning: #F59E0B; --info: #3B82F6;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-body); color: var(--text-main); display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: 260px; background-color: var(--bg-card); border-right: 1px solid var(--border); position: fixed; height: 100%; z-index: 10; }
        .sidebar-header { padding: 24px; font-size: 1.25rem; font-weight: 700; color: var(--primary); display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--border); }
        .nav-links { padding: 20px; display: flex; flex-direction: column; gap: 8px; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 8px; color: var(--text-muted); text-decoration: none; font-weight: 500; transition: all 0.2s; }
        .nav-item:hover, .nav-item.active { background-color: #EEF2FF; color: var(--primary); }

        /* Main Content */
        .main-content { margin-left: 260px; flex: 1; padding: 32px; width: calc(100% - 260px); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
        
        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px; margin-bottom: 32px; }
        .card { background: var(--bg-card); padding: 24px; border-radius: 12px; border: 1px solid var(--border); display: flex; flex-direction: column; gap: 8px; }
        .card-value { font-size: 1.875rem; font-weight: 700; }
        .card-icon { align-self: flex-end; margin-top: -32px; font-size: 1.5rem; padding: 10px; border-radius: 8px; }

        /* Table & Filters */
        .table-container { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
        .filters { padding: 20px; border-bottom: 1px solid var(--border); display: flex; gap: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #F9FAFB; padding: 16px 24px; font-size: 0.75rem; color: var(--text-muted); border-bottom: 1px solid var(--border); text-align: left; }
        td { padding: 16px 24px; font-size: 0.9rem; border-bottom: 1px solid var(--border); }
        
        /* Badges */
        .badge { padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
        .badge-menunggu { background: #FEF3C7; color: #B45309; }
        .badge-selesai { background: #D1FAE5; color: #065F46; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header"><i class="ph ph-hexagon-fill"></i> fintrack</div>
        <nav class="nav-links">
            <a href="{{ route('admin.dashboard') }}" class="nav-item active"><i class="ph ph-squares-four"></i> Dashboard</a>
            <a href="{{ route('admin.siswa') }}" class="nav-item"><i class="ph ph-users"></i> Siswa</a>
            <form action="{{ route('admin.logout') }}" method="POST" style="margin-top: auto;">
                @csrf
                <button type="submit" class="nav-item" style="border:none; background:none; width:100%;"><i class="ph ph-sign-out"></i> Keluar</button>
            </form>
        </nav>
    </aside>

    <main class="main-content">
        <header class="header">
            <h1>Dashboard Permohonan</h1>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span>Admin</span>
                <div style="width:40px; height:40px; border-radius:50%; background:#4F46E5; color:white; display:flex; align-items:center; justify-content:center;">A</div>
            </div>
        </header>

        <section class="stats-grid">
            <div class="card">
                <span class="card-label">Total Data</span>
                <span class="card-value">{{ $stats['total'] }}</span>
                <div class="card-icon" style="background:#EDE9FE; color:#4F46E5;"><i class="ph ph-files"></i></div>
            </div>
            <div class="card">
                <span class="card-label">Menunggu</span>
                <span class="card-value">{{ $stats['menunggu'] }}</span>
                <div class="card-icon" style="background:#FEF3C7; color:#F59E0B;"><i class="ph ph-hourglass"></i></div>
            </div>
            <div class="card">
                <span class="card-label">Selesai</span>
                <span class="card-value">{{ $stats['selesai'] }}</span>
                <div class="card-icon" style="background:#D1FAE5; color:#10B981;"><i class="ph ph-check-circle"></i></div>
            </div>
        </section>

        <section class="table-container">
            <div class="filters">
                <strong>Daftar Aspirasi Terbaru</strong>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Info Laporan (Tgl, Kelas, Jurusan)</th>
                        <th>Isi Aspirasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengaduans as $p)
                    <tr>
                        <td>
                            <small>{{ $p->created_at->format('d M Y') }}</small><br>
                            <strong>{{ $p->siswa->kelas ?? '-' }}</strong> | {{ $p->siswa->jurusan ?? '-' }}
                        </td>
                        <td>{{ Str::limit($p->message, 50) }}</td>
                        <td>
                            <span class="badge {{ $p->status == 'selesai' ? 'badge-selesai' : 'badge-menunggu' }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td>
                            <button onclick="openModal('{{ $p->id }}', '{{ $p->message }}')" style="cursor:pointer; border:none; background:none; color:var(--primary); font-weight:600;">Balas</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </main>

    <div id="replyModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center;">
        <div style="background:white; padding:24px; border-radius:12px; width:400px;">
            <h3>Tanggapi Aspirasi</h3>
            <p id="modalMsg" style="margin: 10px 0; color: var(--text-muted); font-size: 0.9rem;"></p>
            <form id="replyForm" method="POST">
                @csrf
                <textarea name="feedback" required style="width:100%; height:100px; padding:10px; margin-bottom:10px; border-radius:8px; border:1px solid var(--border);"></textarea>
                <div style="display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" onclick="closeModal()" style="padding:8px 16px; border-radius:8px; border:1px solid var(--border); cursor:pointer;">Batal</button>
                    <button type="submit" style="padding:8px 16px; border-radius:8px; background:var(--primary); color:white; border:none; cursor:pointer;">Kirim & Selesai</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id, msg) {
            document.getElementById('modalMsg').innerText = '"' + msg + '"';
            document.getElementById('replyForm').action = "/admin/tanggapi/" + id;
            document.getElementById('replyModal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('replyModal').style.display = 'none';
        }
    </script>
</body>
</html>