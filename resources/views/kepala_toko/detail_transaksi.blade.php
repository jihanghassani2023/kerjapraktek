<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Transaksi - MG TECH</title>
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

        .info-row {
            display: flex;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 200px;
            font-weight: bold;
            color: #555;
        }

        .info-value {
            flex: 1;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
        }

        .status-menunggu {
            background-color: #ffeaea;
            color: #ff6b6b;
        }

        .status-proses {
            background-color: #fff4e0;
            color: #ffaa00;
        }

        .status-selesai {
            background-color: #e7f9e7;
            color: #28a745;
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

        .btn-print {
            background-color: #6c757d;
            color: white;
        }

        .btn-print:hover {
            background-color: #5a6268;
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

        /* Latest Process Styles */
        .latest-process {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            border-left: 4px solid #8c3a3a;
            margin-bottom: 20px;
        }

        .process-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .process-title {
            font-weight: bold;
            color: #333;
        }

        .process-date {
            font-size: 14px;
            color: #666;
            background-color: #f0f0f0;
            padding: 3px 8px;
            border-radius: 12px;
        }

        .process-content {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }

        .show-all-link {
            text-align: right;
            color: #8c3a3a;
            font-size: 14px;
            cursor: pointer;
            padding: 5px 0;
        }

        .show-all-link:hover {
            text-decoration: underline;
        }

        /* Timeline Styles */
        .timeline-container {
            display: none;
            margin-top: 20px;
        }

        .timeline {
            position: relative;
            margin-left: 10px;
            padding-left: 30px;
            border-left: 2px solid #e0e0e0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 25px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-marker {
            position: absolute;
            left: -41px;
            top: 0;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background-color: white;
            border: 2px solid #8c3a3a;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .timeline-marker i {
            color: #8c3a3a;
            font-size: 12px;
        }

        .timeline-content {
            padding-left: 10px;
        }

        .timeline-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .timeline-date {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
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

            .info-row {
                flex-direction: column;
            }

            .info-label {
                width: 100%;
                margin-bottom: 5px;
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
        <a href="{{ route('transaksi.index') }}" class="menu-item active">
            <i class="fas fa-exchange-alt"></i>
            <span>Transaksi</span>
        </a>
        <a href="{{ route('karyawan.index') }}" class="menu-item">
            <i class="fas fa-users"></i>
            <span>Karyawan</span>
        </a>

        <a href="{{ route('transaksi.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>

        <form method="POST" action="{{ route('logout') }}" style="margin-top: 0;">
            @csrf
            <button type="submit" class="logout" style="width: 100%; border: none; cursor: pointer; background: none; text-align: left;">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>

    <div class="main-content">
        <div class="header">
            <h1 class="page-title">Detail Transaksi</h1>
            <div style="display: flex; align-items: center;">
                <div class="user-info">
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="user-role">Kepala Toko</div>
                </div>
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div id="statusAlert" class="alert alert-success" style="display: none;"></div>

        <div class="content-wrapper">
            <div class="content-header">
                <h2 class="content-title">Transaksi #{{ $transaksi->kode_perbaikan }}</h2>
                <a href="javascript:window.print()" class="btn btn-print">
                    <i class="fas fa-print"></i> Cetak
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Perbaikan</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">Kode Perbaikan</div>
                        <div class="info-value">{{ $transaksi->kode_perbaikan }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tanggal Perbaikan</div>
                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($transaksi->tanggal_perbaikan)->format('d F Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nama Barang</div>
                        <div class="info-value">{{ $transaksi->nama_barang }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Kategori Device</div>
                        <div class="info-value">{{ $transaksi->kategori_device ?? 'Tidak ditentukan' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Masalah</div>
                        <div class="info-value">{{ $transaksi->masalah }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tindakan Perbaikan</div>
                        <div class="info-value">{{ $transaksi->tindakan_perbaikan }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Harga</div>
                        <div class="info-value">Rp. {{ number_format($transaksi->harga, 0, ',', '.') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Garansi</div>
                        <div class="info-value">{{ $transaksi->garansi ?: 'Tidak ada' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span id="statusBadge" class="status-badge status-{{ strtolower($transaksi->status) }}">
                                {{ $transaksi->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Proses Pengerjaan Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Proses Pengerjaan</h3>
                </div>
                <div class="card-body">
                    @if(!empty($transaksi->proses_pengerjaan) && count($transaksi->proses_pengerjaan) > 0)
                        <?php
                            $prosesArray = $transaksi->proses_pengerjaan;
                            $latestProcess = $prosesArray[count($prosesArray) - 1];
                        ?>
                        <!-- Latest Process -->
                        <div class="latest-process">
                            <div class="process-header">
                                <div class="process-title"><i class="fas fa-clock"></i> Progress Terakhir</div>
                                <div class="process-date">{{ \Carbon\Carbon::parse($latestProcess['timestamp'])->format('d M Y H:i') }}</div>
                            </div>
                            <div class="process-content">{{ $latestProcess['step'] }}</div>
                            <div class="show-all-link" onclick="toggleTimeline()">
                                Lihat semua progress <i class="fas fa-chevron-down" id="timeline-toggle-icon"></i>
                            </div>
                        </div>

                        <!-- Full Timeline (hidden by default) -->
                        <div id="timeline-container" class="timeline-container">
                            <div class="timeline">
                                @foreach(array_reverse($transaksi->proses_pengerjaan) as $proses)
                                    <div class="timeline-item">
                                        <div class="timeline-marker">
                                            <i class="fas fa-circle"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h4 class="timeline-title">{{ $proses['step'] }}</h4>
                                            <p class="timeline-date">{{ \Carbon\Carbon::parse($proses['timestamp'])->format('d M Y H:i:s') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p style="text-align: center; padding: 20px; color: #666;">Belum ada proses pengerjaan yang direkam.</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Pelanggan</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">Nama Pelanggan</div>
                        <div class="info-value">{{ $transaksi->pelanggan->nama_pelanggan }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nomor Telepon</div>
                        <div class="info-value">{{ $transaksi->pelanggan->nomor_telp }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $transaksi->pelanggan->email ?: '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Teknisi</h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">Nama Teknisi</div>
                        <div class="info-value">{{ $transaksi->user->name ?? 'Tidak ada' }}</div>
                    </div>
                    @if ($transaksi->user)
                        <div class="info-row">
                            <div class="info-label">Email Teknisi</div>
                            <div class="info-value">{{ $transaksi->user->email }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle timeline function
        function toggleTimeline() {
            const timelineContainer = document.getElementById('timeline-container');
            const toggleIcon = document.getElementById('timeline-toggle-icon');
            const showAllLink = document.querySelector('.show-all-link');

            if (timelineContainer.style.display === 'none' || timelineContainer.style.display === '') {
                timelineContainer.style.display = 'block';
                toggleIcon.className = 'fas fa-chevron-up';
                showAllLink.innerHTML = 'Sembunyikan progress <i class="fas fa-chevron-up" id="timeline-toggle-icon"></i>';
            } else {
                timelineContainer.style.display = 'none';
                toggleIcon.className = 'fas fa-chevron-down';
                showAllLink.innerHTML = 'Lihat semua progress <i class="fas fa-chevron-down" id="timeline-toggle-icon"></i>';
            }
        }

        // Print functionality
        window.addEventListener('beforeprint', function() {
            document.querySelector('.sidebar').style.display = 'none';
            document.querySelector('.main-content').style.marginLeft = '0';
            document.querySelector('.header').style.display = 'none';
            document.querySelector('.content-header .btn-print').style.display = 'none';
            document.querySelector('.show-all-link').style.display = 'none';

            // Ensure full timeline is visible when printing
            const timelineContainer = document.getElementById('timeline-container');
            if(timelineContainer) {
                timelineContainer.style.display = 'block';
            }
        });

        window.addEventListener('afterprint', function() {
            document.querySelector('.sidebar').style.display = 'flex';
            document.querySelector('.main-content').style.marginLeft = '220px';
            document.querySelector('.header').style.display = 'flex';
            document.querySelector('.content-header .btn-print').style.display = 'inline-flex';
            document.querySelector('.show-all-link').style.display = 'block';

            // Restore timeline to previous state after printing
            const timelineContainer = document.getElementById('timeline-container');
            if(timelineContainer && !document.querySelector('.show-all-link').innerHTML.includes('Sembunyikan')) {
                timelineContainer.style.display = 'none';
            }
        });
    </script>
</body>

</html>
