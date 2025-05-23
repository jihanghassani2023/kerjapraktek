<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Perbaikan - MG TECH</title>
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
            padding: 15px 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
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

        .content-wrapper {
            padding: 0;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .content-title {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
        }

        /* Row and Column Layout */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .col-md-6 {
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }

        @media (min-width: 768px) {
            .col-md-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        .mt-3 {
            margin-top: 15px;
        }

        .mt-4 {
            margin-top: 20px;
        }

        /* Card Styling */
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .card-header {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            padding-bottom: 0;
            margin-bottom: 0;
        }

        .info-label {
            width: 200px;
            font-weight: bold;
            color: #555;
        }

        .info-value {
            flex: 1;
            color: #333;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
        }

        .status-menunggu {
            color: #ff6b6b;
        }

        .status-proses {
            color: #ffaa00;
        }

        .status-selesai {
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

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-print {
            background-color: #6c757d;
            color: white;
        }

        .btn-print:hover {
            background-color: #5a6268;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .status-actions {
            margin-top: 0;
            padding: 0;
            background-color: transparent;
            border-radius: 0;
        }

        .status-title {
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .status-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-status {
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        .btn-menunggu {
            background-color: #ffeaea;
            color: #ff6b6b;
        }

        .btn-menunggu:hover {
            background-color: #ffd0d0;
        }

        .btn-proses {
            background-color: #fff4e0;
            color: #ffaa00;
        }

        .btn-proses:hover {
            background-color: #ffe6c0;
        }

        .btn-selesai {
            background-color: #e7f9e7;
            color: #28a745;
        }

        .btn-selesai:hover {
            background-color: #d0f0d0;
        }

        /* Timeline Styles */
        .timeline-container {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            display: none;
        }

        .timeline {
            position: relative;
            margin-left: 20px;
            padding-left: 20px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 15px;
            padding-bottom: 15px;
            background-color: transparent !important;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .timeline-marker {
            position: absolute;
            left: -31px;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: white;
            border: 2px solid #8c3a3a;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .timeline-marker i {
            font-size: 10px;
            color: #8c3a3a;
        }

        .timeline-content {
            padding-left: 10px;
        }

        .timeline-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .timeline-date {
            font-size: 14px;
            color: #666;
            margin: 0;
        }

        .add-process-form {
            margin-top: 15px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        .input-group {
            display: flex;
            gap: 10px;
        }

        .input-group-append {
            display: flex;
        }

        .form-control {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        /* Latest Process */
        .latest-process {
            background-color: transparent;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #eee;
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

        /* Status change styling */
        .timeline-item.status-change .timeline-marker {
            border-color: #6c757d;
        }

        .timeline-item.status-change .timeline-marker i {
            color: #6c757d;
        }

        .timeline-item.status-change .timeline-title {
            font-style: italic;
        }

        /* Status-specific colors */
        .timeline-item.status-menunggu .timeline-marker {
            border-color: #ff6b6b;
        }

        .timeline-item.status-menunggu .timeline-marker i {
            color: #ff6b6b;
        }

        .timeline-item.status-menunggu .timeline-title {
            color: #ff6b6b;
        }

        .timeline-item.status-proses .timeline-marker {
            border-color: #ffaa00;
        }

        .timeline-item.status-proses .timeline-marker i {
            color: #ffaa00;
        }

        .timeline-item.status-proses .timeline-title {
            color: #ffaa00;
        }

        .timeline-item.status-selesai .timeline-marker {
            border-color: #28a745;
        }

        .timeline-item.status-selesai .timeline-marker i {
            color: #28a745;
        }

        .timeline-item.status-selesai .timeline-title {
            color: #28a745;
        }

        /* Tambahan untuk menghilangkan background color pada semua item timeline */
        .timeline-item.status-change,
        .timeline-item.status-change.status-proses,
        .timeline-item.status-change.status-selesai,
        .timeline-item.status-change.status-menunggu {
            background-color: transparent !important;
        }

        /* Modal styles */
        #confirmationModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        #confirmationModal .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            width: 350px;
        }

        #confirmationText {
            margin-bottom: 20px;
            font-weight: bold;
            color: #333333;
        }

        #confirmYes,
        #confirmNo {
            padding: 8px 30px;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
            cursor: pointer;
            font-weight: bold;
        }

        #confirmYes {
            background-color: #28a745;
            color: white;
        }

        #confirmNo {
            background-color: #dc3545;
            color: white;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
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

            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .info-row {
                flex-direction: column;
            }

            .info-label {
                width: 100%;
                margin-bottom: 5px;
            }

            .status-buttons {
                flex-direction: column;
            }
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
        <a href="{{ route('teknisi.progress') }}" class="menu-item active">
            <i class="fas fa-tools"></i>
            <span>Progres</span>
        </a>
        <a href="{{ route('teknisi.laporan') }}" class="menu-item">
            <i class="fas fa-clipboard-list"></i>
            <span>Laporan</span>
        </a>

        <a href="{{ route('teknisi.progress') }}" class="back-btn">
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
            <h1 class="page-title">Detail Perbaikan <span>TEKNISI</span></h1>
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

        <div class="content-wrapper">
            <div class="content-header">
                <h2 class="content-title">Perbaikan #{{ $perbaikan->id }}</h2>
                <a href="javascript:window.print()" class="btn btn-print">
                    <i class="fas fa-print"></i> Cetak
                </a>
            </div>

            <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <!-- Card Info Perbaikan -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detail Perbaikan</h3>
                            <span class="detail-status"
                                style="color: {{ $perbaikan->status == 'Selesai' ? '#28a745' : ($perbaikan->status == 'Proses' ? '#ffaa00' : '#ff6b6b') }};">
                                {{ $perbaikan->status }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="info-row">
                                <div class="info-label">Kode Perbaikan</div>
                                <div class="info-value">{{ $perbaikan->id }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Tanggal Perbaikan</div>
                                <div class="info-value">
                                    {{ \Carbon\Carbon::parse($perbaikan->tanggal_perbaikan)->format('d F Y') }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Nama Device</div>
                                <div class="info-value">{{ $perbaikan->nama_device }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Kategori Device</div>
                                <div class="info-value">{{ $perbaikan->kategori_device ?? 'Tidak ditentukan' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Masalah</div>
                                <div class="info-value">{{ $perbaikan->masalah }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Tindakan Perbaikan</div>
                                <div class="info-value">{{ $perbaikan->tindakan_perbaikan }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Harga</div>
                                <div class="info-value">Rp. {{ number_format($perbaikan->harga, 0, ',', '.') }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Garansi</div>
                                <div class="info-value">{{ $perbaikan->garansi ?: 'Tidak ada' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Status</div>
                                <div class="info-value">
                                    <span id="statusBadge"
                                        class="status-badge status-{{ strtolower($perbaikan->status) }}">
                                        {{ $perbaikan->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Info Pelanggan -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Pelanggan</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-row">
                                <div class="info-label">Nama Pelanggan</div>
                                <div class="info-value">{{ $perbaikan->pelanggan->nama_pelanggan }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Nomor Telepon</div>
                                <div class="info-value">{{ $perbaikan->pelanggan->nomor_telp }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $perbaikan->pelanggan->email ?: '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <!-- Card Timeline Proses Pengerjaan -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Proses Pengerjaan</h3>
                        </div>
                        <div class="card-body">
                            @if (!empty($perbaikan->proses_pengerjaan) && count($perbaikan->proses_pengerjaan) > 0)
                                <?php
                                $prosesArray = $perbaikan->proses_pengerjaan;
                                $latestProcess = $prosesArray[count($prosesArray) - 1];
                                ?>
                                <!-- Latest Process -->
                                <div class="latest-process">
                                    <div class="process-header">
                                        <div class="process-title"><i class="fas fa-clock"></i> Progress Terakhir
                                        </div>
                                        <div class="process-date">
                                            {{ \Carbon\Carbon::parse($latestProcess['timestamp'])->format('d M Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="process-content">{{ $latestProcess['step'] }}</div>
                                    <div class="show-all-link" onclick="toggleTimeline()">
                                        Lihat semua progress <i class="fas fa-chevron-down"
                                            id="timeline-toggle-icon"></i>
                                    </div>
                                </div>

                                <!-- Timeline Container (Initially Hidden) -->
                                <div id="timeline-container" class="timeline-container">
                                    <div class="timeline">
                                        @foreach (array_reverse($perbaikan->proses_pengerjaan) as $proses)
                                            @php
                                                $isStatusChange =
                                                    $proses['step'] == 'Menunggu Antrian Perbaikan' ||
                                                    $proses['step'] == 'Device Anda Sedang diproses' ||
                                                    $proses['step'] == 'Device Anda Telah Selesai';
                                                $statusClass = '';
                                                if ($isStatusChange) {
                                                    if ($proses['step'] == 'Menunggu Antrian Perbaikan') {
                                                        $statusClass = 'status-menunggu';
                                                    } elseif ($proses['step'] == 'Device Anda Sedang diproses') {
                                                        $statusClass = 'status-proses';
                                                    } elseif ($proses['step'] == 'Device Anda Telah Selesai') {
                                                        $statusClass = 'status-selesai';
                                                    }
                                                }
                                            @endphp
                                            <div class="timeline-item {{ $isStatusChange ? 'status-change ' . $statusClass : '' }}"
                                                style="background-color: transparent !important;">
                                                <div class="timeline-marker">
                                                    <i
                                                        class="fas {{ $isStatusChange ? 'fa-flag' : 'fa-circle' }}"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <div class="timeline-title">{{ $proses['step'] }}</div>
                                                    <div class="timeline-date">
                                                        {{ \Carbon\Carbon::parse($proses['timestamp'])->format('d M Y H:i:s') }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <p style="text-align: center; padding: 20px; color: #666;">Belum ada proses pengerjaan
                                    yang direkam.</p>
                            @endif

                            <!-- Form Tambah Proses -->
                            <div class="add-process-form">
                                <form action="{{ route('perbaikan.add-process', $perbaikan->id) }}" method="POST">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" name="proses_step" class="form-control"
                                            placeholder="Tambahkan langkah proses baru..." required>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Tambah
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Card Status Actions -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">Aksi</h3>
                        </div>
                        <div class="card-body">
                            <!-- Status update section -->
                            <div class="status-actions">
                                <h4 class="status-title">Ubah Status Perbaikan</h4>
                                <div class="status-buttons">
                                    @if ($perbaikan->status == 'Menunggu')
                                        <button type="button" class="btn-status btn-proses" data-status="Proses">
                                            Proses
                                        </button>
                                    @elseif($perbaikan->status == 'Proses')
                                        <button type="button" class="btn-status btn-selesai" data-status="Selesai">
                                            Selesai
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="actions mt-3">
                                <a href="{{ route('perbaikan.edit', $perbaikan->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit Perbaikan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Confirmation Modal -->
    <div id="confirmationModal">
        <div class="modal-content">
            <h3 id="confirmationText">APAKAH DEVICE INI AKAN ANDA KERJAKAN?</h3>
            <div>
                <button id="confirmYes">YA</button>
                <button id="confirmNo">TIDAK</button>
            </div>
        </div>
    </div>

    <script>
        // Execute when DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Get current status
            const currentStatus = '{{ $perbaikan->status }}';

            // Hide status change section if status is already "Selesai"
            if (currentStatus === 'Selesai') {
                const statusActions = document.querySelector('.status-actions');
                if (statusActions) {
                    statusActions.style.display = 'none';
                }
            }

            // Set up the confirmation modal
            const modal = document.getElementById('confirmationModal');
            const confirmText = document.getElementById('confirmationText');
            const confirmYes = document.getElementById('confirmYes');
            const confirmNo = document.getElementById('confirmNo');

            // Variables to store the status we want to change to
            let pendingStatus = '';

            // Attach click handlers to all status buttons
            document.querySelectorAll('.btn-status').forEach(button => {
                button.addEventListener('click', function() {
                    // Get the status from data attribute or button content
                    pendingStatus = this.getAttribute('data-status') || this.textContent.trim();

                    // Set confirmation message based on the status
                    if (pendingStatus === 'Proses') {
                        confirmText.textContent = 'APAKAH DEVICE INI AKAN ANDA KERJAKAN?';
                    } else if (pendingStatus === 'Selesai') {
                        confirmText.textContent = 'APAKAH DEVICE INI SUDAH SELESAI?';
                    } else {
                        confirmText.textContent = 'APAKAH ANDA YAKIN MENGUBAH STATUS?';
                    }

                    // Show the confirmation modal
                    modal.style.display = 'block';
                });
            });

            // Handle confirmation: YES
            confirmYes.addEventListener('click', function() {
                if (pendingStatus) {
                    // Get CSRF token
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Hide the modal
                    modal.style.display = 'none';

                    // Capture the old status before updating UI
                    const statusBadge = document.getElementById('statusBadge');
                    const oldStatus = statusBadge ? statusBadge.textContent.trim() : '';

                    // IMMEDIATELY UPDATE UI - Update UI before waiting for server
                    if (statusBadge) {
                        statusBadge.className = 'status-badge status-' + pendingStatus.toLowerCase();
                        statusBadge.textContent = pendingStatus;
                    }

                    // If status is now "Selesai", hide the status change section immediately
                    if (pendingStatus === 'Selesai') {
                        const statusActions = document.querySelector('.status-actions');
                        if (statusActions) {
                            statusActions.style.display = 'none';
                        }
                    } else {
                        // Otherwise, update which buttons should be visible immediately
                        updateStatusButtons(pendingStatus);
                    }

                    // Also update any other UI elements that show the status
                    const detailStatus = document.querySelector('.detail-status');
                    if (detailStatus) {
                        detailStatus.textContent = pendingStatus;
                        if (pendingStatus === 'Selesai') {
                            detailStatus.style.color = '#28a745';
                        } else if (pendingStatus === 'Proses') {
                            detailStatus.style.color = '#ffaa00';
                        } else {
                            detailStatus.style.color = '#ff6b6b';
                        }
                    }

                    // Create a new timeline item for display without refresh
                    addNewTimelineItem(pendingStatus);

                    // Now send the status update request in the background
                    fetch('/perbaikan/{{ $perbaikan->id }}/status', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({
                                status: pendingStatus,
                                tindakan_perbaikan: "{{ $perbaikan->tindakan_perbaikan }}",
                                harga: {{ $perbaikan->harga }}
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Success! Do nothing visible to user since we already updated UI
                            console.log("Status updated successfully");
                        })
                        .catch(error => {
                            // Error occurred, but don't show to user
                            console.error('Error updating status:', error);
                        });
                }
            });

            // Function to add a new timeline item without refresh
            function addNewTimelineItem(newStatus) {
                // Get the timeline container
                const timeline = document.querySelector('.timeline');

                if (!timeline) return;

                // Create status message
                let statusText = "";
                if (newStatus === 'Menunggu') {
                    statusText = "Menunggu Antrian Perbaikan";
                } else if (newStatus === 'Proses') {
                    statusText = "Device Anda Sedang diproses";
                } else if (newStatus === 'Selesai') {
                    statusText = "Device Anda Telah Selesai";
                }

                // Determine status class
                let statusClass = '';
                if (newStatus === 'Menunggu') {
                    statusClass = 'status-menunggu';
                } else if (newStatus === 'Proses') {
                    statusClass = 'status-proses';
                } else if (newStatus === 'Selesai') {
                    statusClass = 'status-selesai';
                }

                // Get current date and time in proper format
                // Get current date and time in Palembang timezone (GMT+7)
                const now = new Date();

                // Calculate the time in Indonesia (GMT+7)
                const utcTime = now.getTime() + (now.getTimezoneOffset() * 60000);
                const jakartaTime = new Date(utcTime + (3600000 * 7));

                const dateStr = jakartaTime.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
                const timeStr = jakartaTime.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                const datetime = `${dateStr} ${timeStr}`;
                // Create new timeline item element
                const newItem = document.createElement('div');
                newItem.className = `timeline-item status-change ${statusClass}`;
                newItem.style.backgroundColor = 'transparent'; // Memastikan tidak ada background color
                newItem.innerHTML = `
                    <div class="timeline-marker">
                        <i class="fas fa-flag"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-title">${statusText}</div>
                        <div class="timeline-date">${datetime}</div>
                    </div>
                `;

                // Add to the beginning of the timeline
                if (timeline.firstChild) {
                    timeline.insertBefore(newItem, timeline.firstChild);
                } else {
                    timeline.appendChild(newItem);
                }

                // Also update the latest process section
                const latestProcessContent = document.querySelector('.process-content');
                if (latestProcessContent) {
                    latestProcessContent.textContent = statusText;
                }

                const processDate = document.querySelector('.process-date');
                if (processDate) {
                    processDate.textContent = datetime;
                }

                // If there is an "empty timeline" message, remove it
                const emptyTimeline = document.querySelector('.empty-timeline');
                if (emptyTimeline) {
                    emptyTimeline.remove();
                }
            }

            // Handle confirmation: NO
            confirmNo.addEventListener('click', function() {
                // Just hide the modal
                modal.style.display = 'none';
            });

            // Close the modal if user clicks outside of it
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });

        // Function to update button display based on status
        function updateStatusButtons(status) {
            // Remove all buttons first
            const buttonsContainer = document.querySelector('.status-buttons');
            if (!buttonsContainer) return;

            buttonsContainer.innerHTML = '';

            // Add appropriate buttons based on status
            if (status === 'Menunggu') {
                // For Menunggu status, show ONLY Proses button
                const prosesButton = document.createElement('button');
                prosesButton.type = 'button';
                prosesButton.className = 'btn-status btn-proses';
                prosesButton.setAttribute('data-status', 'Proses');
                prosesButton.textContent = 'Proses';
                prosesButton.onclick = showConfirmationModal;
                buttonsContainer.appendChild(prosesButton);
            } else if (status === 'Proses') {
                // For Proses status, show ONLY Selesai button
                const selesaiButton = document.createElement('button');
                selesaiButton.type = 'button';
                selesaiButton.className = 'btn-status btn-selesai';
                selesaiButton.setAttribute('data-status', 'Selesai');
                selesaiButton.textContent = 'Selesai';
                selesaiButton.onclick = showConfirmationModal;
                buttonsContainer.appendChild(selesaiButton);
            }
            // For Selesai status, no buttons will be shown
        }

        // Helper function to show confirmation modal
        function showConfirmationModal() {
            const pendingStatus = this.getAttribute('data-status') || this.textContent.trim();
            const confirmText = document.getElementById('confirmationText');

            if (pendingStatus === 'Proses') {
                confirmText.textContent = 'APAKAH DEVICE INI AKAN ANDA KERJAKAN?';
            } else if (pendingStatus === 'Selesai') {
                confirmText.textContent = 'APAKAH DEVICE INI SUDAH SELESAI?';
            } else {
                confirmText.textContent = 'APAKAH ANDA YAKIN MENGUBAH STATUS?';
            }

            document.getElementById('confirmationModal').style.display = 'block';

            // Store the status in a global-ish scope for the confirmation handler
            window.pendingStatus = pendingStatus;
        }

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
                showAllLink.innerHTML =
                    'Lihat semua progress <i class="fas fa-chevron-down" id="timeline-toggle-icon"></i>';
            }
        }

        // Print functionality
        window.addEventListener('beforeprint', function() {
            document.querySelector('.sidebar').style.display = 'none';
            document.querySelector('.main-content').style.marginLeft = '0';
            document.querySelector('.header').style.display = 'none';
            document.querySelector('.content-header .btn-print').style.display = 'none';
            document.querySelector('.status-actions').style.display = 'none';
            document.querySelector('.actions').style.display = 'none';
        });

        window.addEventListener('afterprint', function() {
            document.querySelector('.sidebar').style.display = 'flex';
            document.querySelector('.main-content').style.marginLeft = '150px';
            document.querySelector('.header').style.display = 'flex';
            document.querySelector('.content-header .btn-print').style.display = 'inline-flex';

            const currentStatus = document.querySelector('.status-badge').textContent.trim();
            if (currentStatus !== 'Selesai') {
                document.querySelector('.status-actions').style.display = 'block';
            }

            document.querySelector('.actions').style.display = 'flex';
        });
    </script>
</body>

</html>
