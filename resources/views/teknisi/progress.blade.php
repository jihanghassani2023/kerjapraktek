<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Progress Perbaikan - MG TECH</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        thead {
            background-color: #f5f5f5;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e3e3e3;
        }
        th {
            color: #666;
            font-weight: bold;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            margin-right: 5px;
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
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 5px;
            width: 300px;
            max-width: 90%;
            text-align: center;
        }
        .modal-title {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .btn {
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            border: none;
            font-weight: bold;
        }
        .btn-yes {
            background-color: #28a745;
            color: white;
        }
        .btn-no {
            background-color: #ff6b6b;
            color: white;
        }
        .btn-action {
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            border: none;
            transition: background-color 0.3s;
        }
        .btn-process {
            background-color: #fff4e0;
            color: #ffaa00;
        }
        .btn-process:hover {
            background-color: #ffe6c0;
        }
        .btn-complete {
            background-color: #e7f9e7;
            color: #28a745;
        }
        .btn-complete:hover {
            background-color: #d0f0d0;
        }
        .alert {
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-success {
            background-color: #e7f9e7;
            color: #28a745;
            border: 1px solid #d0f0d0;
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
        <a href="{{ route('teknisi.laporan') }}" class="menu-item">
            <i class="fas fa-clipboard-list"></i>
            <span>Laporan</span>
        </a>
        <a href="{{ route('teknisi.progress') }}" class="menu-item active">
            <i class="fas fa-tools"></i>
            <span>Progres</span>
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
            <h1 class="page-title">Progres <span>TEKNISI</span></h1>
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

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>KODE PERBAIKAN</th>
                    <th>NAMA BARANG</th>
                    <th>TANGGAL PERBAIKAN</th>
                    <th>MASALAH</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perbaikan as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->kode_perbaikan }}</td>
                    <td>{{ $p->nama_barang }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_perbaikan)->format('l, j F Y') }}</td>
                    <td>{{ $p->masalah }}</td>
                    <td>
                        @if($p->status == 'Menunggu')
                            <span class="status status-menunggu">{{ $p->status }}</span>
                            <button class="btn-action btn-process" data-id="{{ $p->id }}" data-status="Proses">Proses</button>
                        @elseif($p->status == 'Proses')
                            <span class="status status-proses">{{ $p->status }}</span>
                            <button class="btn-action btn-complete" data-id="{{ $p->id }}" data-status="Selesai">Selesai</button>
                        @else
                            <span class="status status-selesai">{{ $p->status }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data perbaikan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <h3 class="modal-title">APAKAH DEVICE INI AKAN ANDA KERJAKAN?</h3>
            <div class="modal-buttons">
                <button id="confirmYes" class="btn btn-yes">YA</button>
                <button id="confirmNo" class="btn btn-no">TIDAK</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusButtons = document.querySelectorAll('.btn-action');
            const confirmModal = document.getElementById('confirmModal');
            const confirmYes = document.getElementById('confirmYes');
            const confirmNo = document.getElementById('confirmNo');
            let currentButton = null;
            let currentId = null;
            let currentStatus = null;
            
            // Add click event to all status buttons
            statusButtons.forEach(button => {
                button.addEventListener('click', function() {
                    currentButton = this;
                    currentId = this.dataset.id;
                    currentStatus = this.dataset.status;
                    
                    // Update dialog text based on status
                    const statusText = currentStatus === 'Proses' ? 'KERJAKAN' : 'SELESAIKAN';
                    document.querySelector('.modal-title').textContent = `APAKAH DEVICE INI AKAN ANDA ${statusText}?`;
                    
                    // Show modal
                    confirmModal.style.display = 'block';
                });
            });
            
            // Confirm button (Yes)
            confirmYes.addEventListener('click', function() {
                if (currentId && currentStatus) {
                    updateStatus(currentId, currentStatus);
                }
                confirmModal.style.display = 'none';
            });
            
            // Cancel button (No)
            confirmNo.addEventListener('click', function() {
                confirmModal.style.display = 'none';
            });
            
            // Close modal if clicked outside
            window.addEventListener('click', function(event) {
                if (event.target === confirmModal) {
                    confirmModal.style.display = 'none';
                }
            });
            
            // Function to update status
            function updateStatus(id, status) {
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Send AJAX request
                fetch(`/teknisi/perbaikan/${id}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Reload page to show updated status
                        window.location.reload();
                    } else {
                        alert('Gagal mengubah status. Silakan coba lagi.');
                    }
                })
                .catch(error => {
                    console.error('Error updating status:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                });
            }
        });
    </script>
</body>
</html>