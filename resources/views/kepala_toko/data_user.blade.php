<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User - MG TECH</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* (SEMUA CSS ANDA YANG SEBELUMNYA TETAP SAMA DI SINI) */
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

        .btn-info {
            background-color: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background-color: #138496;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .content-section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .table-responsive {
            overflow-x: auto;
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
            cursor: pointer;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
        }

        table th:last-child,
        table td:last-child {
            text-align: center;
        }

        .action-btn {
            padding: 6px 10px;
            border-radius: 4px;
            background-color: #e9ecef;
            color: #495057;
            text-decoration: none;
            font-size: 0.9em;
            transition: background-color 0.2s;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
        }

        .action-btn:hover {
            background-color: #dee2e6;
        }

        .action-btn i {
            font-size: 14px;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Custom Modal Styles */
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
                <h2>User</h2>
            </div>
            <div style="display: flex; align-items: center;">
                <div class="user-info">
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="user-role">
                        {{ $user->isKepalaToko() ? 'Kepala Toko' : ($user->isAdmin() ? 'Admin' : 'Teknisi') }}</div>
                </div>
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>

        <div class="title-section">
            <h1 class="page-title"></h1>
            <a href="{{ route('user.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah User
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="content-section">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAMA USER</th>
                            <th>ALAMAT</th>
                            <th>JABATAN</th>
                            <th style="text-align: center;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $u)
                            <tr onclick="window.location='{{ route('user.show', $u->id) }}';" style="cursor: pointer;">
                                <td>{{ $u->id }}</td>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->alamat }}</td>
                                <td>{{ $u->jabatan }}</td>
                                <td class="action-buttons" onclick="event.stopPropagation();"
                                    style="text-align: center;">
                                    <a href="{{ route('user.edit', $u->id) }}" class="action-btn" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="action-btn" title="Hapus"
                                        onclick="showDeleteModal('{{ $u->id }}', '{{ $u->name }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center;">Tidak ada user</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h4>Konfirmasi Hapus</h4>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus user</p>
                <p class="user-name-highlight" id="userNameDisplay"></p>
            </div>
            <div class="modal-buttons">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="hideDeleteModal()">
                    Batal
                </button>
                <button type="button" class="modal-btn modal-btn-delete" onclick="confirmDelete()">
                    Hapus
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

        function showDeleteModal(userId, userName) {
            currentUserId = userId;
            currentUserName = userName;

            document.getElementById('userNameDisplay').textContent = userName;
            document.getElementById('deleteModal').style.display = 'block';
            document.body.style.overflow = 'hidden'; // Mencegah scroll body saat modal terbuka
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            document.body.style.overflow = 'auto'; // Mengizinkan scroll body kembali
            currentUserId = null;
            currentUserName = null;
        }

        function confirmDelete() {
            if (currentUserId) {
                const form = document.getElementById('deleteForm');
                
                // Gunakan base URL yang Anda harapkan, atau sesuaikan dengan struktur route Anda
                // Jika route 'user.destroy' didefinisikan sebagai 'user/{user}', maka:
                form.action = `{{ url('user') }}/${currentUserId}`;
                
                // Debug log untuk troubleshooting (Anda bisa hapus ini setelah berfungsi)
                console.log('Current User ID:', currentUserId);
                console.log('Form action will be:', form.action);
                
                form.submit();
            }
        }

        // Menutup modal jika klik di luar area modal content
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideDeleteModal();
            }
        });

        // Menutup modal jika menekan tombol Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideDeleteModal();
            }
        });
    </script>
</body>

</html>