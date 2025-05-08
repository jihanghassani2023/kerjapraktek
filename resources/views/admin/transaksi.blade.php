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
        .card-body {
            padding: 20px;
        }
        .filter-bar {
            display: flex;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
            align-items: center;
        }
        .filter-label {
            margin-right: 15px;
            font-weight: bold;
            color: #666;
        }
        .filter-options {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .filter-btn {
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            background-color: white;
            border: 1px solid #ddd;
            color: #666;
            text-decoration: none;
        }
        .filter-btn.active {
            background-color: #8c3a3a;
            color: white;
            border-color: #8c3a3a;
        }
        .filter-btn:hover:not(.active) {
            background-color: #f0f0f0;
        }
        .search-box {
            display: flex;
            margin-left: auto;
        }
        .search-input {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 5px 0 0 5px;
            font-size: 14px;
            min-width: 200px;
        }
        .search-btn {
            padding: 8px 15px;
            border-radius: 0 5px 5px 0;
            background-color: #8c3a3a;
            color: white;
            border: none;
            cursor: pointer;
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
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            min-width: 150px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 5px;
            z-index: 1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropdown-item {
            padding: 10px 15px;
            display: block;
            text-decoration: none;
            color: #333;
            transition: all 0.2s;
        }
        .dropdown-item:hover {
            background-color: #f5f5f5;
        }
        .dropdown-divider {
            border-top: 1px solid #eee;
            margin: 5px 0;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .modal-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .close {
            color: #aaa;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: #333;
        }
        .modal-body {
            margin-bottom: 20px;
        }
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 15px;
            border-top: 1px solid #eee;
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
            <a href="{{ route('admin.dashboard') }}" class="menu-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.transaksi', ['status' => 'all']) }}" class="menu-item {{ $status == 'all' ? 'active' : '' }}">
                <i class="fas fa-exchange-alt"></i>
                <span>Semua Transaksi</span>
            </a>
            <a href="{{ route('admin.transaksi', ['status' => 'menunggu']) }}" class="menu-item {{ $status == 'menunggu' ? 'active' : '' }}">
                <i class="fas fa-clock"></i>
                <span>Menunggu</span>
            </a>
            <a href="{{ route('admin.transaksi', ['status' => 'proses']) }}" class="menu-item {{ $status == 'proses' ? 'active' : '' }}">
                <i class="fas fa-spinner"></i>
                <span>Dalam Proses</span>
            </a>
            <a href="{{ route('admin.transaksi', ['status' => 'selesai']) }}" class="menu-item {{ $status == 'selesai' ? 'active' : '' }}">
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
            <h1 class="page-title">Transaksi</h1>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Transaksi</li>
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

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    @if($status == 'all')
                        Semua Transaksi
                    @elseif($status == 'menunggu')
                        Transaksi Menunggu
                    @elseif($status == 'proses')
                        Transaksi Dalam Proses
                    @elseif($status == 'selesai')
                        Transaksi Selesai
                    @endif
                </h5>
                <div class="card-tools">
                    <a href="{{ route('admin.transaksi.export') }}" class="btn btn-outline">
                        <i class="fas fa-file-export"></i> Export
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="filter-bar">
                    <div class="filter-label">Filter:</div>
                    <div class="filter-options">
                        <a href="{{ route('admin.transaksi', ['status' => 'all']) }}" class="filter-btn {{ $status == 'all' ? 'active' : '' }}">Semua</a>
                        <a href="{{ route('admin.transaksi', ['status' => 'menunggu']) }}" class="filter-btn {{ $status == 'menunggu' ? 'active' : '' }}">Menunggu</a>
                        <a href="{{ route('admin.transaksi', ['status' => 'proses']) }}" class="filter-btn {{ $status == 'proses' ? 'active' : '' }}">Proses</a>
                        <a href="{{ route('admin.transaksi', ['status' => 'selesai']) }}" class="filter-btn {{ $status == 'selesai' ? 'active' : '' }}">Selesai</a>
                    </div>

                    <form action="{{ route('admin.transaksi', ['status' => $status]) }}" method="GET" class="search-box">
                        <input type="text" name="search" placeholder="Cari kode atau nama..." class="search-input" value="{{ request('search') }}">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
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
                            @forelse($transaksi as $index => $t)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $t->kode_perbaikan }}</td>
                                    <td>{{ $t->nama_pelanggan }}</td>
                                    <td>{{ $t->nama_barang }}</td>
                                    <td>{{ \Carbon\Carbon::parse($t->tanggal_perbaikan)->format('d M Y') }}</td>
                                    <td>{{ $t->user->name ?? 'Tidak ada' }}</td>
                                    <td>Rp {{ number_format($t->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($t->status) }}">
                                            {{ $t->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="action-btn">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-content">
                                                <a href="{{ route('admin.transaksi.detail', $t->id) }}" class="dropdown-item">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a href="#" class="dropdown-item change-status" data-id="{{ $t->id }}" data-toggle="modal" data-target="#statusModal">
                                                    <i class="fas fa-edit"></i> Ubah Status
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" style="text-align: center;">Tidak ada data transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    {{ $transaksi->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ubah Status -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Status Transaksi</h5>
                <span class="close">&times;</span>
            </div>
            <form id="updateStatusForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="Menunggu">Menunggu</option>
                            <option value="Proses">Proses</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline modal-close">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Status modal functionality
            const modal = document.getElementById('statusModal');
            const changeStatusBtns = document.querySelectorAll('.change-status');
            const closeBtn = document.querySelector('.close');
            const modalCloseBtn = document.querySelector('.modal-close');
            const updateStatusForm = document.getElementById('updateStatusForm');
            
            // Open modal and set form action
            changeStatusBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    updateStatusForm.action = `/admin/transaksi/${id}/update-status`;
                    modal.style.display = 'block';
                });
            });
            
            // Close modal
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
            
            modalCloseBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>