<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard {{ ucfirst($user->jabatan) }} - MG TECH</title>
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
        .stats-container {
            display: flex;
            gap: 20px;
            margin: 20px 0;
        }
        .stat-card {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border-left: 5px solid #8c3a3a;
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            background-color: rgba(140, 58, 58, 0.1);
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        .stat-icon i {
            color: #8c3a3a;
            font-size: 24px;
        }
        .stat-info h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-info p {
            font-size: 24px;
            font-weight: bold;
            color: #333;
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
        tbody tr {
            cursor: pointer;
            transition: background-color 0.3s;
        }
        tbody tr:hover {
            background-color: #f8f9fa;
        }
        .status {
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
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
        .welcome-box {
            background-color: #fff;
            border-left: 5px solid #8c3a3a;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .welcome-title {
            font-size: 18px;
            color: #8c3a3a;
            margin-bottom: 10px;
        }
        .welcome-text {
            color: #666;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('img/Mg-Tech.png') }}" alt="MG Tech Logo" onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\' viewBox=\'0 0 80 80\'><rect width=\'80\' height=\'80\' fill=\'%238c3a3a\'/><text x=\'50%\' y=\'50%\' font-size=\'30\' text-anchor=\'middle\' fill=\'white\' font-family=\'Arial\' dominant-baseline=\'middle\'>MG</text></svg>'">
        </div>
        <a href="{{ route('teknisi.dashboard') }}" class="menu-item active">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
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
            <h1 class="page-title">Dashboard <span>{{ strtoupper($user->jabatan) }}</span></h1>
            <div class="user-info">
                <div class="user-name">
                    <div>{{ $user->name }}</div>
                    <div class="user-role">{{ $user->jabatan }}</div>
                </div>
                <div class="user-avatar">
                    <img src="{{ asset('img/user.png') }}" alt="User" onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\' viewBox=\'0 0 40 40\'><circle cx=\'20\' cy=\'20\' r=\'20\' fill=\'%23f5f5f5\'/><text x=\'50%\' y=\'50%\' font-size=\'20\' text-anchor=\'middle\' fill=\'%238c3a3a\' font-family=\'Arial\' dominant-baseline=\'middle\'>{{ substr($user->name, 0, 1) }}</text></svg>'">
                </div>
            </div>
        </div>



        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>Sedang Menunggu</h3>
                    <p>{{ $sedangMenunggu }}</p>
                </div>
            </div>
             <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="stat-info">
                    <h3>Sedang Proses</h3>
                    <p>{{ $sedangProses }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>Perbaikan Selesai Hari Ini</h3>
                    <p>{{ $perbaikanSelesaiHari }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-info">
                    <h3>Perbaikan Selesai Bulan Ini</h3>
                    <p>{{ $perbaikanSelesaiBulan }}</p>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; margin-bottom: 20px;">
            <h2 style="margin: 0;">Daftar Perbaikan Yang Ditugaskan</h2>

            <!-- Filter Section -->
            <div style="display: flex; gap: 15px; align-items: center;">
                <select id="statusFilter" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                    <option value="">Semua Status</option>
                    <option value="menunggu">Menunggu</option>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                </select>

                <select id="sortBy" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                    <option value="terbaru">Terbaru</option>
                    <option value="terlama">Terlama</option>
                    <option value="device">Nama Device A-Z</option>
                    <option value="pelanggan">Nama Pelanggan A-Z</option>
                </select>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>KODE PERBAIKAN</th>
                    <th>NAMA DEVICE</th>
                    <th>PELANGGAN</th>
                    <th>TANGGAL PERBAIKAN</th>
                    <th>MASALAH</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perbaikan as $index => $p)
                <tr onclick="window.location.href='{{ route('perbaikan.show', $p->id) }}';">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->nama_device }}</td>
                    <td>{{ $p->pelanggan->nama_pelanggan ?? 'N/A' }}</td>
                    <td>{{ $p->tanggal_formatted ?? \App\Helpers\DateHelper::formatTanggalIndonesia($p->tanggal_perbaikan) }}</td>
                    <td>{{ $p->masalah }}</td>
                    <td>
                        <span class="status status-{{ strtolower($p->status) }}">{{ $p->status }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada perbaikan yang ditugaskan kepada Anda</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        // Filter functionality
        let originalRows = [];

        document.addEventListener('DOMContentLoaded', function() {
            // Store original rows
            const tbody = document.querySelector('tbody');
            originalRows = Array.from(tbody.querySelectorAll('tr:not(.empty-row)'));

            // Status filter
            document.getElementById('statusFilter').addEventListener('change', function() {
                filterTable();
            });

            // Sort filter
            document.getElementById('sortBy').addEventListener('change', function() {
                filterTable();
            });
        });

        function filterTable() {
            const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
            const sortBy = document.getElementById('sortBy').value;
            const tbody = document.querySelector('tbody');

            let filteredRows = [...originalRows];

            // Apply status filter
            if (statusFilter) {
                filteredRows = filteredRows.filter(row => {
                    const statusCell = row.querySelector('.status');
                    if (statusCell) {
                        return statusCell.textContent.toLowerCase().includes(statusFilter);
                    }
                    return false;
                });
            }

            // Apply sorting
            switch(sortBy) {
                case 'terlama':
                    filteredRows.reverse();
                    break;
                case 'device':
                    filteredRows.sort((a, b) => {
                        const deviceA = a.cells[2].textContent.toLowerCase();
                        const deviceB = b.cells[2].textContent.toLowerCase();
                        return deviceA.localeCompare(deviceB);
                    });
                    break;
                case 'pelanggan':
                    filteredRows.sort((a, b) => {
                        const pelangganA = a.cells[3].textContent.toLowerCase();
                        const pelangganB = b.cells[3].textContent.toLowerCase();
                        return pelangganA.localeCompare(pelangganB);
                    });
                    break;
                // 'terbaru' is default, no sorting needed
            }

            // Clear tbody and add filtered rows
            tbody.innerHTML = '';

            if (filteredRows.length > 0) {
                filteredRows.forEach((row, index) => {
                    // Update row number
                    row.cells[0].textContent = index + 1;
                    tbody.appendChild(row);
                });
            } else {
                // Add empty row
                const emptyRow = document.createElement('tr');
                emptyRow.className = 'empty-row';
                emptyRow.innerHTML = '<td colspan="7" style="text-align: center; color: #999; padding: 40px;">Tidak ada data yang sesuai dengan filter</td>';
                tbody.appendChild(emptyRow);
            }
        }
    </script>
</body>
</html>
