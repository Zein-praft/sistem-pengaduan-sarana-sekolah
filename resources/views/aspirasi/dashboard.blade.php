<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .nav-pills .nav-link.active { background-color: #0d6efd; }
        .card { border-radius: 12px; }

        .message-truncate {
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Batasi maksimal 2 baris */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .btn-link-toggle {
            display: block;
            margin-top: 5px;
            font-size: 0.8rem;
            color: #0d6efd;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Halo, {{ $nama_siswa }}! 👋</h2>
            <p class="text-muted">Selamat datang di sistem aspirasi sekolah.</p>
        </div>
        <form action="{{ route('siswa.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger fw-bold">Logout</button>
        </form>
    </div>

    <ul class="nav nav-pills mb-4 bg-white p-2 rounded shadow-sm" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-form" data-bs-toggle="pill" data-bs-target="#pills-form">Buat Laporan</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-history" data-bs-toggle="pill" data-bs-target="#pills-history">History Laporan</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-balasan" data-bs-toggle="pill" data-bs-target="#pills-balasan">Balasan Admin</button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        
        <div class="tab-pane fade show active" id="pills-form">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="mb-3">Kirim Pengaduan Baru</h4>
                <form action="{{ route('siswa.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Detail Laporan</label>
                        <textarea name="message" class="form-control" rows="5" placeholder="Tulis di sini..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary px-4">Kirim Aspirasi</button>
                </form>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-history">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="mb-3">Riwayat Laporan</h4>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Pesan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history as $item)
                            <tr>
                                <td class="text-nowrap">{{ $item->created_at->format('d/m/Y') }}</td>
                                
                                <td style="min-width: 250px;">
                                    <div id="msg-siswa-{{ $item->id }}" class="message-truncate">
                                        {{ $item->message }}
                                    </div>
                                    
                                    @if(strlen($item->message) > 100)
                                        <a href="javascript:void(0)" 
                                        id="btn-siswa-{{ $item->id }}" 
                                        onclick="toggleSiswa('{{ $item->id }}')" 
                                        class="btn-link-toggle">
                                        Lihat Selengkapnya
                                        </a>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge {{ $item->status == 'selesai' ? 'bg-success' : ($item->status == 'proses' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->status == 'menunggu')
                                    <form action="{{ route('siswa.delete', $item->id) }}" method="POST" onsubmit="return confirm('Hapus laporan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-muted small"><i class="fas fa-lock"></i> Terkunci</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada laporan yang dikirim.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-balasan">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="mb-3">Umpan Balik Admin</h4>
                @forelse($history->whereNotNull('feedback') as $balas)
                <div class="card mb-3 border-start border-primary border-4 shadow-sm">
                    <div class="card-body">
                        <p class="mb-1 text-muted small">Laporan Anda: "{{ $balas->message }}"</p>
                        <h6 class="card-title text-primary">Balasan Admin:</h6>
                        <p class="card-text">{{ $balas->feedback }}</p>
                        <div class="text-end text-muted small">{{ $balas->updated_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada balasan dari admin untuk laporan Anda.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<script>
    function toggleSiswa(id) {
        const msg = document.getElementById('msg-siswa-' + id);
        const btn = document.getElementById('btn-siswa-' + id);

        if (msg.classList.contains('message-truncate')) {
            msg.classList.remove('message-truncate');
            btn.innerText = 'Lihat Lebih Sedikit';
        } else {
            msg.classList.add('message-truncate');
            btn.innerText = 'Lihat Selengkapnya';
        }
    }
</script>
</body>
</html>