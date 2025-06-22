<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin - MG TECH</title>
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

        .dashboard-title {
            margin: 25px 0;
            font-size: 1.5em;
            color: #333;
        }

        .dashboard-title span {
            color: #888;
            font-size: 0.8em;
            margin-left: 10px;
            font-weight: normal;
        }

        .stats-container {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            flex: 1;
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .stat-icon.teknisi {
            background-color: #f0e5e5;
        }

        .stat-icon.harian {
            background-color: #e9f0e5;
        }

        .stat-icon.bulanan {
            background-color: #e5e5f0;
        }

        .stat-icon i {
            font-size: 24px;
        }

        .stat-icon.teknisi i {
            color: #8c3a3a;
        }

        .stat-icon.harian i {
            color: #3a8c3a;
        }

        .stat-icon.bulanan i {
            color: #3a3a8c;
        }

        .stat-info h3 {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 5px;
        }

        .stat-info p {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
        }

        .content-section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 1.2em;
            color: #333;
        }

        .section-action {
            color: #8c3a3a;
            text-decoration: none;
            font-size: 0.9em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .status-active {
            color: #3a8c3a;
        }

        .status-inactive {
            color: #8c3a3a;
        }

        .welcome-message {
            background-color: #f0e5e5;
            border-left: 4px solid #8c3a3a;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .welcome-message h2 {
            color: #8c3a3a;
            margin-bottom: 10px;
        }

        .welcome-message p {
            color: #333;
            margin-bottom: 0;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
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
            background-color: #3a8c3a;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #2d6d2d;
        }

        .search-container {
            margin: 20px 0;
            display: flex;
            max-width: 1600px;
            position: relative;
        }

        .search-input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-right: none;
            border-radius: 5px 0 0 5px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: #8c3a3a;
        }

        .search-button {
            background-color: #8c3a3a;
            color: white;
            border: none;
            padding: 0 20px;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-button:hover {
            background-color: #6d2d2d;
        }

        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .search-suggestion-item {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .search-suggestion-item:last-child {
            border-bottom: none;
        }

        .search-suggestion-item:hover {
            background-color: #f5f5f5;
        }

        .suggestion-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }

        .suggestion-details {
            display: flex;
            font-size: 0.85em;
            color: #666;
            gap: 10px;
        }

        .suggestion-detail {
            display: flex;
            align-items: center;
        }

        .suggestion-detail i {
            margin-right: 5px;
            font-size: 0.9em;
        }

        .suggestion-status {
            font-size: 0.85em;
            padding: 2px 6px;
            border-radius: 3px;
            display: inline-block;
        }

        .suggestion-status-menunggu {
            background-color: #ffeaea;
            color: #ff6b6b;
        }

        .suggestion-status-proses {
            background-color: #fff4e0;
            color: #ffaa00;
        }

        .suggestion-status-selesai {
            background-color: #e7f9e7;
            color: #28a745;
        }

        .transaction-controls {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: white;
            color: #333;
            font-size: 14px;
            min-width: 130px;
            cursor: pointer;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .filter-select:focus {
            outline: none;
            border-color: #8c3a3a;
            box-shadow: 0 0 0 2px rgba(140, 58, 58, 0.1);
        }

        .filter-select:hover {
            border-color: #999;
        }

        .table-wrapper {
            position: relative;
            overflow-x: auto;
        }

        .transaction-row {
            transition: none;
        }

        .transaction-row:hover {
            background-color: #f8f9fa;
        }

        .status-menunggu {
            color: #dc3545;
        }

        .status-proses {
            color: #fd7e14;
        }

        .status-selesai {
            color: #28a745;
        }

        .no-results {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .no-results i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .no-results p {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .no-results small {
            color: #999;
        }

        table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }

        table td {
            border-bottom: 1px solid #f1f3f4;
        }

        table tbody tr:last-child td {
            border-bottom: none;
        }

        .transaction-row.filtered-out {
            display: none;
        }

        .transaction-row.filtered-in {
            display: table-row;
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

            .stats-container {
                flex-direction: column;
            }

            .action-buttons {
                flex-direction: column;
            }

            .search-container {
                max-width: 100%;
            }

            .transaction-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-select {
                min-width: auto;
                margin-bottom: 5px;
            }

            .table-wrapper {
                overflow-x: scroll;
            }

            table {
                min-width: 700px;
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
        <a href="{{ route('admin.dashboard') }}" class="menu-item active">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.transaksi') }}" class="menu-item">
            <i class="fas fa-chart-bar"></i>
            <span>Laporan</span>
        </a>
        <a href="{{ route('admin.pelanggan') }}" class="menu-item">
            <i class="fas fa-users"></i>
            <span>Pelanggan</span>
        </a>
        <a href="{{ route('admin.perbaikan.create') }}" class="menu-item">
            <i class="fas fa-tools"></i>
            <span>Tambah Perbaikan</span>
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
            <h1 class="page-title">Dashboard <span class="user-role">ADMIN</span></h1>
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

        <div class="search-container">
            <input type="text" id="searchInput" class="search-input"
                placeholder="Cari berdasarkan kode, nama pelanggan, atau barang...">
            <button type="button" id="searchButton" class="search-button">
                <i class="fas fa-search"></i>
            </button>
            <div id="searchSuggestions" class="search-suggestions"></div>
        </div>

        <div style="margin-top: 30px;"></div>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon teknisi">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Jumlah Teknisi</h3>
                    <p>{{ $totalTeknisi ?? \App\Models\User::where('role', 'teknisi')->count() }}</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon harian">
                    <i class="fas fa-money-bill"></i>
                </div>
                <div class="stat-info">
                    <h3>Transaksi Hari Ini</h3>
                    <p>Rp.
                        {{ number_format($totalTransaksiHariIni ?? \App\Models\Perbaikan::whereDate('tanggal_perbaikan', date('Y-m-d'))->sum('harga'), 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bulanan">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>Transaksi Bulan Ini</h3>
                    <p>Rp.
                        {{ number_format($totalTransaksiBulanIni ?? \App\Models\Perbaikan::whereMonth('tanggal_perbaikan', date('m'))->whereYear('tanggal_perbaikan', date('Y'))->sum('harga'), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="content-section">
            <div class="section-header">
                <h3 class="section-title">Transaksi Terbaru</h3>
                <div class="transaction-controls">
                    <select id="statusFilter" class="filter-select">
                        <option value="all">Semua Status</option>
                        <option value="Menunggu">Menunggu</option>
                        <option value="Proses">Proses</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                    <select id="periodFilter" class="filter-select">
                        <option value="today">Hari Ini</option>
                        <option value="week">7 Hari Terakhir</option>
                        <option value="month">Bulan Ini</option>
                        <option value="all">Semua</option>
                    </select>
                    <a href="{{ route('admin.transaksi') }}" class="section-action">Lihat Semua</a>
                </div>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Barang</th>
                            <th>Teknisi</th>
                            <th>Status</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody id="transactionTableBody">
                        @php
                            $latestTransaksi =
                                $latestTransaksi ??
                                \App\Models\Perbaikan::with(['user', 'pelanggan'])
                                    ->orderBy('created_at', 'desc')
                                    ->take(10)
                                    ->get();
                        @endphp

                        @forelse($latestTransaksi as $t)
                            <tr onclick="window.location='{{ route('admin.transaksi.show', $t->id) }}';"
                                style="cursor: pointer;" class="transaction-row" data-status="{{ $t->status }}"
                                data-date="{{ $t->tanggal_perbaikan }}">
                                <td>{{ $t->id }}</td>
                                <td>{{ $t->tanggal_formatted ?? \App\Helpers\DateHelper::formatTanggalIndonesia($t->tanggal_perbaikan) }}
                                </td>
                                <td>{{ $t->pelanggan->nama_pelanggan ?? '-' }}</td>
                                <td>{{ $t->nama_device }}</td>
                                <td>{{ $t->user->name ?? '-' }}</td>
                                <td>
                                    <span class="status-{{ strtolower($t->status) }}">
                                        {{ $t->status }}
                                    </span>
                                </td>
                                <td>Rp. {{ number_format($t->harga, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px;">
                                    <i class="fas fa-inbox"
                                        style="font-size: 48px; color: #ccc; margin-bottom: 15px; display: block;"></i>
                                    Tidak ada data transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div id="noResultsMessage" class="no-results" style="display: none;">
                    <i class="fas fa-search"></i>
                    <p>Tidak ada transaksi yang sesuai dengan filter</p>
                    <small>Coba ubah filter untuk melihat data lainnya</small>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');
            const searchSuggestions = document.getElementById('searchSuggestions');
            const statusFilter = document.getElementById('statusFilter');
            const periodFilter = document.getElementById('periodFilter');
            const tableBody = document.getElementById('transactionTableBody');
            const noResultsMessage = document.getElementById('noResultsMessage');
            const allRows = Array.from(tableBody.querySelectorAll('.transaction-row'));
            let searchTimeout;

            function performSearch() {
                const searchTerm = searchInput.value.trim();
                if (searchTerm.length >= 1) {
                    window.location.href = `/admin/search?search=${encodeURIComponent(searchTerm)}`;
                }
            }

            searchButton.addEventListener('click', performSearch);

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    performSearch();
                }
            });

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();

                if (query.length >= 2) {
                    searchTimeout = setTimeout(() => {
                        fetchSearchSuggestions(query);
                    }, 300);
                } else {
                    searchSuggestions.style.display = 'none';
                }
            });

            function fetchSearchSuggestions(query) {
                fetch(`{{ route('search.suggestions') }}?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        displaySearchSuggestions(data);
                    })
                    .catch(error => {
                        console.error('Error fetching suggestions:', error);
                        searchSuggestions.style.display = 'none';
                    });
            }

            function displaySearchSuggestions(suggestions) {
                if (suggestions.length === 0) {
                    searchSuggestions.style.display = 'none';
                    return;
                }

                const suggestionsList = suggestions.map(item => `
                    <div class="search-suggestion-item" onclick="selectSuggestion('${item.url}')">
                        <div class="suggestion-title">${item.kode_perbaikan} - ${item.nama_device}</div>
                        <div class="suggestion-details">
                            <span class="suggestion-detail">
                                <i class="fas fa-user"></i>
                                ${item.nama_pelanggan}
                            </span>
                            <span class="suggestion-detail">
                                <i class="fas fa-calendar"></i>
                                ${item.tanggal}
                            </span>
                            <span class="suggestion-status suggestion-status-${item.status.toLowerCase()}">
                                ${item.status}
                            </span>
                        </div>
                    </div>
                `).join('');

                searchSuggestions.innerHTML = suggestionsList;
                searchSuggestions.style.display = 'block';
            }
            window.selectSuggestion = function(url) {
                window.location.href = url;
            };

            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                    searchSuggestions.style.display = 'none';
                }
            });

            statusFilter.addEventListener('change', applyFilters);
            periodFilter.addEventListener('change', applyFilters);

            function applyFilters() {
                const selectedStatus = statusFilter.value;
                const selectedPeriod = periodFilter.value;

                let visibleRows = allRows.filter(row => {
                    const rowStatus = row.dataset.status;
                    const rowDate = new Date(row.dataset.date);
                    const today = new Date();

                    const statusMatch = selectedStatus === 'all' || rowStatus === selectedStatus;

                    let periodMatch = true;
                    if (selectedPeriod === 'today') {
                        periodMatch = rowDate.toDateString() === today.toDateString();
                    } else if (selectedPeriod === 'week') {
                        const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                        periodMatch = rowDate >= weekAgo;
                    } else if (selectedPeriod === 'month') {
                        periodMatch = rowDate.getMonth() === today.getMonth() &&
                            rowDate.getFullYear() === today.getFullYear();
                    }

                    return statusMatch && periodMatch;
                });

                allRows.forEach(row => {
                    if (visibleRows.includes(row)) {
                        row.style.display = '';
                        row.classList.remove('filtered-out');
                        row.classList.add('filtered-in');
                    } else {
                        row.classList.remove('filtered-in');
                        row.classList.add('filtered-out');
                        row.style.display = 'none';
                    }
                });

                if (visibleRows.length === 0) {
                    noResultsMessage.style.display = 'block';
                } else {
                    noResultsMessage.style.display = 'none';
                }
            }
        });
    </script>
</body>

</html>
