<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - MG TECH</title>
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
        .stats-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stats-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .stats-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            overflow: hidden;
            position: relative;
        }
        .stats-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background-color: #8c3a3a;
        }
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            background-color: rgba(140, 58, 58, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
        }
        .stats-icon i {
            font-size: 24px;
            color: #8c3a3a;
        }
        .stats-info {
            flex: 1;
        }
        .stats-value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .stats-label {
            font-size: 14px;
            color: #666;
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
        .card-body {
            padding: 20px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, 
        table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        table th {
            color: #666;
            font-weight: bold;
            background-color: #f9f9f9;
        }
        table tbody tr:hover {
            background-color: #f5f5f5;
        }
        table tbody tr:last-child td {
            border-bottom: none;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
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
        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            color: #666;
            text-decoration: none;
        }
        .action-btn:hover {
            background-color: #f0f0f0;
        }
        .pagination {
            display: flex;
            list-style: none;
            margin-top: 20px;
            justify-content: flex-end;
        }
        .pagination li {
            margin: 0 3px;
        }
        .pagination a,
        .pagination span {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
            color: #666;
            transition: all 0.3s;
        }
        .pagination a {
            background-color: #f5f5f5;
        }
        .pagination a:hover {
            background-color: #e0e0e0;
        }
        .pagination .active span {
            background-color: #8c3a3a;
            color: white;
        }
        .pagination .disabled span {
            background-color: #f5f5f5;
            color: #aaa;
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
        .alert-info {
            background-color: #e6f3ff;
            border-left-color: #0d6efd;
            color: #0d6efd;
        }
        .alert-warning {
            background-color: #fff9e6;
            border-left-color: #ffaa00;
            color: #ffaa00;
        }
        .alert-danger {
            background-color: #ffeaea;
            border-left-color: #ff6b6b;
            color: #ff6b6b;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('img/Mg-Tech.png') }}" alt="MG Tech Logo" onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\' viewBox=\'0 0 80 80\'><rect width=\'80\' height=\'80\' fill=\'%23ffffff\'/><text x=\'50%\' y=\'50%\' font-size=\'30\' text-anchor=\'middle\' fill=\'%238c3a3a\' font-family=\'Arial\' dominant-baseline=\'middle\'>MG</text></svg>'">
            <h3>MG TECH</h3>
            <p>Admin Panel</p>
        </div>
        <div class="sidebar-menu">
            <div class="menu-header">MENU UTAMA</div>
            <a href="{{ route('admin.dashboard') }}" class="menu-item active">
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
            <h1 class="page-title">Dashboard</h1>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ul>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif

        <div class="stats-container">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <div class="stats-info">
                    <div class="stats-value">{{ $totalPerbaikan }}</div>
                    <div class="stats-label">Total Perbaikan</div>
                </div>
            </div>

            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stats-info">
                    <div class="stats-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                    <div class="stats-label">Total Pendapatan</div>
                </div>
            </div>

            <div class="stats-row">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-info">
                        <div class="stats-value">{{ $totalMenunggu }}</div>
                        <div class="stats-label">Menunggu</div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-spinner"></i>
                    </div>
                    <div class="stats-info">
                        <div class="stats-value">{{ $totalProses }}</div>
                        <div class="stats-label">Dalam Proses</div>
                    </div>
                </div>
            </div>

            <div class="stats-row">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-info">
                        <div class="stats-value">{{ $totalSelesai }}</div>
                        <div class="stats-label">Selesai</div>
                    </div>
                </div>

                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stats-info">
                        <div class="stats-value">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
                        <div class="stats-label">Pendapatan Hari Ini</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Transaksi Terbaru</h5>
                <div class="card-tools">
                    <a href="{{ route('admin.transaksi', ['status' => 'all']) }}" class="btn btn-outline">
                        <i class="fas fa-eye"></i> Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Pelanggan</th>
                                <th>Device</th>
                                <th>Tanggal</th>
                                <th>Teknisi</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latesTransaksi as $transaksi)
                                <tr>
                                    <td>{{ $transaksi->kode_perbaikan }}</td>
                                    <td>{{ $transaksi->nama_pelanggan }}</td>
                                    <td>{{ $transaksi->nama_barang }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_perbaikan)->format('d M Y') }}</td>
                                    <td>{{ $transaksi->user->name ?? 'Tidak ada' }}</td>
                                    <td>Rp {{ number_format($transaksi->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($transaksi->status) }}">
                                            {{ $transaksi->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.transaksi.detail', $transaksi->id) }}" class="action-btn">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align: center;">Tidak ada data transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script untuk dropdown menu, jika diperlukan
        document.addEventListener('DOMContentLoaded', function() {
            // Dapat ditambahkan script untuk dashboard jika diperlukan
        });
    </script>
</body>
</html>