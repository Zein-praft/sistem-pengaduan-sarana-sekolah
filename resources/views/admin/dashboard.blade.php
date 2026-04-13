<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Aspirasi Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand">Admin Panel Aspirasi</span>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-light btn-sm">Logout</button>
        </form>
    </div>
</nav>

<div class="container">
    <div class="row mb-4 text-center">
        <div class="col-md-3">
            <div class="card bg-primary text-white p-3 shadow-sm">
                <h6>Total Aspirasi</h6>
                <h3>{{ $stats['total'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark p-3 shadow-sm">
                <h6>Menunggu</h6>
                <h3>{{ $stats['menunggu'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white p-3 shadow-sm">
                <h6>Proses</h6>
                <h3>{{ $stats['proses'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white p-3 shadow-sm">
                <h6>Selesai</h6>
                <h3>{{ $stats['selesai'] }}</h3>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h4 class="mb-3">Daftar Aspirasi Siswa</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Pesan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengaduans as $p)
                        <tr>
                            <td><strong>{{ $p->siswa->nama ?? 'Anonim' }}</strong></td>
                            <td>{{ $p->message }}</td>
                            <td>
                                <span class="badge {{ $p->status == 'selesai' ? 'bg-success' : ($p->status == 'proses' ? 'bg-warning' : 'bg-secondary') }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalBalas{{ $p->id }}">Balas</button>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalBalas{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.tanggapi', $p->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tanggapi Aspirasi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Ubah Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="menunggu" {{ $p->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                    <option value="proses" {{ $p->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                                    <option value="selesai" {{ $p->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Balasan Admin</label>
                                                <textarea name="feedback" class="form-control" rows="3">{{ $p->feedback }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>