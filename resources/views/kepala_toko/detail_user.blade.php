<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail User - MG TECH</title>
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

        .logo-container {
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .logo-text {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            margin-left: 10px;
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
            border-left: 4px solid white;
        }

        .menu-item:hover {
            background-color: #6d2d2d;
        }

        .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .logout-btn {
            margin-top: auto;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            width: 100%;
            text-align: left;
            font-size: 1rem;
        }

        .logout-btn:hover {
            background-color: #6d2d2d;
        }

        .logout-btn i {
            margin-right: 10px;
        }

        .back-btn {
            padding: 15px 20px;
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            margin-top: auto; /* Disesuaikan untuk menempatkan tombol Kembali di atas Logout */
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

        .top-header {
            background-color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .user-profile {
            display: flex;
            align-items: center;
        }

        .user-info {
            text-align: right;
            margin-right: 15px;
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
            background-color: #8c3a3a;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .content-wrapper {
            padding: 30px;
            flex: 1;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .content-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
            position: relative;
            padding-bottom: 10px;
        }

        .content-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: #8c3a3a;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            font-size: 0.9rem;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn i {
            margin-right: 8px;
        }

        .btn-edit {
            background-color: #8c3a3a;
            color: white;
        }

        .btn-edit:hover {
            background-color: #6d2d2d;
        }

        .btn-delete {
            background-color: #6c757d;
            color: white;
        }

        .btn-delete:hover {
            background-color: #5a6268;
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

        .data-row {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .data-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .data-label {
            width: 200px;
            font-weight: bold;
            color: #555;
        }

        .data-value {
            flex: 1;
            color: #333;
        }

        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #8c3a3a;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
        }

        .info-box i {
            font-size: 24px;
            color: #8c3a3a;
            margin-right: 15px;
        }

        .info-text {
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            text-align: left;
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        .status-menunggu {
            color: #ff6b6b;
            font-weight: bold;
        }

        .status-proses {
            color: #ff9f43;
            font-weight: bold;
        }

        .status-selesai {
            color: #28a745;
            font-weight: bold;
        }

        .empty-state {
            text-align: center;
            padding: 40px 0;
        }

        .empty-state i {
            font-size: 48px;
            color: #ccc;
            margin-bottom: 15px;
        }

        .empty-state-text {
            color: #888;
            font-size: 16px;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border-radius: 10px;
            padding: 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 90%;
            animation: modalFadeIn 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translate(-50%, -60%);
            }

            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        .modal-header {
            background-color: #dc3545;
            color: white;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }

        .modal-header h4 {
            margin: 0;
            font-size: 16px;
        }

        .modal-body {
            padding: 20px;
            text-align: center;
        }

        .modal-body p {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 15px;
            line-height: 1.5;
        }

        .user-name-highlight {
            font-weight: bold;
            color: #8c3a3a;
        }

        .modal-buttons {
            padding: 0 20px 20px 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .modal-btn {
            padding: 8px 20px;
            border-radius: 5px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }

        .modal-btn-cancel {
            background-color: #6c757d;
            color: white;
        }

        .modal-btn-cancel:hover {
            background-color: #5a6268;
        }

        .modal-btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .modal-btn-delete:hover {
            background-color: #c82333;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }

            .sidebar .logo-text,
            .sidebar .menu-item span,
            .sidebar .logout-btn span,
            .sidebar .back-btn span {
                display: none;
            }

            .main-content {
                margin-left: 60px;
            }

            .data-row {
                flex-direction: column;
            }

            .data-label {
                width: 100%;
                margin-bottom: 5px;
            }

            .modal-container {
                width: 95%;
                margin: 20px;
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
        <a href="{{ route('kepala-toko.dashboard') }}" class="menu-item">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('user.index') }}" class="menu-item active">
            <i class="fas fa-users"></i>
            <span>User</span>
        </a>
        <a href="{{ route('laporan.index') }}" class="menu-item">
            <i class="fas fa-chart-bar"></i>
            <span>Laporan</span>
        </a>

        <a href="{{ route('user.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>

    <div class="main-content">
        <div class="top-header">
            <h1 class="page-title">Detail User</h1>
            <div class="user-profile">
                <div class="user-info">
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="user-role">
                        {{ $user->isKepalaToko() ? 'Kepala Toko' : ($user->isAdmin() ? 'Admin' : 'Teknisi') }}</div>
                </div>
                <div class="user-avatar">
                    {{ substr($user->name, 0, 1) }}
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="content-header">
                <h2 class="content-title">Detail User</h2>
                <div class="action-buttons">
                    <a href="{{ route('user.edit', $userData->id) }}" class="btn btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <button type="button" class="btn btn-delete"
                        onclick="showDeleteModal('{{ $userData->id }}', '{{ addslashes($userData->name) }}')">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi User</h3>
                </div>
                <div class="card-body">
                    <div class="data-row">
                        <div class="data-label">ID User</div>
                        <div class="data-value">{{ $userData->id }}</div>
                    </div>
                    <div class="data-row">
                        <div class="data-label">Nama User</div>
                        <div class="data-value">{{ $userData->name }}</div>
                    </div>
                    <div class="data-row">
                        <div class="data-label">Alamat</div>
                        <div class="data-value">{{ $userData->alamat }}</div>
                    </div>
                    <div class="data-row">
                        <div class="data-label">Jabatan</div>
                        <div class="data-value">{{ $userData->jabatan }}</div>
                    </div>
                </div>
            </div>

            @if ($userData->jabatan == 'Teknisi' || $userData->jabatan == 'Kepala Teknisi')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Perbaikan</h3>
                    </div>
                    <div class="card-body">
                        @if (count($perbaikanList) > 0)
                            <table>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Device</th>
                                        <th>Keterangan</th>
                                        <th>Tanggal</th>
                                        <th>Total Perbaikan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($perbaikanList as $index => $perbaikan)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $perbaikan->nama_device }}</td>
                                            <td>{{ $perbaikan->masalah }}</td>
                                            <td>{{ $perbaikan->tanggal_formatted ?? \App\Helpers\DateHelper::formatTanggalIndonesia($perbaikan->tanggal_perbaikan) }}
                                            </td>
                                            <td>Rp {{ number_format($perbaikan->harga, 0, ',', '.') }}</td>
                                            <td><span
                                                    class="status-{{ strtolower($perbaikan->status) }}">{{ $perbaikan->status }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-info-circle"></i>
                                <p class="empty-state-text">Belum ada data perbaikan yang dilakukan oleh User ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <div class="info-text">
                        User dengan jabatan {{ $userData->jabatan }} tidak memiliki akses untuk melakukan perbaikan.
                    </div>
                </div>
            @endif
        </div>
    </div>


    <div id="deleteModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h4>Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus user ini?</p>
                <p class="user-name-highlight" id="userNameDisplay"></p>
            </div>
            <div class="modal-buttons">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="hideDeleteModal()">
                    Cancel
                </button>
                <button type="button" class="modal-btn modal-btn-delete" onclick="confirmDelete()">
                    OK
                </button>
            </div>
        </div>
    </div>


    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        let currentUserId = null;
        let currentUserName = null;

        /**
         * Menampilkan modal konfirmasi penghapusan.
         * @param {string} userId - ID pengguna yang akan dihapus.
         * @param {string} userName - Nama pengguna yang akan dihapus.
         */
        function showDeleteModal(userId, userName) {
            currentUserId = userId;
            currentUserName = userName;
            document.getElementById('userNameDisplay').textContent = userName;
            document.getElementById('deleteModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        /**
         * Menyembunyikan modal konfirmasi penghapusan.
         */
        function hideDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            currentUserId = null;
            currentUserName = null;
        }

        /**
         * Mengirimkan form penghapusan setelah konfirmasi.
         */
        function confirmDelete() {
            if (currentUserId) {
                const form = document.getElementById('deleteForm');
                // Mengatur action form dengan URL penghapusan yang benar.
                // Berdasarkan routes/web.php Anda, Route::resource('user', UserController::class);
                // didefinisikan tanpa prefix, jadi URL yang tepat adalah '/user/{id}'.
                form.action = `/user/${currentUserId}`;
                
                // Log untuk debugging: Menampilkan ID pengguna dan URL form yang akan disubmit.
                console.log('Current User ID:', currentUserId);
                console.log('Form action will be:', form.action);
                
                // Mengirimkan form, yang akan memicu permintaan DELETE ke server Laravel.
                form.submit();
            }
        }
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideDeleteModal();
            }
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideDeleteModal();
            }
        });
    </script>
</body>

</html>
