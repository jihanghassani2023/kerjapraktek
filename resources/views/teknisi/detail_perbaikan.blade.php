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

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .status-actions {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
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

        .btn-print {
            background-color: #6c757d;
            color: white;
        }

        .btn-print:hover {
            background-color: #5a6268;
        }

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

        .timeline-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .timeline-section-title {
            margin-bottom: 15px;
            font-weight: bold;
            color: #333;
        }

        .timeline {
            position: relative;
            margin-left: 20px;
            padding-left: 20px;
            border-left: 2px solid #e0e0e0;
            margin-bottom: 20px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 15px;
            padding-bottom: 15px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
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

        .add-process-form {
            margin-top: 15px;
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
                <h2 class="content-title">Perbaikan #{{ $perbaikan->kode_perbaikan }}</h2>
                <a href="javascript:window.print()" class="btn btn-print">
                    <i class="fas fa-print"></i> Cetak
                </a>
            </div>

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
                        <div class="info-value">{{ $perbaikan->kode_perbaikan }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tanggal Perbaikan</div>
                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($perbaikan->tanggal_perbaikan)->format('d F Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nama Barang</div>
                        <div class="info-value">{{ $perbaikan->nama_barang }}</div>
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

                    <!-- Change the status buttons section in detail_perbaikan.blade.php -->
                    <div class="timeline-section">
                        <h4 class="timeline-section-title">Riwayat Proses Pengerjaan</h4>

                        @if (!empty($perbaikan->proses_pengerjaan) && count($perbaikan->proses_pengerjaan) > 0)
                            <div class="timeline">
                                @foreach ($perbaikan->proses_pengerjaan as $proses)
                                    <div class="timeline-item">
                                        <div class="timeline-marker">
                                            <i class="fas fa-circle"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h4 class="timeline-title">{{ $proses['step'] }}</h4>
                                            <p class="timeline-date">
                                                {{ \Carbon\Carbon::parse($proses['timestamp'])->format('d M Y H:i:s') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Belum ada proses yang direkam.</p>
                        @endif
                        <div class="add-process-form">
                            <form action="{{ route('perbaikan.add-process', $perbaikan->id) }}" method="POST"
                                class="d-flex">
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
                    <div class="status-actions">
                        <div class="status-title">Ubah Status Perbaikan</div>
                        <div class="status-buttons">
                            @if ($perbaikan->status != 'Menunggu')
                                <button type="button" class="btn-status btn-menunggu"
                                    data-status="Menunggu">Menunggu</button>
                            @endif

                            @if ($perbaikan->status != 'Proses')
                                <button type="button" class="btn-status btn-proses"
                                    data-status="Proses">Proses</button>
                            @endif

                            @if ($perbaikan->status != 'Selesai')
                                <button type="button" class="btn-status btn-selesai"
                                    data-status="Selesai">Selesai</button>
                            @endif
                        </div>
                    </div>
                    <!-- Add this inside the card-body div, just before or after the status-actions div -->
                    <div class="actions">
                        <a href="{{ route('perbaikan.edit', $perbaikan->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Perbaikan
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
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
                    {{-- <div class="action-button">
                        <a href="{{ route('perbaikan.edit-pelanggan', $perbaikan->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Data Pelanggan
                        </a>
                    </div> --}}
                </div>
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
                    // Get the status from data attribute
                    pendingStatus = this.getAttribute('data-status');

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
                    const statusBadge = document.querySelector('.status-badge');
                    const oldStatus = statusBadge ? statusBadge.textContent.trim() : '';

                    // OPTIMISTIC UI UPDATE - Update UI immediately before waiting for server
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

                    // Now send the status update request
                    fetch('/perbaikan/{{ $perbaikan->id }}/status', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({
                                status: pendingStatus,
                                tindakan_perbaikan: "Akan diupdate",
                                harga: 0
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                // If failed, revert UI changes
                                console.error('Failed to update status:', data.message);

                                // Revert status badge
                                if (statusBadge) {
                                    statusBadge.className = 'status-badge status-' + oldStatus
                                        .toLowerCase();
                                    statusBadge.textContent = oldStatus;
                                }

                                // Revert detail status
                                if (detailStatus) {
                                    detailStatus.textContent = oldStatus;
                                    if (oldStatus === 'Selesai') {
                                        detailStatus.style.color = '#28a745';
                                    } else if (oldStatus === 'Proses') {
                                        detailStatus.style.color = '#ffaa00';
                                    } else {
                                        detailStatus.style.color = '#ff6b6b';
                                    }
                                }

                                // Revert button visibility
                                if (oldStatus === 'Selesai') {
                                    const statusActions = document.querySelector('.status-actions');
                                    if (statusActions) {
                                        statusActions.style.display = 'none';
                                    }
                                } else {
                                    updateStatusButtons(oldStatus);
                                    const statusActions = document.querySelector('.status-actions');
                                    if (statusActions) {
                                        statusActions.style.display = 'block';
                                    }
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error updating status:', error);
                            // Could add code here to revert UI changes on network error
                        });
                }
            });

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
            if (status !== 'Selesai') {
                if (status === 'Menunggu') {
                    // For Menunggu status, show Proses and Selesai buttons
                    const prosesButton = document.createElement('button');
                    prosesButton.type = 'button';
                    prosesButton.className = 'btn-status btn-proses';
                    prosesButton.setAttribute('data-status', 'Proses');
                    prosesButton.textContent = 'Proses';
                    prosesButton.addEventListener('click', showConfirmationModal);
                    buttonsContainer.appendChild(prosesButton);

                    const selesaiButton = document.createElement('button');
                    selesaiButton.type = 'button';
                    selesaiButton.className = 'btn-status btn-selesai';
                    selesaiButton.setAttribute('data-status', 'Selesai');
                    selesaiButton.textContent = 'Selesai';
                    selesaiButton.addEventListener('click', showConfirmationModal);
                    buttonsContainer.appendChild(selesaiButton);
                } else if (status === 'Proses') {
                    // For Proses status, show Menunggu and Selesai buttons
                    const menungguButton = document.createElement('button');
                    menungguButton.type = 'button';
                    menungguButton.className = 'btn-status btn-menunggu';
                    menungguButton.setAttribute('data-status', 'Menunggu');
                    menungguButton.textContent = 'Menunggu';
                    menungguButton.addEventListener('click', showConfirmationModal);
                    buttonsContainer.appendChild(menungguButton);

                    const selesaiButton = document.createElement('button');
                    selesaiButton.type = 'button';
                    selesaiButton.className = 'btn-status btn-selesai';
                    selesaiButton.setAttribute('data-status', 'Selesai');
                    selesaiButton.textContent = 'Selesai';
                    selesaiButton.addEventListener('click', showConfirmationModal);
                    buttonsContainer.appendChild(selesaiButton);
                }
            }
        }

        // Helper function to show confirmation modal
        function showConfirmationModal() {
            const pendingStatus = this.getAttribute('data-status');
            const confirmText = document.getElementById('confirmationText');

            if (pendingStatus === 'Proses') {
                confirmText.textContent = 'APAKAH DEVICE INI AKAN ANDA KERJAKAN?';
            } else if (pendingStatus === 'Selesai') {
                confirmText.textContent = 'APAKAH DEVICE INI SUDAH SELESAI?';
            } else {
                confirmText.textContent = 'APAKAH ANDA YAKIN MENGUBAH STATUS?';
            }

            document.getElementById('confirmationModal').style.display = 'block';
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
    <div id="confirmationModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
        <div
            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border-radius: 5px; text-align: center; width: 350px;">
            <h3 id="confirmationText" style="margin-bottom: 20px; font-weight: bold; color: #333333;">APAKAH DEVICE
                INI AKAN ANDA KERJAKAN?</h3>
            <div>
                <button id="confirmYes"
                    style="padding: 8px 30px; background-color: #28a745; color: white; border: none; border-radius: 5px; margin-right: 10px; cursor: pointer; font-weight: bold;">YA</button>
                <button id="confirmNo"
                    style="padding: 8px 30px; background-color: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">TIDAK</button>
            </div>
        </div>
    </div>
</body>

</html>
