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

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #8c3a3a;
            box-shadow: 0 0 0 0.2rem rgba(140, 58, 58, 0.25);
        }

        .form-control:disabled {
            background-color: #f0f0f0;
            cursor: not-allowed;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .status-select {
            cursor: pointer;
            font-weight: bold;
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
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            border: none;
            font-weight: bold;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: background-color 0.3s;
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
            background-color: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert-success {
            background-color: #e7f9e7;
            color: #28a745;
            border: 1px solid #d0f0d0;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 5px;
            display: block;
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

        /* Required field indicator */
        .form-group label.required::after {
            content: " *";
            color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('img/Mg-Tech.png') }}" alt="MG Tech Logo"
                onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\' viewBox=\'0 0 80 80\'><rect width=\'80\' height=\'80\' fill=\'%238c3a3a\'/><text x=\'50%\' y=\'50%\' font-size=\'30\' text-anchor=\'middle\' fill=\'white\' font-family=\'Arial\' dominant-baseline=\'middle\'>MG</text></svg>'">
        </div>
        <a href="{{ route('teknisi.dashboard') }}" class="menu-item">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
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
            <button type="submit" class="logout"
                style="width: 100%; border: none; cursor: pointer; background: none; text-align: left;">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>

    <div class="main-content">
        <div class="header">
            <h1 class="page-title">Edit Perbaikan <span>TEKNISI</span></h1>
            <div class="user-info">
                <div class="user-name">
                    <div>{{ $user->name }}</div>
                    <div class="user-role">{{ $user->role }}</div>
                </div>
                <div class="user-avatar">
                    <img src="{{ asset('img/user.png') }}" alt="User"
                        onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\' viewBox=\'0 0 40 40\'><circle cx=\'20\' cy=\'20\' r=\'20\' fill=\'%23f5f5f5\'/><text x=\'50%\' y=\'50%\' font-size=\'20\' text-anchor=\'middle\' fill=\'%238c3a3a\' font-family=\'Arial\' dominant-baseline=\'middle\'>{{ substr($user->name, 0, 1) }}</text></svg>'">
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terdapat kesalahan pada form:</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
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
                @if ($perbaikan->pelanggan->email)
                    <p><strong>Email:</strong> {{ $perbaikan->pelanggan->email }}</p>
                @endif
            </div>
        </div>

        <div class="form-container">
            <h2 class="form-header">Form Edit Perbaikan</h2>
            <form action="{{ route('perbaikan.update', $perbaikan->id) }}" method="POST" id="editPerbaikanForm" novalidate>
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="id">Kode Perbaikan</label>
                    <input type="text" id="id" class="form-control" value="{{ $perbaikan->id }}" disabled>
                </div>

                <div class="form-group">
                    <label for="nama_device">Nama Device</label>
                    <input type="text" id="nama_device" class="form-control" value="{{ $perbaikan->nama_device }}"
                        disabled>
                </div>

                <div class="form-group">
                    <label for="kategori_device" class="required">Kategori Device</label>
                    <select id="kategori_device" name="kategori_device"
                        class="form-control @error('kategori_device') is-invalid @enderror">
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
                    <div class="invalid-feedback" id="kategori-device-error" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="tanggal_perbaikan">Tanggal Perbaikan</label>
                    <input type="text" id="tanggal_perbaikan" class="form-control"
                        value="{{ \App\Helpers\DateHelper::formatTanggalIndonesia($perbaikan->tanggal_perbaikan) }}"
                        disabled>
                </div>

                <div class="form-group">
                    <label for="masalah" class="required">Keterangan Masalah</label>
                    <textarea id="masalah" name="masalah" class="form-control @error('masalah') is-invalid @enderror">{{ old('masalah', $perbaikan->masalah) }}</textarea>
                    @error('masalah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="masalah-error" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="tindakan_perbaikan" class="required">Tindakan Perbaikan</label>
                    <textarea id="tindakan_perbaikan" name="tindakan_perbaikan"
                        class="form-control @error('tindakan_perbaikan') is-invalid @enderror">{{ old('tindakan_perbaikan', $perbaikan->tindakan_perbaikan) }}</textarea>
                    @error('tindakan_perbaikan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="tindakan-perbaikan-error" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="harga" class="required">Harga (Rp)</label>
                    <input type="number" id="harga" name="harga"
                        class="form-control @error('harga') is-invalid @enderror"
                        value="{{ old('harga', $perbaikan->harga) }}">
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="harga-error" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="garansi" class="required">Garansi</label>
                    <select id="garansi" name="garansi"
                        class="form-control @error('garansi') is-invalid @enderror">
                        <option value="">-- Pilih Garansi --</option>
                        <option value="1 Bulan"
                            {{ old('garansi', $perbaikan->garansi) == '1 Bulan' ? 'selected' : '' }}>
                            1 Bulan
                        </option>
                        <option value="12 Bulan"
                            {{ old('garansi', $perbaikan->garansi) == '12 Bulan' ? 'selected' : '' }}>
                            12 Bulan
                        </option>
                    </select>
                    @error('garansi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="garansi-error" style="display: none;"></div>
                </div>

                <div class="form-footer">
                    <a href="{{ route('perbaikan.show', $perbaikan->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editPerbaikanForm');

            // Error elements
            const errorElements = {
                'kategori_device': document.getElementById('kategori-device-error'),
                'masalah': document.getElementById('masalah-error'),
                'tindakan_perbaikan': document.getElementById('tindakan-perbaikan-error'),
                'harga': document.getElementById('harga-error'),
                'garansi': document.getElementById('garansi-error')
            };

            // Fungsi untuk menampilkan error
            function showError(fieldName, message) {
                const field = document.getElementById(fieldName);
                const errorDiv = errorElements[fieldName];

                if (field) {
                    field.classList.add('is-invalid');
                    field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    field.focus();
                }

                if (errorDiv) {
                    errorDiv.textContent = message;
                    errorDiv.style.display = 'block';
                }
            }

            // Fungsi untuk menghilangkan error
            function hideError(fieldName) {
                const field = document.getElementById(fieldName);
                const errorDiv = errorElements[fieldName];

                if (field) {
                    field.classList.remove('is-invalid');
                }

                if (errorDiv) {
                    errorDiv.style.display = 'none';
                }
            }

            // Add input event listeners to hide errors when typing/selecting
            ['kategori_device', 'masalah', 'tindakan_perbaikan', 'harga', 'garansi'].forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field) {
                    field.addEventListener('input', function() {
                        if (this.value.trim()) {
                            hideError(fieldName);
                        }
                    });

                    // For select elements, also listen to change event
                    if (field.tagName === 'SELECT') {
                        field.addEventListener('change', function() {
                            if (this.value.trim()) {
                                hideError(fieldName);
                            }
                        });
                    }
                }
            });

            // Numeric validation for harga
            const hargaInput = document.getElementById('harga');
            if (hargaInput) {
                hargaInput.addEventListener('input', function() {
                    // Remove non-numeric characters
                    this.value = this.value.replace(/[^0-9]/g, '');

                    // Hide error if value is entered
                    if (this.value.trim()) {
                        hideError('harga');
                    }
                });
            }

            // Form submit handler
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Always prevent default

                let isValid = true;
                let firstErrorField = null;

                // Reset all errors
                Object.keys(errorElements).forEach(fieldName => {
                    hideError(fieldName);
                });

                // Validate required fields
                const requiredFields = [
                    { name: 'kategori_device', message: 'Kategori device wajib dipilih.' },
                    { name: 'masalah', message: 'Keterangan masalah wajib diisi.' },
                    { name: 'tindakan_perbaikan', message: 'Tindakan perbaikan wajib diisi.' },
                    { name: 'harga', message: 'Harga wajib diisi.' },
                    { name: 'garansi', message: 'Garansi wajib dipilih.' }
                ];

                requiredFields.forEach(field => {
                    const input = document.getElementById(field.name);
                    if (input && !input.value.trim()) {
                        isValid = false;
                        showError(field.name, field.message);
                        if (!firstErrorField) firstErrorField = input;
                    }
                });

                // Validate harga is number and > 0
                const hargaInput = document.getElementById('harga');
                if (hargaInput && hargaInput.value.trim()) {
                    const hargaValue = parseFloat(hargaInput.value);
                    if (isNaN(hargaValue) || hargaValue <= 0) {
                        isValid = false;
                        showError('harga', 'Harga harus berupa angka yang valid dan lebih dari 0.');
                        if (!firstErrorField) firstErrorField = hargaInput;
                    }
                }

                // If validation passes, submit form
                if (isValid) {
                    form.submit();
                } else {
                    if (firstErrorField) {
                        firstErrorField.focus();
                    }
                }
            });
        });
    </script>
</body>

</html>
