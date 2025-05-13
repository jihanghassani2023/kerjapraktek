<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pelanggan - MG TECH</title>
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
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            border-color: #8c3a3a;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(140, 58, 58, 0.25);
        }
        .invalid-feedback {
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            border-left: 4px solid;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
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
            <img src="{{ asset('img/Mg-Tech.png') }}" alt="MG Tech Logo" onerror="this.src='https://via.placeholder.com/80'">
            <span>MG TECH</span>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="menu-item">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.transaksi') }}" class="menu-item">
            <i class="fas fa-exchange-alt"></i>
            <span>Transaksi</span>
        </a>
        <a href="{{ route('admin.pelanggan') }}" class="menu-item active">
            <i class="fas fa-users"></i>
            <span>Pelanggan</span>
        </a>
        <a href="{{ route('admin.perbaikan.create') }}" class="menu-item">
            <i class="fas fa-tools"></i>
            <span>Tambah Perbaikan</span>
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
                <h2>Edit Pelanggan</h2>
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
            <h1 class="page-title">Edit Data Pelanggan</h1>
            <a href="{{ route('admin.pelanggan') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
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

        <div class="content-section">
            <form action="{{ route('admin.pelanggan.update', $pelanggan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nama_pelanggan">Nama Pelanggan</label>
                    <input type="text" class="form-control @error('nama_pelanggan') is-invalid @enderror" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" required>
                    @error('nama_pelanggan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nomor_telp">Nomor Telepon</label>
                    <input type="text" class="form-control @error('nomor_telp') is-invalid @enderror" id="nomor_telp" name="nomor_telp" value="{{ old('nomor_telp', $pelanggan->nomor_telp) }}" required>
                    @error('nomor_telp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email (Opsional)</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $pelanggan->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div style="text-align: right;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<script>
    // JavaScript untuk validasi frontend

    // Menunggu hingga DOM sepenuhnya dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Temukan form dan input yang relevan
        const form = document.querySelector('form');
        const nomorTelpInput = document.getElementById('nomor_telp');
        const hargaInput = document.getElementById('harga'); // Mungkin tidak ada di semua form

        if (form) {
            form.addEventListener('submit', function(e) {
                let isValid = true;

                // Validasi nomor telepon - hanya angka dan maksimal 13 digit
                if (nomorTelpInput) {
                    const phoneValue = nomorTelpInput.value.trim();
                    const phoneRegex = /^[0-9]{1,13}$/;

                    if (!phoneRegex.test(phoneValue)) {
                        isValid = false;
                        // Buat atau perbarui pesan kesalahan
                        let errorDiv = nomorTelpInput.nextElementSibling;
                        if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                            errorDiv = document.createElement('div');
                            errorDiv.className = 'invalid-feedback';
                            nomorTelpInput.parentNode.insertBefore(errorDiv, nomorTelpInput.nextSibling);
                        }

                        errorDiv.textContent = phoneValue.length > 13 ?
                            'Nomor telepon maksimal 13 digit.' :
                            'Nomor telepon hanya boleh berisi angka.';

                        errorDiv.style.display = 'block';
                        nomorTelpInput.classList.add('is-invalid');
                    } else {
                        // Hapus kesalahan jika valid
                        nomorTelpInput.classList.remove('is-invalid');
                        const errorDiv = nomorTelpInput.nextElementSibling;
                        if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                            errorDiv.style.display = 'none';
                        }
                    }
                }

                // Validasi input harga jika ada - hanya angka
                if (hargaInput) {
                    const hargaValue = hargaInput.value.trim();

                    if (hargaValue !== '' && !/^\d+(\.\d{1,2})?$/.test(hargaValue)) {
                        isValid = false;
                        // Buat atau perbarui pesan kesalahan
                        let errorDiv = hargaInput.nextElementSibling;
                        if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                            errorDiv = document.createElement('div');
                            errorDiv.className = 'invalid-feedback';
                            hargaInput.parentNode.insertBefore(errorDiv, hargaInput.nextSibling);
                        }

                        errorDiv.textContent = 'Harga harus berupa angka.';
                        errorDiv.style.display = 'block';
                        hargaInput.classList.add('is-invalid');
                    } else {
                        // Hapus kesalahan jika valid
                        hargaInput.classList.remove('is-invalid');
                        const errorDiv = hargaInput.nextElementSibling;
                        if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                            errorDiv.style.display = 'none';
                        }
                    }
                }

                // Mencegah pengiriman form jika validasi gagal
                if (!isValid) {
                    e.preventDefault();
                }
            });
        }

        // Validasi real-time untuk input nomor telepon
        if (nomorTelpInput) {
            nomorTelpInput.addEventListener('input', function() {
                // Hapus karakter non-numerik saat diketik
                this.value = this.value.replace(/[^0-9]/g, '');

                // Batasi hingga 13 digit
                if (this.value.length > 13) {
                    this.value = this.value.slice(0, 13);
                }
            });
        }

        // Validasi real-time untuk input harga
        if (hargaInput) {
            hargaInput.addEventListener('input', function() {
                // Hanya izinkan angka dan titik desimal
                this.value = this.value.replace(/[^0-9.]/g, '');

                // Pastikan hanya ada satu titik desimal
                const parts = this.value.split('.');
                if (parts.length > 2) {
                    this.value = parts[0] + '.' + parts.slice(1).join('');
                }
            });
        }
    });
</script>
