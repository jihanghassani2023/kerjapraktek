<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Perbaikan - MG TECH</title>
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

        .content-section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #f5f5f5;
        }

        th,
        td {
            text-align: left;
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        tbody tr {
            cursor: pointer;
            transition: background-color 0.3s;
        }

        tbody tr:hover {
            background-color: #f5f5f5;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
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

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
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
        <a href="{{ route('admin.pelanggan') }}" class="menu-item">
            <i class="fas fa-users"></i>
            <span>Data Pelanggan</span>
        </a>
        <a href="{{ route('admin.perbaikan') }}" class="menu-item active">
            <i class="fas fa-tools"></i>
            <span>Data Perbaikan</span>
        </a>
        <a href="{{ route('admin.transaksi') }}" class="menu-item">
            <i class="fas fa-chart-bar"></i>
            <span>Laporan</span>
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
                <h2>Data Perbaikan</h2>
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

        <div class="title-section">
            <h1 class="page-title">Data Perbaikan</h1>
            <a href="{{ route('admin.pelanggan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Perbaikan
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="content-section">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Perbaikan</th>
                            <th>Nama Device</th>
                            <th>Pelanggan</th>
                            <th>Teknisi</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perbaikan as $index => $p)
                            <tr onclick="window.location.href='{{ route('admin.perbaikan.show', $p->id) }}';">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $p->id }}</td>
                                <td>{{ $p->nama_device }}</td>
                                <td>{{ $p->pelanggan->nama_pelanggan }}</td>
                                <td>{{ $p->user->name ?? 'Belum ditugaskan' }}</td>
                                <td>{{ $p->tanggal_formatted ?? \App\Helpers\DateHelper::formatTanggalIndonesia($p->tanggal_perbaikan) }}
                                </td>
                                <td>
                                    <span class="status status-{{ strtolower($p->status) }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center;">Tidak ada data perbaikan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
