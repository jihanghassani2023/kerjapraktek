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

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.875em;
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

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
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

        /* Garansi Container */
        .garansi-container {
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 15px;
            background-color: #f9f9f9;
        }

        .garansi-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .garansi-title {
            font-weight: bold;
            color: #333;
        }

        .garansi-item {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: start;
        }

        .garansi-item:last-child {
            margin-bottom: 0;
        }

        .garansi-sparepart {
            flex: 1;
        }

        .garansi-periode {
            flex: 1;
        }

        .garansi-actions {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .garansi-item input {
            margin-bottom: 0;
        }

        .garansi-item label {
            margin-bottom: 5px;
            font-size: 0.9em;
            color: #666;
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

            .garansi-item {
                flex-direction: column;
            }

            .garansi-actions {
                align-self: flex-end;
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
        <a href="{{ route('admin.transaksi') }}" class="menu-item">
            <i class="fas fa-chart-bar"></i>
            <span>Laporan</span>
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
            <form id="perbaikanForm" action="{{ route('admin.perbaikan.store') }}" method="POST" novalidate>
                @csrf
                <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                <input type="hidden" name="pelanggan_id" id="pelanggan_id" value="{{ old('pelanggan_id') }}">
                <input type="hidden" name="nomor_telp" id="nomor_telp" value="{{ old('nomor_telp') }}">
                <input type="hidden" name="email" id="email" value="{{ old('email') }}">

                <div class="form-group">
                    <label for="nama_pelanggan">Nama Pelanggan</label>
                    <div class="autocomplete-container">
                        <input type="text" id="nama_pelanggan" name="nama_pelanggan" class="form-control"
                            placeholder="Ketik nama pelanggan..." value="{{ old('nama_pelanggan') }}">
                        <div id="autocompleteResults" class="autocomplete-results"></div>
                    </div>
                    @error('nama_pelanggan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="nama-pelanggan-error" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="user_id">Pilih Teknisi</label>
                    <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
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
                    <div class="invalid-feedback" id="user-id-error" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="nama_device">Nama Device</label>
                    <input type="text" id="nama_device" name="nama_device"
                        class="form-control @error('nama_device') is-invalid @enderror"
                        value="{{ old('nama_device') }}">
                    @error('nama_device')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="nama-device-error" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="kategori_device">Kategori Device</label>
                    <select id="kategori_device" name="kategori_device"
                        class="form-control @error('kategori_device') is-invalid @enderror">
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
                    <div class="invalid-feedback" id="kategori-device-error" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="masalah">Masalah</label>
                    <textarea id="masalah" name="masalah" class="form-control @error('masalah') is-invalid @enderror">{{ old('masalah') }}</textarea>
                    @error('masalah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="masalah-error" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="tindakan_perbaikan">Tindakan Perbaikan</label>
                    <textarea id="tindakan_perbaikan" name="tindakan_perbaikan"
                        class="form-control @error('tindakan_perbaikan') is-invalid @enderror">{{ old('tindakan_perbaikan') }}</textarea>
                    @error('tindakan_perbaikan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="tindakan-perbaikan-error" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="harga">Harga (Rp)</label>
                    <input type="number" id="harga" name="harga"
                        class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga') }}">
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="harga-error" style="display: none;"></div>
                </div>

                <div class="form-group">
                    <label for="garansi">Garansi</label>
                    <div class="garansi-container">
                        <div class="garansi-header">
                            <span class="garansi-title">Detail Garansi</span>
                            <button type="button" id="addGaransiBtn" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Tambah Garansi
                            </button>
                        </div>
                        <div id="garansiContainer">
                            <div class="garansi-item" data-index="0">
                                <div class="garansi-sparepart">
                                    <label>Sparepart</label>
                                    <input type="text" name="garansi_items[0][sparepart]" class="form-control"
                                           placeholder="Contoh: Baterai, LCD, Mesin, dll">
                                </div>
                                <div class="garansi-periode">
                                    <label>Garansi</label>
                                    <select name="garansi_items[0][periode]" class="form-control">
                                        <option value="">-- Pilih Garansi --</option>
                                        <option value="Tidak ada garansi">Tidak ada garansi</option>
                                        <option value="1 Bulan">1 Bulan</option>
                                        <option value="12 Bulan">12 Bulan</option>
                                    </select>
                                </div>
                                <div class="garansi-actions">
                                    <button type="button" class="btn btn-danger btn-sm remove-garansi" style="display: none;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="garansi" id="garansi" value="{{ old('garansi') }}">
                    @error('garansi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="garansi-error" style="display: none;"></div>
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
            const addGaransiBtn = document.getElementById('addGaransiBtn');
            const garansiContainer = document.getElementById('garansiContainer');
            const garansiHiddenInput = document.getElementById('garansi');

            // CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Store all customers from database
            let allCustomers = [];
            let garansiIndex = 1;

            // Error elements
            const errorElements = {
                'nama_pelanggan': document.getElementById('nama-pelanggan-error'),
                'user_id': document.getElementById('user-id-error'),
                'nama_device': document.getElementById('nama-device-error'),
                'kategori_device': document.getElementById('kategori-device-error'),
                'masalah': document.getElementById('masalah-error'),
                'tindakan_perbaikan': document.getElementById('tindakan-perbaikan-error'),
                'harga': document.getElementById('harga-error'),
                'garansi': document.getElementById('garansi-error')
            };

            // Fetch customers from the database when page loads
            fetchCustomers();

            // Function to show error
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

            // Function to hide error
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
                    const item = document.createElement('div');
                    item.className = 'autocomplete-item';
                    item.textContent = 'Pelanggan tidak ditemukan. Silakan daftarkan pelanggan terlebih dahulu.';
                    item.style.color = '#dc3545';

                    autocompleteResults.appendChild(item);
                    autocompleteResults.classList.add('show');

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

                // Hide error for nama_pelanggan
                hideError('nama_pelanggan');
            }

            // Customer name input event
            namaPelangganInput.addEventListener('input', function() {
                const query = this.value.trim();

                if (query.length >= 1) {
                    const results = filterCustomers(query);
                    displayAutocompleteResults(results);
                } else {
                    autocompleteResults.classList.remove('show');
                }

                // Hide error when typing
                if (query.length > 0) {
                    hideError('nama_pelanggan');
                }
            });

            // Blur event for customer name
            namaPelangganInput.addEventListener('blur', function() {
                const query = this.value.trim();

                if (query.length >= 1) {
                    const matchingCustomers = allCustomers.filter(customer =>
                        customer.nama_pelanggan.toLowerCase() === query.toLowerCase()
                    );

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

            // Add input event listeners to hide errors when typing
            ['user_id', 'nama_device', 'kategori_device', 'masalah', 'harga'].forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field) {
                    field.addEventListener('input', function() {
                        if (this.value.trim()) {
                            hideError(fieldName);
                        }
                    });
                }
            });

            // Garansi Management Functions
            function updateRemoveButtons() {
                const garansiItems = garansiContainer.querySelectorAll('.garansi-item');
                garansiItems.forEach((item, index) => {
                    const removeBtn = item.querySelector('.remove-garansi');
                    if (garansiItems.length > 1) {
                        removeBtn.style.display = 'inline-flex';
                    } else {
                        removeBtn.style.display = 'none';
                    }
                });
            }

            function addGaransiItem() {
                const newItem = document.createElement('div');
                newItem.className = 'garansi-item';
                newItem.setAttribute('data-index', garansiIndex);

                newItem.innerHTML = `
                    <div class="garansi-sparepart">
                        <label>Sparepart/Komponen</label>
                        <input type="text" name="garansi_items[${garansiIndex}][sparepart]" class="form-control"
                               placeholder="Contoh: Baterai, LCD, Mesin, dll">
                    </div>
                    <div class="garansi-periode">
                        <label>Periode Garansi</label>
                        <select name="garansi_items[${garansiIndex}][periode]" class="form-control">
                            <option value="">-- Pilih Garansi --</option>
                            <option value="Tidak ada garansi">Tidak ada garansi</option>
                            <option value="1 Bulan">1 Bulan</option>
                            <option value="12 Bulan">12 Bulan</option>
                        </select>
                    </div>
                    <div class="garansi-actions">
                        <button type="button" class="btn btn-danger btn-sm remove-garansi">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;

                garansiContainer.appendChild(newItem);
                garansiIndex++;
                updateRemoveButtons();
                updateGaransiHiddenField();

                // Add event listener for remove button
                const removeBtn = newItem.querySelector('.remove-garansi');
                removeBtn.addEventListener('click', function() {
                    removeGaransiItem(newItem);
                });

                // Add event listeners for inputs to update hidden field
                const inputs = newItem.querySelectorAll('input, select');
                inputs.forEach(input => {
                    input.addEventListener('input', updateGaransiHiddenField);
                    input.addEventListener('change', updateGaransiHiddenField);
                });
            }

            function removeGaransiItem(item) {
                item.remove();
                updateRemoveButtons();
                updateGaransiHiddenField();
            }

            function updateGaransiHiddenField() {
                const garansiItems = garansiContainer.querySelectorAll('.garansi-item');
                const garansiData = [];

                garansiItems.forEach(item => {
                    const sparepart = item.querySelector('input[name*="[sparepart]"]').value.trim();
                    const periode = item.querySelector('select[name*="[periode]"]').value;

                    if (sparepart && periode) {
                        garansiData.push(`${sparepart}: ${periode}`);
                    }
                });

                garansiHiddenInput.value = garansiData.join('; ');

                // Hide garansi error when there's valid data
                if (garansiData.length > 0) {
                    hideError('garansi');
                }
            }

            // Add garansi button event listener
            addGaransiBtn.addEventListener('click', addGaransiItem);

            // Initial setup for first garansi item
            const initialInputs = garansiContainer.querySelectorAll('input, select');
            initialInputs.forEach(input => {
                input.addEventListener('input', updateGaransiHiddenField);
                input.addEventListener('change', updateGaransiHiddenField);
            });

            // Initial remove button setup
            const initialRemoveBtn = garansiContainer.querySelector('.remove-garansi');
            if (initialRemoveBtn) {
                initialRemoveBtn.addEventListener('click', function() {
                    const item = this.closest('.garansi-item');
                    removeGaransiItem(item);
                });
            }

            updateRemoveButtons();

            // Form submit handler
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Always prevent default

                let isValid = true;
                let firstErrorField = null;

                // Reset all errors
                Object.keys(errorElements).forEach(fieldName => {
                    hideError(fieldName);
                });

                // Update garansi hidden field before validation
                updateGaransiHiddenField();

                // Validate customer selection
                if (!pelangganIdInput.value || !namaPelangganInput.value.trim()) {
                    isValid = false;
                    showError('nama_pelanggan', 'Nama pelanggan wajib diisi. Pilih dari daftar pelanggan yang tersedia.');
                    if (!firstErrorField) firstErrorField = namaPelangganInput;
                }

                // Validate required fields
                const requiredFields = [
                    { name: 'user_id', message: 'Teknisi wajib dipilih.' },
                    { name: 'nama_device', message: 'Nama device wajib diisi.' },
                    { name: 'kategori_device', message: 'Kategori device wajib dipilih.' },
                    { name: 'masalah', message: 'Masalah wajib diisi.' },
                    { name: 'harga', message: 'Harga wajib diisi.' }
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

                // Validate garansi
                if (!garansiHiddenInput.value.trim()) {
                    isValid = false;
                    showError('garansi', 'Minimal satu item garansi harus diisi dengan lengkap.');
                    if (!firstErrorField) {
                        const firstGaransiInput = garansiContainer.querySelector('input, select');
                        if (firstGaransiInput) firstErrorField = firstGaransiInput;
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
