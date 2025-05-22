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
         .timeline {
        position: relative;
        margin-left: 20px;
        padding-left: 20px;
        border-left: 2px solid #e0e0e0;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 15px;
        padding-bottom: 15px;
    }
    .timeline-marker {
        position: absolute;
        left: -31px;
        width: 20px;
        height: 20px;
        color: #8c3a3a;
        background: white;
        border-radius: 50%;
        text-align: center;
        line-height: 20px;
    }
    .timeline-content {
        padding-left: 10px;
    }
    .timeline-title {
        font-size: 16px;
        margin-bottom: 5px;
    }
    .timeline-date {
        font-size: 14px;
        color: #666;
        margin: 0;
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
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

        .form-control:disabled {
            background-color: #e9ecef;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        select.form-control {
            cursor: pointer;
            padding-right: 30px;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
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

        .form-footer {
            text-align: right;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .customer-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #eee;
        }

        .customer-title {
            font-size: 16px;
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

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 5px;
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
            <i class="fas fa-exchange-alt"></i>
            <span>Transaksi</span>
        </a>
        <a href="{{ route('admin.pelanggan') }}" class="menu-item">
            <i class="fas fa-users"></i>
            <span>Pelanggan</span>
        </a>
        <a href="{{ route('admin.perbaikan.create') }}" class="menu-item">
            <i class="fas fa-tools"></i>
            <span>Tambah Perbaikan</span>
        </a>

        <a href="{{ route('admin.transaksi.show', $perbaikan->id) }}" class="back-btn">
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
            <h1 class="page-title">Edit Perbaikan</h1>
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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="content-wrapper">
            <div class="content-header">
                <h2 class="content-title">Edit Perbaikan #{{ $perbaikan->id }}</h2>
            </div>

            <div class="customer-info">
                <div class="customer-title">Informasi Pelanggan</div>
                <div class="customer-details">
                    <p><strong>Nama:</strong> {{ $perbaikan->pelanggan->nama_pelanggan }}</p>
                    <p><strong>No. Telepon:</strong> {{ $perbaikan->pelanggan->nomor_telp }}</p>
                    @if ($perbaikan->pelanggan->email)
                        <p><strong>Email:</strong> {{ $perbaikan->pelanggan->email }}</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Perbaikan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.perbaikan.update', $perbaikan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="id">Kode Perbaikan</label>
                            <input type="text" id="id" class="form-control"
                                value="{{ $perbaikan->id }}" disabled>
                        </div>

                        <div class="form-group">
                            <label for="nama_device">Nama Device</label>
                            <input type="text" id="nama_device" name="nama_device"
                                class="form-control @error('nama_device') is-invalid @enderror"
                                value="{{ old('nama_device', $perbaikan->nama_device) }}" required>
                            @error('nama_device')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                       <div class="form-group">
    <label for="kategori_device">Kategori Device</label>
    <select id="kategori_device" name="kategori_device"
        class="form-control @error('kategori_device') is-invalid @enderror" required>
        <option value="">-- Pilih Kategori --</option>
        <option value="iPhone"
            {{ old('kategori_device', $perbaikan->kategori_device) == 'iPhone' ? 'selected' : '' }}>
            iPhone</option>
        <option value="iWatch"
            {{ old('kategori_device', $perbaikan->kategori_device) == 'iWatch' ? 'selected' : '' }}>
            iWatch</option>
        <option value="Macbook"
            {{ old('kategori_device', $perbaikan->kategori_device) == 'Macbook' ? 'selected' : '' }}>
            Macbook</option>
        <option value="iPad"
            {{ old('kategori_device', $perbaikan->kategori_device) == 'iPad' ? 'selected' : '' }}>
            iPad</option>
    </select>
    @error('kategori_device')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                        <div class="form-group">
                            <label for="tanggal_perbaikan">Tanggal Perbaikan</label>
                            <input type="text" id="tanggal_perbaikan" class="form-control"
                                value="{{ \Carbon\Carbon::parse($perbaikan->tanggal_perbaikan)->format('d F Y') }}"
                                disabled>
                        </div>

                        <div class="form-group">
                            <label for="masalah">Masalah</label>
                            <textarea id="masalah" name="masalah" class="form-control @error('masalah') is-invalid @enderror" required>{{ old('masalah', $perbaikan->masalah) }}</textarea>
                            @error('masalah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tindakan_perbaikan">Tindakan Perbaikan</label>
                            <textarea id="tindakan_perbaikan" name="tindakan_perbaikan"
                                class="form-control @error('tindakan_perbaikan') is-invalid @enderror" required>{{ old('tindakan_perbaikan', $perbaikan->tindakan_perbaikan) }}</textarea>
                            @error('tindakan_perbaikan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>



                        <div class="form-group">
                            <label for="harga">Harga (Rp)</label>
                            <input type="number" id="harga" name="harga"
                                class="form-control @error('harga') is-invalid @enderror"
                                value="{{ old('harga', $perbaikan->harga) }}" required>
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="garansi">Garansi</label>
                            <input type="text" id="garansi" name="garansi"
                                class="form-control @error('garansi') is-invalid @enderror"
                                value="{{ old('garansi', $perbaikan->garansi) }}" required>
                            @error('garansi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="form-footer">
                            <a href="{{ route('admin.transaksi.show', $perbaikan->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validation for numeric input
            const hargaInput = document.getElementById('harga');
            if (hargaInput) {
                hargaInput.addEventListener('input', function() {
                    // Remove non-numeric characters
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
        });
    </script>
</body>

</html>
