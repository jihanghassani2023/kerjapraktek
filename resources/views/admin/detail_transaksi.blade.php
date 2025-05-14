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

        /* Status actions styles */
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
            transition: background-color 0.2s;
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
                onerror="this.src='https://via.placeholder.com/80'">
        </div>
        <a href="{{ route('admin.dashboard') }}" class="menu-item">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.transaksi') }}" class="menu-item active">
            <i class="fas fa-exchange-alt"></i>
            <span>Transaksi</span>
        </a>

        <a href="{{ route('admin.transaksi') }}" class="back-btn">
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
            <h1 class="page-title">Detail Transaksi</h1>
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

                    <!-- Status update section - Very important -->
                    <!-- Change the status buttons section in detail_transaksi.blade.php -->
                  <div class="status-actions">
    <h4 class="status-title">Ubah Status Perbaikan</h4>
    <div class="status-buttons">
        @if($transaksi->status != 'Menunggu')
            <button type="button" class="btn-status btn-menunggu" data-status="Menunggu">
                Menunggu
            </button>
        @endif

        @if($transaksi->status != 'Proses')
            <button type="button" class="btn-status btn-proses" data-status="Proses">
                Proses
            </button>
        @endif

        @if($transaksi->status != 'Selesai')
            <button type="button" class="btn-status btn-selesai" data-status="Selesai">
                Selesai
            </button>
        @endif
    </div>
</div>
<div id="statusAlert" class="alert alert-success" style="display: none;"></div>
                    <!-- Add this inside the card-body div, perhaps before the status-actions section -->
                    <div class="actions" style="margin-top: 20px;">
                        <a href="{{ route('admin.perbaikan.edit', $transaksi->id) }}" class="btn btn-primary">
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
    // Execute when DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Get current status
        const currentStatus = '{{ $transaksi->status }}';

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

                // Set confirmation message
                confirmText.textContent = 'APAKAH ANDA YAKIN MENGUBAH STATUS MENJADI ' + pendingStatus + '?';

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

                // Show a brief success message
                const statusAlert = document.getElementById('statusAlert');
                if (statusAlert) {
                    statusAlert.textContent = 'Status berhasil diperbarui menjadi ' + pendingStatus;
                    statusAlert.style.display = 'block';

                    // Hide the alert after a few seconds
                    setTimeout(() => {
                        statusAlert.style.display = 'none';
                    }, 3000);
                }

                // Now send the status update request
                fetch('/admin/transaksi/{{ $transaksi->id }}/status', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        status: pendingStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        // If failed, revert UI changes
                        console.error('Failed to update status:', data.message);

                        // Revert status badge
                        if (statusBadge) {
                            statusBadge.className = 'status-badge status-' + oldStatus.toLowerCase();
                            statusBadge.textContent = oldStatus;
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

                        // Show error message
                        if (statusAlert) {
                            statusAlert.textContent = 'Gagal mengubah status: ' + (data.message || 'Terjadi kesalahan');
                            statusAlert.style.display = 'block';

                            // Hide the alert after a few seconds
                            setTimeout(() => {
                                statusAlert.style.display = 'none';
                            }, 3000);
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

        // Print functionality
        window.addEventListener('beforeprint', function() {
            document.querySelector('.sidebar').style.display = 'none';
            document.querySelector('.main-content').style.marginLeft = '0';
            document.querySelector('.header').style.display = 'none';
            document.querySelector('.content-header').style.display = 'none';
            document.querySelector('.status-actions').style.display = 'none';
            document.querySelector('.actions').style.display = 'none';
        });

        window.addEventListener('afterprint', function() {
            document.querySelector('.sidebar').style.display = 'flex';
            document.querySelector('.main-content').style.marginLeft = '220px';
            document.querySelector('.header').style.display = 'flex';
            document.querySelector('.content-header').style.display = 'flex';

            // Only show status actions if status is not "Selesai"
            const currentStatus = document.getElementById('statusBadge').textContent.trim();
            if (currentStatus !== 'Selesai') {
                document.querySelector('.status-actions').style.display = 'block';
            }

            document.querySelector('.actions').style.display = 'flex';
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
                prosesButton.onclick = showConfirmationModal;
                buttonsContainer.appendChild(prosesButton);

                const selesaiButton = document.createElement('button');
                selesaiButton.type = 'button';
                selesaiButton.className = 'btn-status btn-selesai';
                selesaiButton.setAttribute('data-status', 'Selesai');
                selesaiButton.textContent = 'Selesai';
                selesaiButton.onclick = showConfirmationModal;
                buttonsContainer.appendChild(selesaiButton);
            } else if (status === 'Proses') {
                // For Proses status, show Menunggu and Selesai buttons
                const menungguButton = document.createElement('button');
                menungguButton.type = 'button';
                menungguButton.className = 'btn-status btn-menunggu';
                menungguButton.setAttribute('data-status', 'Menunggu');
                menungguButton.textContent = 'Menunggu';
                menungguButton.onclick = showConfirmationModal;
                buttonsContainer.appendChild(menungguButton);

                const selesaiButton = document.createElement('button');
                selesaiButton.type = 'button';
                selesaiButton.className = 'btn-status btn-selesai';
                selesaiButton.setAttribute('data-status', 'Selesai');
                selesaiButton.textContent = 'Selesai';
                selesaiButton.onclick = showConfirmationModal;
                buttonsContainer.appendChild(selesaiButton);
            }
        }
    }

    // Helper function to show confirmation modal
    function showConfirmationModal() {
        const pendingStatus = this.getAttribute('data-status') || this.textContent.trim();
        const confirmText = document.getElementById('confirmationText');
        confirmText.textContent = 'APAKAH ANDA YAKIN MENGUBAH STATUS MENJADI ' + pendingStatus + '?';
        document.getElementById('confirmationModal').style.display = 'block';

        // Store the status in a global-ish scope for the confirmation handler
        window.pendingStatus = pendingStatus;
    }
</script>
    <!-- Custom Confirmation Modal -->
    <div id="confirmationModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
        <div
            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border-radius: 5px; text-align: center; width: 350px;">
            <h3 id="confirmationText" style="margin-bottom: 20px; font-weight: bold; color: #333333;">APAKAH ANDA
                YAKIN MENGUBAH STATUS?</h3>
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
