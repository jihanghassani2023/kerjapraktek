<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi - MG TECH</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background-color: #f5f5f5;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background-color: #8c3a3a;
            color: white;
            padding: 0;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar-header img {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        .sidebar-header h3 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        .sidebar-header p {
            font-size: 14px;
            opacity: 0.8;
        }
        .sidebar-menu {
            padding: 20px 0;
            flex: 1;
        }
        .menu-header {
            padding: 10px 25px;
            font-size: 12px;
            text-transform: uppercase;
            opacity: 0.6;
            letter-spacing: 1px;
        }
        .menu-item {
            padding: 12px 25px;
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            margin-bottom: 5px;
            border-left: 4px solid transparent;
        }
        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .menu-item.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: white;
        }
        .menu-item i {
            margin-right: 15px;
            font-size: 18px;
            width: 20px;
            text-align: center;
        }
        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        .logout-btn {
            padding: 10px 20px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .logout-btn i {
            margin-right: 10px;
        }
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
        }
        .page-header {
            margin-bottom: 30px;
        }
        .page-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .breadcrumb {
            display: flex;
            list-style: none;
        }
        .breadcrumb-item {
            color: #666;
            font-size: 14px;
        }
        .breadcrumb-item:not(:last-child)::after {
            content: '/';
            margin: 0 5px;
            color: #ccc;
        }
        .breadcrumb-item.active {
            color: #8c3a3a;
        }
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            overflow: hidden;
        }
        .card-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
        .card-tools {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }
        .btn i {
            margin-right: 5px;
        }
        .btn-primary {
            background-color: #8c3a3a;
            color: white;
        }
        .btn-primary:hover {
            background-color: #6d2d2d;
        }
        .btn-outline {
            background-color: transparent;
            border: 1px solid #ddd;
            color: #666;
        }
        .btn-outline:hover {
            background-color: #f5f5f5;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .card-body {
            padding: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        .status-menunggu {
            background-color: #ffeaea;
            color: #ff6b6b;
        }
        .status-proses {
            background-color: #fff4e0;
            color: #ffaa00;
        }
        .status-selesai {
            background-color: #e7f9e7;
            color: #28a745;
        }
        .detail-section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .detail-item {
            margin-bottom: 15px;
        }
        .detail-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .detail-value {
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }
        .divider {
            height: 1px;
            background-color: #eee;
            margin: 20px 0;
        }
        .status-history {
            margin-top: 30px;
        }
        .history-item {
            display: flex;
            margin-bottom: 15px;
        }
        .history-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        .history-icon i {
            color: #8c3a3a;
        }
        .history-content {
            flex: 1;
        }
        .history-date {
            font-size: 12px;
            color: #999;
            margin-bottom: 5px;
        }
        .history-text {
            font-size: 14px;
            color: #333;
        }
        .status-form {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        .form-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-control:focus {
            outline: none;
            border-color: #8c3a3a;
        }
        .form-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23888' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
        }
        .form-actions {
            margin-top: 20px;
            text-align: right;
        }
        .alert {
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }
        .alert-success {
            background-color: #e7f9e7;
            border-left-color: #28a745;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('img/Mg-Tech.png') }}" alt="MG Tech Logo" onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\' viewBox=\'0 0 80 80\'><rect width=\'80\' height=\'80\' fill=\'%23ffffff\'/><text x=\'50%\' y=\'50%\' font-size=\'30\' text-anchor=\'middle\' fill=\'%238c3a3a\' font-family=\'Arial\' dominant-baseline=\'middle\'>MG</text></svg>'">
        </div>
        <div class="sidebar-menu">
            <div class="menu-header">MENU UTAMA</div>
            <a href="{{ route('admin.dashboard') }}" class="menu-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.transaksi', ['status' => 'all']) }}" class="menu-item">
                <i class="fas fa-exchange-alt"></i>
                <span>Semua Transaksi</span>
            </a>
            <a href="{{ route('admin.transaksi', ['status' => 'menunggu']) }}" class="menu-item">
                <i class="fas fa-clock"></i>
                <span>Menunggu</span>
            </a>
            <a href="{{ route('admin.transaksi', ['status' => 'proses']) }}" class="menu-item">
                <i class="fas fa-spinner"></i>
                <span>Dalam Proses</span>
            </a>
            <a href="{{ route('admin.transaksi', ['status' => 'selesai']) }}" class="menu-item">
                <i class="fas fa-check-circle"></i>
                <span>Selesai</span>
            </a>
            
            <div class="menu-header">MANAJEMEN</div>
            <a href="{{ route('karyawan.index') }}" class="menu-item">
                <i class="fas fa-users"></i>
                <span>Data Karyawan</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="sidebar-footer">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>

    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title">Detail Transaksi</h1>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.transaksi', ['status' => 'all']) }}">Transaksi</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ul>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Detail Transaksi #{{ $transaksi->kode_perbaikan }}</h5>
                <div class="card-tools">
                    <button class="btn btn-outline" onclick="window.print()">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                    <a href="{{ route('admin.transaksi', ['status' => 'all']) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div style="text-align: center; margin-bottom: 30px;">
                    <span class="status-badge status-{{ strtolower($transaksi->status) }}">
                        {{ $transaksi->status }}
                    </span>
                </div>

                <div class="detail-section">
                    <h3 class="section-title">Informasi Perbaikan</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Kode Perbaikan</div>
                            <div class="detail-value">{{ $transaksi->kode_perbaikan }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Tanggal Perbaikan</div>
                            <div class="detail-value">{{ \Carbon\Carbon::parse($transaksi->tanggal_perbaikan)->format('d F Y') }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Perangkat</div>
                            <div class="detail-value">{{ $transaksi->nama_barang }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Deskripsi Masalah</div>
                            <div class="detail-value">{{ $transaksi->masalah }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Harga</div>
                            <div class="detail-value">Rp {{ number_format($transaksi->harga, 0, ',', '.') }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Garansi</div>
                            <div class="detail-value">{{ $transaksi->garansi ?: 'Tidak ada' }}</div>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="detail-section">
                    <h3 class="section-title">Informasi Pelanggan</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Nama Pelanggan</div>
                            <div class="detail-value">{{ $transaksi->nama_pelanggan }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Nomor Telepon</div>
                            <div class="detail-value">{{ $transaksi->nomor_telp }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">{{ $transaksi->email ?: '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="detail-section">
                    <h3 class="section-title">Informasi Teknisi</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Nama Teknisi</div>
                            <div class="detail-value">{{ $transaksi->user->name ?? 'Tidak ada' }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Email Teknisi</div>
                            <div class="detail-value">{{ $transaksi->user->email ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Status Update Form -->
                <div class="status-form">
                    <h3 class="form-title">Ubah Status Transaksi</h3>
                    <form action="{{ route('admin.transaksi.update-status', $transaksi->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="status">Status Baru</label>
                            <select name="status" id="status" class="form-select">
                                <option value="Menunggu" {{ $transaksi->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="Proses" {{ $transaksi->status == 'Proses' ? 'selected' : '' }}>Proses</option>
                                <option value="Selesai" {{ $transaksi->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script untuk printing
        document.addEventListener('DOMContentLoaded', function() {
            // Dapat ditambahkan script untuk print jika diperlukan
        });
    </script>
</body>
</html>