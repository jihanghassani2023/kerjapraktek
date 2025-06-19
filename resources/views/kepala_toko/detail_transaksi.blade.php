<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Transaksi - MG TECH</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/receipt-print.css') }}" rel="stylesheet">
    <script src="{{ asset('js/receipt-generator.js') }}"></script>
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

        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (max-width: 992px) {
            .grid-container {
                grid-template-columns: 1fr;
            }
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

        .info-section {
            margin-bottom: 25px;
        }

        .info-section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            font-weight: bold;
            color: #8c3a3a;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .info-row {
            display: flex;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f5f5f5;
        }

        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
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
            border-radius: 3px;
            font-size: 14px;
            font-weight: bold;
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

        .timeline-container {
            display: none;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .timeline {
            position: relative;
            margin-left: 10px;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 2px;
            background-color: #e0e0e0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 25px;
            background-color: transparent !important;
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

        .real-time-clock {
            padding: 5px 8px;
            background-color: #f0f0f0;
            border-radius: 4px;
            font-size: 14px;
            color: #333;
            margin-left: 10px;
            display: inline-block;
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

        .timeline-item.status-menunggu .timeline-marker {
            border-color: #ff6b6b;
        }

        .timeline-item.status-menunggu .timeline-marker i {
            color: #ff6b6b;
        }

        .timeline-item.status-proses .timeline-marker {
            border-color: #ffaa00;
        }

        .timeline-item.status-proses .timeline-marker i {
            color: #ffaa00;
        }

        .timeline-item.status-selesai .timeline-marker {
            border-color: #28a745;
        }

        .timeline-item.status-selesai .timeline-marker i {
            color: #28a745;
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

        <div class="content-wrapper">
            <div class="content-header">
                <h2 class="content-title">Transaksi #{{ $transaksi->id }}</h2>
                <a href="javascript:window.print()" class="btn btn-print">
                    <i class="fas fa-print"></i> Cetak
                </a>
            </div>

            <div class="grid-container">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Perbaikan</h3>
                        <span id="statusBadge" class="status-badge status-{{ strtolower($transaksi->status) }}">
                            {{ $transaksi->status }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="info-section">
                            <div class="info-row">
                                <div class="info-label">Kode Perbaikan</div>
                                <div class="info-value">{{ $transaksi->id }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Tanggal Perbaikan</div>
                               <div class="info-value">
    {{ \App\Helpers\DateHelper::formatTanggalIndonesia($transaksi->tanggal_perbaikan) }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Nama Device</div>
                                <div class="info-value">{{ $transaksi->nama_device }}</div>
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
                                <div class="info-value">
                                    @if ($transaksi->garansiItems && $transaksi->garansiItems->count() > 0)
                                        @foreach ($transaksi->garansiItems as $garansi)
                                            <div style="margin-bottom: 5px;">
                                                {{ $garansi->garansi_sparepart }}: {{ $garansi->garansi_periode }}
                                            </div>
                                        @endforeach
                                    @else
                                        Tidak ada
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="info-section">
                            <h3 class="section-title">Informasi Pelanggan</h3>
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

                        <div class="info-section">
                            <h3 class="section-title">Informasi Teknisi</h3>
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

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Proses Pengerjaan</h3>
                        <div class="real-time-clock" id="realTimeClock">00:00:00</div>
                    </div>
                    <div class="card-body">
                        @php
                            $distinctProses = $transaksi->getDistinctProsesPengerjaan();
                        @endphp

                        @if($distinctProses && $distinctProses->count() > 0)
                            @php
                                $latestProcess = $distinctProses->first();
                            @endphp
                            <div class="latest-process">
                                <div class="process-header">
                                    <div class="process-title"><i class="fas fa-clock"></i> Progress Terakhir</div>
                                    <div class="process-date">{{ $latestProcess->created_at->format('d M Y H:i') }}</div>
                                </div>
                                <div class="process-content">{{ $latestProcess->process_step }}</div>
                                <div class="show-all-link" onclick="toggleTimeline()">
                                    Lihat semua progress <i class="fas fa-chevron-down" id="timeline-toggle-icon"></i>
                                </div>
                            </div>

                            <div id="timeline-container" class="timeline-container">
                                <div class="timeline">
                                    @foreach($distinctProses as $proses)
                                        @php
                                            $isStatusChange =
                                                $proses->process_step == 'Menunggu Antrian Perbaikan' ||
                                                $proses->process_step == 'Device Anda Sedang diproses' ||
                                                $proses->process_step == 'Device Anda Telah Selesai';
                                            $statusClass = '';
                                            if ($isStatusChange) {
                                                if ($proses->process_step == 'Menunggu Antrian Perbaikan') {
                                                    $statusClass = 'status-menunggu';
                                                } elseif ($proses->process_step == 'Device Anda Sedang diproses') {
                                                    $statusClass = 'status-proses';
                                                } elseif ($proses->process_step == 'Device Anda Telah Selesai') {
                                                    $statusClass = 'status-selesai';
                                                }
                                            }
                                        @endphp
                                        <div class="timeline-item {{ $isStatusChange ? 'status-change '.$statusClass : '' }}" style="background-color: transparent !important;">
                                            <div class="timeline-marker">
                                                <i class="fas {{ $isStatusChange ? 'fa-flag' : 'fa-circle' }}"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <div class="timeline-title">{{ $proses->process_step }}</div>
                                                <div class="timeline-date">{{ $proses->created_at->format('d M Y H:i:s') }}</div>
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
            </div>
        </div>
    </div>

    <script>
        // Setup receipt data untuk print
       // Setup receipt data untuk print - SAFEST VERSION
// Setup receipt data untuk print - KEPALA TOKO VERSION
document.addEventListener('DOMContentLoaded', function() {
    if (window.receiptGenerator) {
        window.receiptGenerator.setData({
            kode: {!! json_encode($transaksi->id) !!},
            tanggal: {!! json_encode(\App\Helpers\DateHelper::formatTanggalIndonesia($transaksi->tanggal_perbaikan)) !!},
            device: {!! json_encode($transaksi->nama_device) !!},
            kategori: {!! json_encode($transaksi->kategori_device ?? "Tidak ditentukan") !!},
            masalah: {!! json_encode($transaksi->masalah) !!},
            tindakan: {!! json_encode($transaksi->tindakan_perbaikan) !!},
            harga: {!! json_encode('Rp. ' . number_format($transaksi->harga, 0, ",", ".")) !!},
            garansi: {!! json_encode($transaksi->garansiItems && $transaksi->garansiItems->count() > 0 ? $transaksi->garansiItems->map(function($g) { return $g->garansi_sparepart . ': ' . $g->garansi_periode; })->join(', ') : 'Tidak ada') !!},
            pelanggan: {!! json_encode($transaksi->pelanggan->nama_pelanggan) !!},
            nomor_telp: {!! json_encode($transaksi->pelanggan->nomor_telp) !!},
            email: {!! json_encode($transaksi->pelanggan->email ?: "-") !!},
            teknisi: {!! json_encode($transaksi->user->name ?? "Tidak ada") !!},
            status: {!! json_encode($transaksi->status) !!}
        });
    }
});

        // Real-time clock function
        function updateRealTimeClock() {
            try {
                const now = new Date();
                const utcTime = now.getTime() + (now.getTimezoneOffset() * 60000);
                const jakartaTime = new Date(utcTime + (3600000 * 7));

                const hours = String(jakartaTime.getHours()).padStart(2, '0');
                const minutes = String(jakartaTime.getMinutes()).padStart(2, '0');
                const seconds = String(jakartaTime.getSeconds()).padStart(2, '0');

                const clockElement = document.getElementById('realTimeClock');
                if (clockElement) {
                    clockElement.textContent = `${hours}:${minutes}:${seconds}`;
                }
            } catch (error) {
                console.error("Error updating clock:", error);
            }
        }

        setInterval(updateRealTimeClock, 1000);
        updateRealTimeClock();

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
    </script>
</body>
</html>
