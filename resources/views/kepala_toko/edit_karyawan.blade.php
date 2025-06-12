<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit User - MG TECH</title>
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
        .form-control:disabled {
            background-color: #e9ecef;
        }
        .form-control.is-invalid {
            border-color: #dc3545;
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
        .name-requirements {
            font-size: 0.85em;
            color: #666;
            margin-top: 5px;
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
        <a href="{{ route('kepala-toko.dashboard') }}" class="menu-item">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('karyawan.index') }}" class="menu-item active">
            <i class="fas fa-users"></i>
            <span>User</span>
        </a>
        <a href="{{ route('transaksi.index') }}" class="menu-item">
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
                <h2>Edit User</h2>
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
            <h1 class="page-title">Edit User</h1>
            <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
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

        <div class="content-section">
            <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST" id="editUserForm" novalidate>
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="karyawan_id">ID User</label>
                    <input type="text" class="form-control" id="karyawan_id" value="{{ $karyawan->id }}" readonly disabled>
                    <input type="hidden" name="karyawan_id" value="{{ $karyawan->id }}">
                </div>

                <div class="form-group">
                    <label for="name">Nama User</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $karyawan->name) }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="name-error" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat">{{ old('alamat', $karyawan->alamat) }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="alamat-error" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <select class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan">
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="Kepala Toko" {{ old('jabatan', $karyawan->jabatan) == 'Kepala Toko' ? 'selected' : '' }}>Kepala Toko</option>
                        <option value="Kepala Teknisi" {{ old('jabatan', $karyawan->jabatan) == 'Kepala Teknisi' ? 'selected' : '' }}>Kepala Teknisi</option>
                        <option value="Teknisi" {{ old('jabatan', $karyawan->jabatan) == 'Teknisi' ? 'selected' : '' }}>Teknisi</option>
                        <option value="Admin" {{ old('jabatan', $karyawan->jabatan) == 'Admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('jabatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="jabatan-error" style="display: none;"></div>
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
            const form = document.getElementById('editUserForm');
            const nameInput = document.getElementById('name');

            // Error elements
            const errorElements = {
                'name': document.getElementById('name-error'),
                'alamat': document.getElementById('alamat-error'),
                'jabatan': document.getElementById('jabatan-error')
            };

            // Validasi nama - hanya huruf dan spasi
            nameInput.addEventListener('input', function(e) {
                let value = e.target.value;
                // Hapus karakter yang bukan huruf, spasi, atau tanda baca umum dalam nama
                let cleanedValue = value.replace(/[^a-zA-Z\s\.']/g, '');

                if (value !== cleanedValue) {
                    e.target.value = cleanedValue;
                    showError('name', 'Nama hanya boleh berisi huruf dan spasi.');
                } else if (cleanedValue.trim()) {
                    hideError('name');
                }
            });

            // Prevent paste dengan angka di nama
            nameInput.addEventListener('paste', function(e) {
                e.preventDefault();
                let paste = (e.clipboardData || window.clipboardData).getData('text');
                let cleanedPaste = paste.replace(/[^a-zA-Z\s\.']/g, '');

                // Get current cursor position
                let start = this.selectionStart;
                let end = this.selectionEnd;
                let currentValue = this.value;

                // Insert cleaned paste at cursor position
                this.value = currentValue.slice(0, start) + cleanedPaste + currentValue.slice(end);

                // Set cursor position after pasted text
                this.setSelectionRange(start + cleanedPaste.length, start + cleanedPaste.length);

                if (paste !== cleanedPaste) {
                    showError('name', 'Nama hanya boleh berisi huruf dan spasi.');
                }
            });

            // Fungsi untuk menampilkan error
            function showError(fieldName, message) {
                const field = document.getElementById(fieldName);
                const errorDiv = errorElements[fieldName];

                if (field) {
                    field.classList.add('is-invalid');
                    field.scrollIntoView({ behavior: 'smooth', block: 'center' });
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

            // Validasi nama - hanya huruf dan spasi
            function isValidName(name) {
                const nameRegex = /^[a-zA-Z\s\.\']+$/;
                return nameRegex.test(name);
            }

            // Add input event listeners to hide errors when typing/selecting
            ['name', 'alamat', 'jabatan'].forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field && fieldName !== 'name') { // name sudah dihandle di atas
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
                    { name: 'name', message: 'Nama user wajib diisi.' },
                    { name: 'alamat', message: 'Alamat wajib diisi.' },
                    { name: 'jabatan', message: 'Jabatan wajib dipilih.' }
                ];

                requiredFields.forEach(field => {
                    const input = document.getElementById(field.name);
                    if (input && !input.value.trim()) {
                        isValid = false;
                        showError(field.name, field.message);
                        if (!firstErrorField) firstErrorField = input;
                    }
                });

                // Validate nama format
                const nameInput = document.getElementById('name');
                if (nameInput && nameInput.value.trim()) {
                    if (!isValidName(nameInput.value.trim())) {
                        isValid = false;
                        showError('name', 'Nama hanya boleh berisi huruf dan spasi.');
                        if (!firstErrorField) firstErrorField = nameInput;
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
