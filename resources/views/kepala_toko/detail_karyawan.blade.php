<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Karyawan - MG TECH</title>
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
        .content-section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .detail-row {
            margin-bottom: 15px;
            display: flex;
        }
        .detail-label {
            font-weight: bold;
            width: 150px;
            color: #555;
        }
        .detail-value {
            flex: 1;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        @media (max-width: 768px) {
            .sidebar {
                <h2>Detail Karyawan</h2>
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

        <div class="title-section">
            <h1 class="page-title">Detail Data Karyawan</h1>
            <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="content-section">
            <div class="detail-row">
                <div class="detail-label">ID Karyawan</div>
                <div class="detail-value">{{ $karyawan->id_karyawan }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Nama Karyawan</div>
                <div class="detail-value">{{ $karyawan->nama_karyawan }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">TTL</div>
                <div class="detail-value">{{ $karyawan->ttl }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Alamat</div>
                <div class="detail-value">{{ $karyawan->alamat }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Jabatan</div>
                <div class="detail-value">{{ $karyawan->jabatan }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Status</div>
                <div class="detail-value">{{ $karyawan->status }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Tanggal Dibuat</div>
                <div class="detail-value">{{ $karyawan->created_at->format('d M Y H:i') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Terakhir Diperbarui</div>
                <div class="detail-value">{{ $karyawan->updated_at->format('d M Y H:i') }}</div>
            </div>

            <div class="action-buttons">
                <a href="{{ route('karyawan.edit', $karyawan->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Data
                </a>
                <form action="{{ route('karyawan.destroy', $karyawan->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-secondary" onclick="return confirm('Apakah Anda yakin ingin menghapus karyawan ini?')">
                        <i class="fas fa-trash"></i> Hapus Karyawan
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>width: 70px;
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
            .detail-row {
                flex-direction: column;
            }
            .detail-label {
                width: 100%;
                margin-bottom: 5px;
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
        <a href="{{ route('kepala-toko.dashboard') }}" class="menu-item">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('karyawan.index') }}" class="menu-item active">
            <i class="fas fa-users"></i>
            <span>Data Karyawan</span>
        </a>
        <a href="#" class="menu-item">
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