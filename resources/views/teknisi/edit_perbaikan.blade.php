<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Perbaikan - MG TECH</title>
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
            margin-left: 150px;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;align-items: center;
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
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .form-header {
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-weight: bold;
        }
        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-control:focus {
            outline: none;
            border-color: #8c3a3a;
        }
        .form-control:disabled {
            background-color: #f0f0f0;
            cursor: not-allowed;
        }
        .status-select {
            cursor: pointer;
            font-weight: bold;
        }
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        .btn {
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            border: none;
            font-weight: bold;
            text-decoration: none;
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
        .form-footer {
            text-align: right;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        .customer-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #eee;
        }
        .customer-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #555;
        }
        .customer-details {
            font-size: 14px;
        }
        .customer-details p {
            margin-bottom: 5px;
        }
        .customer-details strong {
            font-weight: 600;
            color: #333;
        }
        .alert {
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-danger {
            background-color: #ffeaea;
            color: #ff6b6b;
            border: 1px solid #ffd0d0;
        }
        .alert-success {
            background-color: #e7f9e7;
            color: #28a745;
            border: 1px solid #d0f0d0;
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
        <a href="{{ route('teknisi.progress') }}" class="menu-item active">
            <i class="fas fa-tools"></i>
            <span>Progres</span>
        </a>
        <a href="{{ route('teknisi.laporan') }}" class="menu-item">
            <i class="fas fa-clipboard-list"></i>
            <span>Laporan</span>
        </a>

        <a href="{{ route('perbaikan.show', $perbaikan->id) }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" style="margin-top: 0;">
            @csrf
            <button type="submit" class="logout" style="width: 100%; border: none; cursor: pointer; background: none; text-align: left;">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>

    <div class="main-content">
        <div class="header">
            <h1 class="page-title">EDIT PERBAIKAN <span>TEKNISI</span></h1>
            <div class="user-info">
                <div class="user-name">
                    <div>{{ $user->name }}</div>
                    <div class="user-role">{{ $user->role }}</div>
                </div>
                <div class="user-avatar">
                    <img src="{{ asset('img/user.png') }}" alt="User" onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\' viewBox=\'0 0 40 40\'><circle cx=\'20\' cy=\'20\' r=\'20\' fill=\'%23f5f5f5\'/><text x=\'50%\' y=\'50%\' font-size=\'20\' text-anchor=\'middle\' fill=\'%238c3a3a\' font-family=\'Arial\' dominant-baseline=\'middle\'>{{ substr($user->name, 0, 1) }}</text></svg>'">
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="customer-info">
            <div class="customer-title">Informasi Pelanggan</div>
            <div class="customer-details">
                <p><strong>Nama:</strong> {{ $perbaikan->pelanggan->nama_pelanggan }}</p>
                <p><strong>No. Telepon:</strong> {{ $perbaikan->pelanggan->nomor_telp }}</p>
                @if($perbaikan->pelanggan->email)
                <p><strong>Email:</strong> {{ $perbaikan->pelanggan->email }}</p>
                @endif
            </div>
        </div>

        <div class="form-container">
            <h2 class="form-header">Form Edit Perbaikan</h2>
            <form action="{{ route('perbaikan.update', $perbaikan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="kode_perbaikan">Kode Perbaikan</label>
                    <input type="text" id="kode_perbaikan" class="form-control" value="{{ $perbaikan->kode_perbaikan }}" disabled>
                </div>

                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" id="nama_barang" class="form-control" value="{{ $perbaikan->nama_barang }}" disabled>
                </div>

                <div class="form-group">
                    <label for="tanggal_perbaikan">Tanggal Perbaikan</label>
                    <input type="text" id="tanggal_perbaikan" class="form-control" value="{{ \Carbon\Carbon::parse($perbaikan->tanggal_perbaikan)->format('d F Y') }}" disabled>
                </div>

                <div class="form-group">
                    <label for="masalah">Keterangan Masalah</label>
                    <textarea id="masalah" name="masalah" class="form-control" required>{{ old('masalah', $perbaikan->masalah) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="status">Status Perbaikan</label>
                    <select id="status" name="status" class="form-control status-select" required>
                        <option value="Menunggu" {{ old('status', $perbaikan->status) == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Proses" {{ old('status', $perbaikan->status) == 'Proses' ? 'selected' : '' }}>Proses</option>
                        <option value="Selesai" {{ old('status', $perbaikan->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div class="form-footer">
                    <a href="{{ route('perbaikan.show', $perbaikan->id) }}" class="btn btn-secondary" style="margin-right: 10px;">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
