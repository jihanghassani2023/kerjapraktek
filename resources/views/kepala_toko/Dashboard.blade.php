<!-- resources/views/kepala_toko/Dashboard.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kepala Toko - MG TECH</title>
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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
        .dashboard-title {
            margin: 25px 0;
            font-size: 1.5em;
            color: #333;
        }
        .dashboard-title span {
            color: #888;
            font-size: 0.8em;
            margin-left: 10px;
        }
        .stats-container {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            flex: 1;
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        .stat-icon.karyawan {
            background-color: #f0e5e5;
        }
        .stat-icon.harian {
            background-color: #e9f0e5;
        }
        .stat-icon.bulanan {
            background-color: #e5e5f0;
        }
        .stat-icon i {
            font-size: 24px;
        }
        .stat-icon.karyawan i {
            color: #8c3a3a;
        }
        .stat-icon.harian i {
            color: #3a8c3a;
        }
        .stat-icon.bulanan i {
            color: #3a3a8c;
        }
        .stat-info h3 {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-info p {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
        }
        .content-section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 1.2em;
            color: #333;
        }
        .chart-container {
            height: 300px;
            width: 100%;
            margin-top: 20px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }
            .sidebar-logo span,
            .menu-item span,
            .logout span {
                display: none;
            }
            .main-content {
                margin-left: 70px;
            }
            .stats-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('img/Mg-Tech.png') }}" alt="MG Tech Logo" onerror="this.src='https://via.placeholder.com/80'">
            <span>MG TECH</span>
        </div>
        <a href="{{ route('kepala-toko.dashboard') }}" class="menu-item active">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('karyawan.index') }}" class="menu-item">
            <i class="fas fa-users"></i>
            <span>Data Karyawan</span>
        </a>
        <a href="{{ route('transaksi.index') }}" class="menu-item">
            <i class="fas fa-exchange-alt"></i>
            <span>Transaksi</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" style="margin-top: auto;">
            @csrf
            <button type="submit" class="logout" style="width: 100%; border: none; cursor: pointer; background: none; text-align: left;">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>

    <div class="main-content">
        <div class="header">
            <div>
                <h2>Dashboard <span class="user-role">Kepala Toko</span></h2>
            </div>
            <div style="display: flex; align-items: center;">
                <div class="user-info">
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="user-role">{{ $user->isKepalaToko() ? 'Kepala Toko' : ($user->isAdmin() ? 'Admin' : 'Teknisi') }}</div>
                </div>
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>

        <div class="dashboard-title">
            Dashboard <span>Kepala Toko</span>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon karyawan">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Karyawan</h3>
                    <p>{{ $karyawanCount ?? 0 }}</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon harian">
                    <i class="fas fa-money-bill"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Transaksi Harian</h3>
                    <p>Rp. {{ number_format($totalTransaksiHariIni ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bulanan">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Transaksi Bulanan</h3>
                    <p>Rp. {{ number_format($totalTransaksiBulanIni ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="content-section">
            <div class="section-header">
                <h3 class="section-title">Statistik Perbaikan Bulanan ({{ date('Y') }})</h3>
            </div>
            <div class="chart-container">
                <canvas id="monthlyRepairChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data for monthly repair chart
            const monthlyData = @json($monthlyRepairCounts ?? []);
            
            const labels = monthlyData.map(item => item.month);
            const counts = monthlyData.map(item => item.count);
            
            const ctx = document.getElementById('monthlyRepairChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Perbaikan',
                        data: counts,
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
        });
    </script>
</body>
</html>