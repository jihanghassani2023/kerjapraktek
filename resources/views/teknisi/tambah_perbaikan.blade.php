<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Perbaikan - MG TECH</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* CSS yang sudah ada tetap sama */
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
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 5px;
            width: 400px;
            max-width: 90%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            text-align: center;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .modal-title {
            font-size: 20px;
            color: #333;
            text-align: center;
            width: 100%;
        }
        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            right: 20px;
            top: 10px;
        }
        .close:hover {
            color: #333;
        }
        .modal-body {
            margin-bottom: 20px;
        }
        .generated-key {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
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
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #666;
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
        .btn {
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            border: none;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #8c3a3a;
            color: white;
        }
        .btn-primary:hover {
            background-color: #6d2d2d;
        }
        .btn-secondary {
            background-color: #f5f5f5;
            color: #666;
        }
        .btn-secondary:hover {
            background-color: #e5e5e5;
        }
        .form-footer {
            text-align: right;
            margin-top: 20px;
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
            <h1 class="page-title">TAMBAH PERBAIKAN <span>TEKNISI</span></h1>
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

        <div class="form-container">
            <h2 class="form-header">Tambah Perbaikan</h2>
            <form id="perbaikanForm" action="{{ route('perbaikan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="kode_perbaikan" id="kode_perbaikan" value="{{ old('kode_perbaikan') }}">

                <div class="form-group">
                    <label for="nama_pelanggan">Nama</label>
                    <input type="text" id="nama_pelanggan" name="nama_pelanggan" class="form-control" value="{{ old('nama_pelanggan') }}" required>
                </div>

                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" id="nama_barang" name="nama_barang" class="form-control" value="{{ old('nama_barang') }}" required>
                </div>

                <div class="form-group">
                    <label for="nomor_telp">Nomor Telp</label>
                    <input type="text" id="nomor_telp" name="nomor_telp" class="form-control" value="{{ old('nomor_telp') }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label for="masalah">Masalah</label>
                    <input type="text" id="masalah" name="masalah" class="form-control" value="{{ old('masalah') }}" required>
                </div>

                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" id="harga" name="harga" class="form-control" value="{{ old('harga') }}">
                </div>

                <div class="form-group">
                    <label for="garansi">Garansi</label>
                    <input type="text" id="garansi" name="garansi" class="form-control" value="{{ old('garansi') }}">
                </div>

                <div class="form-footer">
                    <button type="button" id="generateKeyBtn" class="btn btn-primary">Generate Key</button>
                </div>
            </form>
        </div>
    </div>

    <div id="keyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">GENERATE KEY</h2>
            </div>
            <div class="modal-body">
                <div id="generatedKey" class="generated-key"></div>
            </div>
            <button id="saveKeyBtn" class="btn btn-primary" style="width: 100%;">SIMPAN</button>
        </div>
    </div>

    <script>
        // PERBAIKAN: Definisikan URL sebagai variabel global
        const generateKeyUrl = "{{ route('perbaikan.generate-key') }}";
        
        // Fungsi untuk melakukan AJAX request dan generate key
        function fetchGenerateKey() {
            // Dapatkan CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Buat konfigurasi untuk request
            const requestOptions = {
                method: 'GET', // Atau 'POST' tergantung pada setup route Anda
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            };
            
            // Lakukan fetch request
            return fetch(generateKeyUrl, requestOptions)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                });
        }
        
        // Fungsi untuk validasi form
        function validateForm() {
            let isValid = true;
            const requiredFields = ['nama_pelanggan', 'nama_barang', 'nomor_telp', 'masalah'];
            
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (!input.value.trim()) {
                    input.style.borderColor = '#ff6b6b';
                    isValid = false;
                } else {
                    input.style.borderColor = '#ddd';
                }
            });
            
            if (!isValid) {
                alert('Mohon lengkapi semua field yang diperlukan');
            }
            
            return isValid;
        }
        
        // Fungsi untuk setup event listeners
        function setupEventListeners() {
            const generateKeyBtn = document.getElementById('generateKeyBtn');
            const keyModal = document.getElementById('keyModal');
            const generatedKeyEl = document.getElementById('generatedKey');
            const saveKeyBtn = document.getElementById('saveKeyBtn');
            const kodeInput = document.getElementById('kode_perbaikan');
            const form = document.getElementById('perbaikanForm');
            
            // Event listener untuk tombol Generate Key
            if (generateKeyBtn) {
                generateKeyBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Validasi form terlebih dahulu
                    if (!validateForm()) {
                        return;
                    }
                    
                    // Lakukan request untuk generate key
                    fetchGenerateKey()
                        .then(data => {
                            console.log('Generated key:', data);
                            if (data && data.kode) {
                                generatedKeyEl.textContent = data.kode;
                                kodeInput.value = data.kode; // Set nilai input hidden juga
                                keyModal.style.display = 'block';
                            } else {
                                throw new Error('Invalid key data received');
                            }
                        })
                        .catch(error => {
                            console.error('Error generating key:', error);
                            alert('Gagal generate key. Silakan coba lagi.');
                        });
                });
            }
            
            // Event listener untuk tombol Save
            if (saveKeyBtn) {
                saveKeyBtn.addEventListener('click', function() {
                    const generatedKey = generatedKeyEl.textContent;
                    kodeInput.value = generatedKey;
                    keyModal.style.display = 'none';
                    
                    // Submit form
                    form.submit();
                });
            }
            
            // Event listener untuk menutup modal saat klik di luar
            window.addEventListener('click', function(event) {
                if (event.target === keyModal) {
                    keyModal.style.display = 'none';
                }
            });
        }
        
        // PERBAIKAN: Jalankan setup event listeners saat DOM sudah siap
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
        });
        
        // PERBAIKAN: Tambahkan event listener untuk turbolinks jika menggunakan Laravel dengan Turbolinks
        if (typeof Turbolinks !== 'undefined') {
            document.addEventListener('turbolinks:load', function() {
                setupEventListeners();
            });
        }
    </script>
</body>
</html>