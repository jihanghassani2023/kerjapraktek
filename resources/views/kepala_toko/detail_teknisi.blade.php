<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Teknisi - MG TECH</title>
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
            width: 220px;
            background-color: #8c3a3a;
            color: white;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-logo {
            padding: 15px 20px 30px;
            text-align: center;
        }

        .sidebar-logo img {
            width: 80px;
            height: auto;
        }

        .sidebar-logo span {
            display: block;
            font-weight: bold;
            font-size: 20px;
            margin-top: 10px;
        }

        .menu-item {
            padding: 15px 20px;
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .menu-item.active {
            background-color: #6d2d2d;
        }

        .menu-item:hover {
            background-color: #6d2d2d;
        }

        .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .logout {
            margin-top: auto;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .logout:hover {
            background-color: #6d2d2d;
        }

        .logout i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .back-btn {
            padding: 15px 20px;
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .back-btn:hover {
            background-color: #6d2d2d;
        }

        .back-btn i {
            margin-right: 10px;
        }

        .main-content {
            flex: 1;
            margin-left: 220px;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            color: #8c3a3a;
            font-weight: bold;
        }

        .user-role {
            color: #888;
            font-size: 0.9em;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f0f0f0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
        }

        .user-avatar i {
            color: #8c3a3a;
            font-size: 20px;
        }

        .content-wrapper {
            padding: 30px 0;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .content-title {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-header {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
            margin: 0;
        }

        .card-body {
            padding: 20px;
        }

        .info-row {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }

        .info-label {
            width: 200px;
            font-weight: bold;
            color: #555;
        }

        .info-value {
            flex: 1;
        }

        .statistics-container {
            margin-top: 20px;
        }

        .stat-title {
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .chart-container {
            height: 300px;
            width: 100%;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .stat-card {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .stat-card-title {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 5px;
        }

        .stat-card-value {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
        }

        .history-container {
            margin-top: 20px;
        }

        .history-title {
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            text-align: left;
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        .status-menunggu {
            color: #ff6b6b;
            font-weight: bold;
        }

        .status-proses {
            color: #ff9f43;
            font-weight: bold;
        }

        .status-selesai {
            color: #28a745;
            font-weight: bold;
        }

        .empty-state {
            text-align: center;
            padding: 40px 0;
        }

        .empty-state i {
            font-size: 48px;
            color: #ccc;
            margin-bottom: 15px;
        }

        .empty-state-text {
            color: #888;
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }

            .sidebar-logo span,
            .menu-item span,
            .logout span,
            .back-btn span {
                display: none;
            }

            .main-content {
                margin-left: 70px;
            }

            .info-row {
                flex-direction: column;
            }

            .info-label {
                width: 100%;
                margin-bottom: 5px;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('img/Mg-Tech.png') }}" alt="MG Tech Logo"
                onerror="this.src='https://via.placeholder.com/80'">
        </div>
        <a href="{{ route('kepala-toko.dashboard') }}" class="menu-item">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('transaksi.teknisi') }}" class="menu-item active">
            <i class="fas fa-users"></i>
            <span>Data Teknisi</span>
        </a>
        <a href="{{ route('laporan.index') }}" class="menu-item">
            <i class="fas fa-chart-bar"></i>
            <span>Laporan</span>
        </a>

        <a href="{{ route('transaksi.teknisi') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" style="margin-top: 0;">
            @csrf
            <button type="submit" class="logout"
                style="width: 100%; border: none; cursor: pointer; background: none; text-align: left;">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>

    <div class="main-content">
        <div class="header">
            <h1 class="page-title">Detail Teknisi</h1>
            <div style="display: flex; align-items: center;">
                <div class="user-info">
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="user-role">Kepala Toko</div>
                </div>
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="content-header">
                <h2 class="content-title">{{ $karyawan->nama_karyawan }}</h2>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Teknisi</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">ID</div>
                        <div class="info-value">{{ $karyawan->id }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value">{{ $karyawan->nama_karyawan }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tempat, Tanggal Lahir</div>
                        <div class="info-value">{{ $karyawan->ttl }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Alamat</div>
                        <div class="info-value">{{ $karyawan->alamat }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Jabatan</div>
                        <div class="info-value">{{ $karyawan->jabatan }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status</div>
                        <div class="info-value">{{ $karyawan->status }}</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistik Perbaikan</h3>
                </div>
                <div class="card-body">
                    <div class="statistics-container">
                        <div class="stat-title">Jumlah Perbaikan Perbulan ({{ date('Y') }})</div>
                        <div class="chart-container">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>

                    <div class="statistics-container">
                        <div class="stat-title">Total Pendapatan Perbulan ({{ date('Y') }})</div>
                        <div class="chart-container">
                            <canvas id="incomeChart"></canvas>
                        </div>
                    </div>

                    <div class="statistics-container">
                        <div class="stat-title">Ringkasan Statistik</div>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-card-title">Total Perbaikan</div>
                                <div class="stat-card-value">{{ count($perbaikanList) }}</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-card-title">Perbaikan Selesai</div>
                                <div class="stat-card-value">{{ $perbaikanList->where('status', 'Selesai')->count() }}
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-card-title">Perbaikan Bulan Ini</div>
                                <div class="stat-card-value">
                                    {{ $perbaikanList->whereMonth('tanggal_perbaikan', date('m'))->count() }}</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-card-title">Total Pendapatan</div>
                                <div class="stat-card-value">Rp
                                    {{ number_format($perbaikanList->where('status', 'Selesai')->sum('harga'), 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Perbaikan</h3>
                </div>
                <div class="card-body">
                    <div class="history-container">
                        <div class="table-container">
                            @if (count($perbaikanList) > 0)
                                <table>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Device</th>
                                            <th>Tanggal</th>
                                            <th>Masalah</th>
                                            <th>Harga</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($perbaikanList as $index => $repair)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $repair->kode_perbaikan }}</td>
                                                <td>{{ $repair->nama_device }}</td>
                                                <td>{{ $repair->tanggal_formatted ?? \App\Helpers\DateHelper::formatTanggalIndonesia($repair->tanggal_perbaikan) }}
                                                </td>
                                                <td>{{ $repair->masalah }}</td>
                                                <td>Rp {{ number_format($repair->harga, 0, ',', '.') }}</td>
                                                <td>
                                                    <span class="status-{{ strtolower($repair->status) }}">
                                                        {{ $repair->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="empty-state">
                                    <i class="fas fa-clipboard-list"></i>
                                    <p class="empty-state-text">Belum ada data perbaikan yang dilakukan oleh teknisi
                                        ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthlyStats = @json($monthlyStats);
            const months = monthlyStats.map(item => item.month);
            const countData = monthlyStats.map(item => item.count);
            const incomeData = monthlyStats.map(item => item.income);
            const ctxCount = document.getElementById('monthlyChart').getContext('2d');
            new Chart(ctxCount, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Jumlah Perbaikan',
                        data: countData,
                        backgroundColor: '#8c3a3a',
                        borderColor: '#6d2d2d',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
            const ctxIncome = document.getElementById('incomeChart').getContext('2d');
            new Chart(ctxIncome, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: incomeData,
                        backgroundColor: 'rgba(140, 58, 58, 0.2)',
                        borderColor: '#8c3a3a',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g,
                                        ".");
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>
