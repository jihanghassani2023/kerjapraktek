<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laporan Perbaikan - MG TECH</title>
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
            width: 150px;
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
            width: 70px;
            height: auto;
        }
        .sidebar-logo span {
            display: block;
            font-weight: bold;
            font-size: 16px;
            margin-top: 10px;
        }
        .menu-item {
            padding: 15px 15px;
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
            margin-left: 150px;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #e3e3e3;
        }
        .page-title {
            font-size: 24px;
            color: #8c3a3a;
        }
        .page-title span {
            font-size: 14px;
            color: #888;
            margin-left: 10px;
            font-weight: normal;
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-name {
            text-align: right;
            margin-right: 10px;
        }
        .user-role {
            color: #999;
            font-size: 14px;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        thead {
            background-color: #f5f5f5;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e3e3e3;
        }
        th {
            color: #666;
            font-weight: bold;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-selesai {
            background-color: #e7f9e7;
            color: #28a745;
        }
        .filter-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }
        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .filter-label {
            font-size: 14px;
            color: #666;
        }
        .filter-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            min-width: 150px;
        }
        .btn-filter {
            padding: 8px 15px;
            background-color: #8c3a3a;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }
        .btn-filter:hover {
            background-color: #6d2d2d;
        }
        .btn-export {
            padding: 8px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
        }
        .btn-export:hover {
            background-color: #218838;
            color: white;
        }
        .empty-state {
            text-align: center;
            padding: 40px 0;
            color: #888;
        }
        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #ddd;
        }
        .empty-state-text {
            font-size: 16px;
        }
        tbody tr:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('img/Mg-Tech.png') }}" alt="MG Tech Logo" onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\' viewBox=\'0 0 80 80\'><rect width=\'80\' height=\'80\' fill=\'%238c3a3a\'/><text x=\'50%\' y=\'50%\' font-size=\'30\' text-anchor=\'middle\' fill=\'white\' font-family=\'Arial\' dominant-baseline=\'middle\'>MG</text></svg>'">
        </div>
        <a href="{{ route('teknisi.dashboard') }}" class="menu-item">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('teknisi.laporan') }}" class="menu-item active">
            <i class="fas fa-clipboard-list"></i>
            <span>Laporan</span>
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
            <h1 class="page-title">Laporan <span>{{ strtoupper($user->jabatan) }}</span></h1>
            <div class="user-info">
                <div class="user-name">
                    <div>{{ $user->name }}</div>
                    <div class="user-role">{{ $user->jabatan }}</div>
                </div>
                <div class="user-avatar">
                    <img src="{{ asset('img/user.png') }}" alt="User" onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\' viewBox=\'0 0 40 40\'><circle cx=\'20\' cy=\'20\' r=\'20\' fill=\'%23f5f5f5\'/><text x=\'50%\' y=\'50%\' font-size=\'20\' text-anchor=\'middle\' fill=\'%238c3a3a\' font-family=\'Arial\' dominant-baseline=\'middle\'>{{ substr($user->name, 0, 1) }}</text></svg>'">
                </div>
            </div>
        </div>

        <div class="filter-container">
            <div class="filter-group">
                <div class="filter-label">Filter:</div>
                <form action="{{ route('teknisi.laporan') }}" method="GET" id="filterForm">
                    <select name="month" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Bulan</option>
                        <option value="1" {{ request('month') == '1' ? 'selected' : '' }}>Januari</option>
                        <option value="2" {{ request('month') == '2' ? 'selected' : '' }}>Februari</option>
                        <option value="3" {{ request('month') == '3' ? 'selected' : '' }}>Maret</option>
                        <option value="4" {{ request('month') == '4' ? 'selected' : '' }}>April</option>
                        <option value="5" {{ request('month') == '5' ? 'selected' : '' }}>Mei</option>
                        <option value="6" {{ request('month') == '6' ? 'selected' : '' }}>Juni</option>
                        <option value="7" {{ request('month') == '7' ? 'selected' : '' }}>Juli</option>
                        <option value="8" {{ request('month') == '8' ? 'selected' : '' }}>Agustus</option>
                        <option value="9" {{ request('month') == '9' ? 'selected' : '' }}>September</option>
                        <option value="10" {{ request('month') == '10' ? 'selected' : '' }}>Oktober</option>
                        <option value="11" {{ request('month') == '11' ? 'selected' : '' }}>November</option>
                        <option value="12" {{ request('month') == '12' ? 'selected' : '' }}>Desember</option>
                    </select>
                    <select name="year" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Tahun</option>
                        <option value="2023" {{ request('year') == '2023' ? 'selected' : '' }}>2023</option>
                        <option value="2024" {{ request('year') == '2024' ? 'selected' : '' }}>2024</option>
                        <option value="2025" {{ request('year') == '2025' ? 'selected' : '' }}>2025</option>
                    </select>
                </form>
            </div>
            <!-- PERBAIKAN: Gunakan route name yang sesuai dengan yang ada di routes/web.php -->
            <a href="{{ route('laporan.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" class="btn-export">
                <i class="fas fa-file-export"></i> Export Laporan
            </a>
        </div>

        @if($perbaikan->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>KODE PERBAIKAN</th>
                    <th>NAMA DEVICE</th>
                    <th>TANGGAL PERBAIKAN</th>
                    <th>MASALAH</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($perbaikan as $index => $p)
                <tr onclick="window.location.href='{{ route('perbaikan.show', $p->id) }}';">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->nama_device }}</td>
                    <td>{{ $p->tanggal_formatted ?? \App\Helpers\DateHelper::formatTanggalIndonesia($p->tanggal_perbaikan) }}</td>
                    <td>{{ Str::limit($p->masalah, 50) }}</td>
                    <td><span class="status status-selesai">{{ $p->status }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-clipboard-list"></i>
            <p class="empty-state-text">Belum ada data perbaikan yang selesai</p>
        </div>
        @endif
    </div>
</body>
</html>
