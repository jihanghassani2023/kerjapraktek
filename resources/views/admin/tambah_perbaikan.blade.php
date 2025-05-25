<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Perbaikan - MG TECH</title>
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
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

        .alert-success {
            background-color: #d4edda;
            border-color: #28a745;
            color: #155724;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .text-primary {
            color: #8c3a3a;
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

        /* Autocomplete styling */
        .autocomplete-container {
            position: relative;
        }

        .autocomplete-results {
            position: absolute;
            background-color: white;
            border: 1px solid #ced4da;
            border-top: none;
            border-radius: 0 0 4px 4px;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .autocomplete-results.show {
            display: block;
        }

        .autocomplete-item {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
        }

        .autocomplete-item:hover {
            background-color: #f8f9fa;
        }

        .autocomplete-item.selected {
            background-color: #e9ecef;
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
        <a href="{{ route('admin.transaksi') }}" class="menu-item">
            <i class="fas fa-exchange-alt"></i>
            <span>Transaksi</span>
        </a>
        <a href="{{ route('admin.pelanggan') }}" class="menu-item">
            <i class="fas fa-users"></i>
            <span>Pelanggan</span>
        </a>
        <a href="{{ route('admin.perbaikan.create') }}" class="menu-item active">
            <i class="fas fa-tools"></i>
            <span>Tambah Perbaikan</span>
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
                <h2>Tambah Perbaikan</h2>
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

        <div style="margin-top: 1%"></div>

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

        <div class="content-section">
            <form id="perbaikanForm" action="{{ route('admin.perbaikan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                <input type="hidden" name="pelanggan_id" id="pelanggan_id" value="{{ old('pelanggan_id') }}">
                <input type="hidden" name="nomor_telp" id="nomor_telp" value="{{ old('nomor_telp') }}">
                <input type="hidden" name="email" id="email" value="{{ old('email') }}">

                <div class="form-group">
                    <label for="nama_pelanggan">Nama Pelanggan</label>
                    <div class="autocomplete-container">
                        <input type="text" id="nama_pelanggan" name="nama_pelanggan" class="form-control"
                            placeholder="Ketik nama pelanggan..." value="{{ old('nama_pelanggan') }}" required>
                        <div id="autocompleteResults" class="autocomplete-results"></div>
                    </div>

                    @error('nama_pelanggan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="user_id">Pilih Teknisi</label>
                    <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id"
                        required>
                        <option value="">-- Pilih Teknisi --</option>
                        @foreach ($teknisi as $t)
                            <option value="{{ $t->id }}" {{ old('user_id') == $t->id ? 'selected' : '' }}>
                                {{ $t->name }} - {{ $t->jabatan }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nama_device">Nama Device</label>
                    <input type="text" id="nama_device" name="nama_device"
                        class="form-control @error('nama_device') is-invalid @enderror"
                        value="{{ old('nama_device') }}" required>
                    @error('nama_device')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="kategori_device">Kategori Device</label>
                    <select id="kategori_device" name="kategori_device"
                        class="form-control @error('kategori_device') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="iPhone" {{ old('kategori_device') == 'iPhone' ? 'selected' : '' }}>
                            iPhone</option>
                        <option value="iWatch" {{ old('kategori_device') == 'iWatch' ? 'selected' : '' }}>
                            iWatch</option>
                        <option value="Macbook" {{ old('kategori_device') == 'Macbook' ? 'selected' : '' }}>
                            Macbook</option>
                        <option value="iPad" {{ old('kategori_device') == 'iPad' ? 'selected' : '' }}>
                            iPad</option>
                    </select>
                    @error('kategori_device')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="masalah">Masalah</label>
                    <textarea id="masalah" name="masalah" class="form-control @error('masalah') is-invalid @enderror" required>{{ old('masalah') }}</textarea>
                    @error('masalah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tindakan_perbaikan">Tindakan Perbaikan</label>
                    <textarea id="tindakan_perbaikan" name="tindakan_perbaikan"
                        class="form-control @error('tindakan_perbaikan') is-invalid @enderror" required>{{ old('tindakan_perbaikan') }}</textarea>
                    @error('tindakan_perbaikan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="harga">Harga (Rp)</label>
                    <input type="number" id="harga" name="harga"
                        class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga') }}"
                        required>
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="garansi">Garansi</label>
                    <select id="garansi" name="garansi" class="form-control @error('garansi') is-invalid @enderror"
                        required>
                        <option value="">-- Pilih Garansi --</option>
                        <option value="1 Bulan" {{ old('garansi') == '1 Bulan' ? 'selected' : '' }}>
                            1 Bulan
                        </option>
                        <option value="12 Bulan" {{ old('garansi') == '12 Bulan' ? 'selected' : '' }}>
                            12 Bulan
                        </option>
                    </select>
                    @error('garansi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div style="text-align: right;">
                    <button type="submit" id="submitBtn" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('perbaikanForm');
            const submitBtn = document.getElementById('submitBtn');
            const namaPelangganInput = document.getElementById('nama_pelanggan');
            const pelangganIdInput = document.getElementById('pelanggan_id');
            const nomorTelpInput = document.getElementById('nomor_telp');
            const emailInput = document.getElementById('email');
            const autocompleteResults = document.getElementById('autocompleteResults');

            // CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Store all customers from database
            let allCustomers = [];

            // Fetch customers from the database when page loads
            fetchCustomers();

            // Function to fetch customers from the database
            function fetchCustomers() {
                fetch('{{ route('admin.api.customers') }}', {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        allCustomers = data;
                    })
                    .catch(error => {
                        console.error('Error fetching customers:', error);
                    });
            }

            // Filter function for customer search
            function filterCustomers(query) {
                if (!query) return [];

                query = query.toLowerCase();
                return allCustomers.filter(customer =>
                    customer.nama_pelanggan.toLowerCase().includes(query)
                );
            }

            // Function to display autocomplete results
            function displayAutocompleteResults(results) {
                autocompleteResults.innerHTML = '';

                if (results.length === 0) {
                    // Show a message directing to register customer first
                    const item = document.createElement('div');
                    item.className = 'autocomplete-item';
                    item.textContent = 'Pelanggan tidak ditemukan. Silakan daftarkan pelanggan terlebih dahulu.';
                    item.style.color = '#dc3545';

                    autocompleteResults.appendChild(item);
                    autocompleteResults.classList.add('show');

                    // Clear hidden fields to ensure data isn't submitted
                    pelangganIdInput.value = '';
                    nomorTelpInput.value = '';
                    emailInput.value = '';

                    return;
                }

                results.forEach(customer => {
                    const item = document.createElement('div');
                    item.className = 'autocomplete-item';
                    item.innerHTML = `<strong>${customer.nama_pelanggan}</strong>`;

                    item.addEventListener('click', function() {
                        selectCustomer(customer);
                    });

                    autocompleteResults.appendChild(item);
                });

                autocompleteResults.classList.add('show');
            }

            // Function to select a customer
            function selectCustomer(customer) {
                namaPelangganInput.value = customer.nama_pelanggan;
                pelangganIdInput.value = customer.id;
                nomorTelpInput.value = customer.nomor_telp;
                emailInput.value = customer.email || '';

                // Hide autocomplete results
                autocompleteResults.classList.remove('show');
            }

            // Customer name input event
            namaPelangganInput.addEventListener('input', function() {
                const query = this.value.trim();

                if (query.length >= 1) { // Show suggestions after typing at least 1 character
                    const results = filterCustomers(query);
                    displayAutocompleteResults(results);
                } else {
                    autocompleteResults.classList.remove('show');
                }
            });

            // Tambahkan kode ini pada bagian input event 'blur' (ketika input field kehilangan fokus)
            namaPelangganInput.addEventListener('blur', function() {
                const query = this.value.trim();

                if (query.length >= 1) {
                    // Cari kecocokan pelanggan dari data yang sudah diambil
                    const matchingCustomers = allCustomers.filter(customer =>
                        customer.nama_pelanggan.toLowerCase() === query.toLowerCase()
                    );

                    // Jika ditemukan pelanggan yang cocok persis
                    if (matchingCustomers.length === 1) {
                        selectCustomer(matchingCustomers[0]);
                    }
                }
            });

            // Close autocomplete results when clicking outside
            document.addEventListener('click', function(event) {
                if (!autocompleteResults.contains(event.target) && event.target !== namaPelangganInput) {
                    autocompleteResults.classList.remove('show');
                }
            });

            // Submit form handler - PENTING: Hanya validasi form, biarkan form submit secara normal
            if (submitBtn) {
                submitBtn.addEventListener('click', function(event) {
                    if (!validateForm()) {
                        event.preventDefault();
                        alert('Silakan lengkapi semua field yang diperlukan.');
                        return;
                    }
                    // Jika validasi lolos, form akan di-submit secara normal
                    form.submit();
                });
            }

            // Form submit event - sebagai backup jika button handler tidak berfungsi
            form.addEventListener('submit', function(event) {
                if (!validateForm()) {
                    event.preventDefault();
                    alert('Silakan lengkapi semua field yang diperlukan.');
                    return;
                }
                // Jika validasi lolos, form akan di-submit secara normal
            });

            // Function to validate form
            function validateForm() {
                let isValid = true;

                // Check if customer is selected (must have a pelanggan_id)
                if (!pelangganIdInput.value) {
                    namaPelangganInput.style.borderColor = 'red';
                    alert(
                        'Pelanggan tidak ditemukan. Silakan pilih pelanggan dari daftar atau daftarkan pelanggan baru terlebih dahulu.'
                    );
                    isValid = false;
                } else {
                    namaPelangganInput.style.borderColor = '';
                }

                // Check required fields (excluding hidden fields)
                const requiredFields = ['nama_pelanggan', 'user_id', 'nama_device', 'masalah', 'tindakan_perbaikan',
                    'harga', 'garansi'
                ];
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input.value.trim()) {
                        input.style.borderColor = 'red';
                        isValid = false;
                    } else {
                        input.style.borderColor = '';
                    }
                });

                return isValid;
            }

            // Numeric validation for harga
            const hargaInput = document.getElementById('harga');
            if (hargaInput) {
                hargaInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
        });
    </script>
</body>

</html>
