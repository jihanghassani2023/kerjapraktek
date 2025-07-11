<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - MG TECH</title>
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

        .title-section {
            margin: 25px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.5em;
            color: #333;
        }

        .btn {
            padding: 10px 15px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
        }

        .btn i {
            margin-right: 8px;
        }

        .btn-primary {
            background-color: #8c3a3a;
            color: white;
        }

        .btn-primary:hover {
            background-color: #6d2d2d;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-export {
            background-color: #28a745;
            color: white;
        }

        .btn-export:hover {
            background-color: #218838;
        }

        .content-section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .filter-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filter-label {
            font-weight: bold;
            color: #555;
        }

        .filter-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: white;
        }

        .summary-cards {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .summary-card {
            flex: 1;
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            border-left: 5px solid #8c3a3a;
        }

        .summary-title {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
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
            color: #dc3545;
            font-weight: bold;
        }

        .status-proses {
            color: #fd7e14;
            font-weight: bold;
        }

        .status-selesai {
            color: #28a745;
            font-weight: bold;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .section-tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
        }

        .teknisi-stats {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .teknisi-stat {
            background-color: #f8f9fa;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 0.9em;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            min-width: 120px;
        }

        .teknisi-stat i {
            margin-right: 5px;
            width: 16px;
            text-align: center;
        }

        .teknisi-stat .fa-check-circle {
            color: #28a745;
        }

        .teknisi-stat .fa-clock {
            color: #ffc107;
        }

        .teknisi-stat .fa-money-bill {
            color: #8c3a3a;
        }

        .tab-item {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }

        .tab-item.active {
            border-bottom-color: #8c3a3a;
            font-weight: bold;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .teknisi-card {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
        }

        .teknisi-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .teknisi-avatar i {
            color: #8c3a3a;
            font-size: 24px;
        }

        .teknisi-info {
            flex: 1;
        }

        .teknisi-name {
            font-weight: bold;
            margin-bottom: 5px;
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

            .summary-cards,
            .filter-container {
                flex-direction: column;
            }

            .filter-group {
                margin-bottom: 10px;
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
        <a href="{{ route('admin.dashboard') }}" class="menu-item">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.transaksi') }}" class="menu-item active">
            <i class="fas fa-chart-bar"></i>
            <span>Laporan</span>
        </a>
        <a href="{{ route('admin.pelanggan') }}" class="menu-item">
            <i class="fas fa-users"></i>
            <span>Pelanggan</span>
        </a>
        <a href="{{ route('admin.perbaikan.create') }}" class="menu-item">
            <i class="fas fa-tools"></i>
            <span>Tambah Perbaikan</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" style="margin-top: auto;">
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
            <div>
                <h2>Laporan</h2>
            </div>
            <div style="display: flex; align-items: center;">
                <div class="user-info">
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="user-role">Admin</div>
                </div>
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>

        <div style="margin-top: 2%"></div>

        <div class="title-section">
            <h1 class="page-title">Data Laporan</h1>
            <a href="#" id="export-btn" class="btn btn-export">
                <i class="fas fa-file-export"></i> Export Data
            </a>
        </div>

        @if (session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif

        <div class="section-tabs">
            <div class="tab-item active" data-tab="transaksi">Transaksi</div>
            <div class="tab-item" data-tab="teknisi">Performa Teknisi</div>
        </div>

        <div class="content-section">
            <div class="filter-container">
                <div class="filter-group">
                    <span class="filter-label">Filter:</span>
                    <form action="{{ route('admin.transaksi') }}" method="GET" id="filterForm">
                        <select name="month" id="monthFilter" class="filter-select">
                            <option value="">Semua Bulan</option>
                            @php
                                $namaBulan = [
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember',
                                ];
                            @endphp
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                    {{ $namaBulan[$i] }}
                                </option>
                            @endfor
                        </select>

                        <select name="year" id="yearFilter" class="filter-select">
                            <option value="">Semua Tahun</option>
                            @for ($i = 2023; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </form>
                </div>
            </div>


            <div class="summary-cards">
                <div class="summary-card">
                    <div class="summary-title">Total Transaksi Periode Ini</div>
                    <div class="summary-value">{{ $transaksi->count() }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-title">Total Pendapatan Periode Ini</div>
                    <div class="summary-value">Rp. {{ number_format($totalTransaksi, 0, ',', '.') }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-title">Pendapatan Hari Ini</div>
                    <div class="summary-value">Rp. {{ number_format($totalTransaksiHariIni, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="tab-content active" id="transaksi-tab">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Perbaikan</th>
                                <th>Tanggal</th>
                                <th>Device</th>
                                <th>Pelanggan</th>
                                <th>Teknisi</th>
                                <th>Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksi as $index => $t)
                                <tr onclick="window.location='{{ route('admin.transaksi.show', $t->id) }}';"
                                    style="cursor: pointer;">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $t->id }}</td>
                                    <td>{{ $t->tanggal_formatted ?? \App\Helpers\DateHelper::formatTanggalIndonesia($t->tanggal_perbaikan) }}
                                    </td>
                                    <td>{{ $t->nama_device }}</td>
                                    <td>{{ $t->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                                    <td>{{ $t->user->name ?? 'N/A' }}</td>
                                    <td>Rp. {{ number_format($t->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="status-{{ strtolower($t->status) }}">{{ $t->status }}</span>
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

            <div class="tab-content" id="teknisi-tab">
                <div class="teknisi-performance">
                    @forelse($teknisiStats as $stats)
                        <div class="teknisi-card">
                            <div class="teknisi-avatar">
                                <i class="fas fa-user-cog"></i>
                            </div>
                            <div class="teknisi-info">
                                <div class="teknisi-name">{{ $stats['name'] }}</div>
                                <div class="teknisi-stats">
                                    <div class="teknisi-stat">
                                        <i class="fas fa-check-circle" style="color: #28a745;"></i>
                                        {{ $stats['repair_count'] }} Selesai
                                    </div>
                                    <div class="teknisi-stat">
                                        <i class="fas fa-clock" style="color: #ffc107;"></i>
                                        {{ $stats['pending_count'] }} Proses/Menunggu
                                    </div>
                                    <div class="teknisi-stat">
                                        <i class="fas fa-money-bill"></i> Rp.
                                        {{ number_format($stats['income'], 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p style="text-align: center;">Tidak ada data performa teknisi</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateExportLink() {
                const monthSelect = document.querySelector('select[name="month"]');
                const yearSelect = document.querySelector('select[name="year"]');
                const exportBtn = document.getElementById('export-btn');

                if (exportBtn) {
                    const baseUrl = "{{ route('admin.transaksi.export') }}";
                    const params = new URLSearchParams();

                    if (monthSelect && monthSelect.value) {
                        params.append('month', monthSelect.value);
                    }
                    if (yearSelect && yearSelect.value) {
                        params.append('year', yearSelect.value);
                    }

                    exportBtn.href = baseUrl + (params.toString() ? '?' + params.toString() : '');
                }
            }

            updateExportLink();

            const filterSelects = document.querySelectorAll('.filter-select');
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    updateExportLink();
                    setTimeout(() => {
                        document.getElementById('filterForm').submit();
                    }, 100);
                });
            });

            const tabItems = document.querySelectorAll('.tab-item');
            const tabContents = document.querySelectorAll('.tab-content');

            tabItems.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');

                    if (!targetTab) return;

                    tabItems.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));

                    this.classList.add('active');
                    const content = document.getElementById(targetTab + '-tab');
                    if (content) {
                        content.classList.add('active');
                    }
                });
            });
        });
    </script>



</body>

</html>
