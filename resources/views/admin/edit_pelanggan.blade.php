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
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 5px;
            display: block;
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
        </div>
        <a href="{{ route('admin.dashboard') }}" class="menu-item">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.transaksi') }}" class="menu-item">
            <i class="fas fa-chart-bar"></i>
            <span>Laporan</span>
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
            <form action="{{ route('admin.pelanggan.update', $pelanggan->id) }}" method="POST" id="editForm" novalidate>
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nama_pelanggan">Nama Pelanggan</label>
                    <input type="text" class="form-control @error('nama_pelanggan') is-invalid @enderror" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}">
                    @error('nama_pelanggan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="nama-error" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="nomor_telp">Nomor Telepon</label>
                    <input type="tel" class="form-control @error('nomor_telp') is-invalid @enderror" id="nomor_telp" name="nomor_telp" value="{{ old('nomor_telp', $pelanggan->nomor_telp) }}" maxlength="13">
                    @error('nomor_telp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="telp-error" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="email">Email (Opsional)</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $pelanggan->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="email-error" style="display: none;"></div>
                </div>

                <div style="text-align: right;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editForm');
            const namaInput = document.getElementById('nama_pelanggan');
            const nomorTelpInput = document.getElementById('nomor_telp');
            const emailInput = document.getElementById('email');
            const namaError = document.getElementById('nama-error');
            const telpError = document.getElementById('telp-error');
            const emailError = document.getElementById('email-error');

            // Fungsi untuk menampilkan error
            function showError(input, errorDiv, message) {
                input.classList.add('is-invalid');
                errorDiv.textContent = message;
                errorDiv.style.display = 'block';
                input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                input.focus();
            }

            // Fungsi untuk menghilangkan error
            function hideError(input, errorDiv) {
                input.classList.remove('is-invalid');
                errorDiv.style.display = 'none';
            }

            // Validasi real-time untuk nama pelanggan
            if (namaInput) {
                namaInput.addEventListener('input', function() {
                    let value = this.value;

                    // Hapus angka dari input nama
                    const cleanValue = value.replace(/[0-9]/g, '');

                    // Jika ada perubahan (ada angka yang dihapus), update value
                    if (value !== cleanValue) {
                        this.value = cleanValue;
                        // Tampilkan error sementara untuk memberi tahu user
                        showError(this, namaError, 'Nama hanya boleh diisi dengan huruf.');
                        setTimeout(() => {
                            if (cleanValue.trim().length > 0) {
                                hideError(this, namaError);
                            }
                        }, 2000); // Error hilang setelah 2 detik
                    }

                    // Batasi maksimal 50 karakter
                    if (this.value.length > 50) {
                        this.value = this.value.slice(0, 50);
                        showError(this, namaError, 'Nama pelanggan maksimal 50 karakter.');
                    }

                    // Hapus error jika input mulai valid
                    if (this.value.trim().length > 0 && !/[0-9]/.test(this.value)) {
                        hideError(this, namaError);
                    }
                });

                // Validasi lengkap saat blur
                namaInput.addEventListener('blur', function() {
                    const value = this.value.trim();
                    if (value && /[0-9]/.test(value)) {
                        showError(this, namaError, 'Nama hanya boleh diisi dengan huruf.');
                    } else if (value && value.length < 2) {
                        showError(this, namaError, 'Nama pelanggan minimal 2 karakter.');
                    } else if (value && value.length > 50) {
                        showError(this, namaError, 'Nama pelanggan maksimal 50 karakter.');
                    }
                });
            }

            // Validasi real-time untuk nomor telepon
            if (nomorTelpInput) {
                nomorTelpInput.addEventListener('input', function() {
                    // Hapus karakter non-numerik
                    this.value = this.value.replace(/[^0-9]/g, '');

                    // Batasi hingga 13 digit
                    if (this.value.length > 13) {
                        this.value = this.value.slice(0, 13);
                    }

                    // Hapus error jika input mulai valid
                    if (this.value.length > 0) {
                        hideError(this, telpError);
                    }
                });

                // Validasi lengkap saat blur
                nomorTelpInput.addEventListener('blur', function() {
                    const value = this.value.trim();
                    if (value && (value.length < 8 || value.length > 13)) {
                        showError(this, telpError, 'Nomor telepon harus 8-13 digit.');
                    } else if (value && !/^[0-9]+$/.test(value)) {
                        showError(this, telpError, 'Nomor telepon hanya boleh berisi angka.');
                    }
                });
            }

            // Validasi email
            if (emailInput) {
                emailInput.addEventListener('blur', function() {
                    const value = this.value.trim();
                    if (value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                        showError(this, emailError, 'Format email tidak valid.');
                    } else {
                        hideError(this, emailError);
                    }
                });

                emailInput.addEventListener('input', function() {
                    if (this.value.trim().length > 0) {
                        hideError(this, emailError);
                    }
                });
            }

            // Validasi form sebelum submit
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // Selalu prevent default dulu

                    let isValid = true;
                    let firstErrorField = null;

                    // Reset semua error
                    hideError(namaInput, namaError);
                    hideError(nomorTelpInput, telpError);
                    hideError(emailInput, emailError);

                    // Validasi nama pelanggan
                    const namaValue = namaInput.value.trim();
                    if (!namaValue || namaValue.length === 0) {
                        isValid = false;
                        showError(namaInput, namaError, 'Nama pelanggan wajib diisi.');
                        if (!firstErrorField) firstErrorField = namaInput;
                    } else if (/[0-9]/.test(namaValue)) {
                        isValid = false;
                        showError(namaInput, namaError, 'Nama hanya boleh diisi dengan huruf.');
                        if (!firstErrorField) firstErrorField = namaInput;
                    } else if (namaValue.length < 2) {
                        isValid = false;
                        showError(namaInput, namaError, 'Nama pelanggan minimal 2 karakter.');
                        if (!firstErrorField) firstErrorField = namaInput;
                    } else if (namaValue.length > 50) {
                        isValid = false;
                        showError(namaInput, namaError, 'Nama pelanggan maksimal 50 karakter.');
                        if (!firstErrorField) firstErrorField = namaInput;
                    }

                    // Validasi nomor telepon
                    const phoneValue = nomorTelpInput.value.trim();
                    if (!phoneValue) {
                        isValid = false;
                        showError(nomorTelpInput, telpError, 'Nomor telepon wajib diisi.');
                        if (!firstErrorField) firstErrorField = nomorTelpInput;
                    } else if (!/^[0-9]{8,13}$/.test(phoneValue)) {
                        isValid = false;
                        showError(nomorTelpInput, telpError, 'Nomor telepon harus 8-13 digit dan hanya berisi angka.');
                        if (!firstErrorField) firstErrorField = nomorTelpInput;
                    }

                    // Validasi email (opsional)
                    const emailValue = emailInput.value.trim();
                    if (emailValue && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue)) {
                        isValid = false;
                        showError(emailInput, emailError, 'Format email tidak valid.');
                        if (!firstErrorField) firstErrorField = emailInput;
                    }

                    // Jika validasi berhasil, submit form
                    if (isValid) {
                        form.submit();
                    } else {
                        // Focus ke field error pertama
                        if (firstErrorField) {
                            firstErrorField.focus();
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
