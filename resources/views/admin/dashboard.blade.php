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
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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
        /* Search box styles */
        .search-container {
            margin: 20px 0;
            display: flex;
            max-width: 600px;
            position: relative; /* For suggestions dropdown */
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
        /* Suggestions dropdown styles */
        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('img/Mg-Tech.png') }}" alt="MG Tech Logo" onerror="this.src='https://via.placeholder.com/80'">
            <span>MG TECH</span>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="menu-item active">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.transaksi') }}" class="menu-item">
            <i class="fas fa-exchange-alt"></i>
            <span>Transaksi</span>
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
            <button type="submit" class="logout" style="width: 100%; border: none; cursor: pointer; background: none; text-align: left;">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>

    <div class="main-content">
        <div class="header">
            <h1 class="page-title">Dashboard <span>ADMIN</span></h1>
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

        <!-- Search box with autocomplete suggestions -->
        <div class="search-container">
            <input type="text" id="searchInput" class="search-input" placeholder="Cari berdasarkan kode, nama pelanggan, atau barang...">
            <button type="button" class="search-button">
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
                    <p>Rp. {{ number_format($totalTransaksiHariIni ?? \App\Models\Perbaikan::whereDate('tanggal_perbaikan', date('Y-m-d'))->sum('harga'), 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bulanan">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>Transaksi Bulan Ini</h3>
                    <p>Rp. {{ number_format($totalTransaksiBulanIni ?? \App\Models\Perbaikan::whereMonth('tanggal_perbaikan', date('m'))->whereYear('tanggal_perbaikan', date('Y'))->sum('harga'), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="content-section">
            <div class="section-header">
                <h3 class="section-title">Transaksi Terbaru</h3>
                <a href="{{ route('admin.transaksi') }}" class="section-action">Lihat Semua</a>
            </div>
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
                <tbody>
                    @php
                        $latestTransaksi = $latestTransaksi ?? \App\Models\Perbaikan::with(['user', 'pelanggan'])->orderBy('created_at', 'desc')->take(5)->get();
                    @endphp

                    @forelse($latestTransaksi as $t)
                    <tr onclick="window.location='{{ route('admin.transaksi.show', $t->id) }}';" style="cursor: pointer;">
                        <td>{{ $t->kode_perbaikan }}</td>
                        <td>{{ \Carbon\Carbon::parse($t->tanggal_perbaikan)->format('d/m/Y') }}</td>
                        <td>{{ $t->pelanggan->nama_pelanggan ?? '-' }}</td>
                        <td>{{ $t->nama_barang }}</td>
                        <td>{{ $t->user->name ?? '-' }}</td>
                        <td>
                            <span class="{{ $t->status == 'Selesai' ? 'status-active' : 'status-inactive' }}">
                                {{ $t->status }}
                            </span>
                        </td>
                        <td>Rp. {{ number_format($t->harga, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">Tidak ada data transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchSuggestions = document.getElementById('searchSuggestions');
            const searchButton = document.querySelector('.search-button');

            let debounceTimer;

            // Event listener for search input
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);

                const query = this.value.trim();

                // Clear suggestions if query is empty
                if (!query) {
                    searchSuggestions.innerHTML = '';
                    searchSuggestions.style.display = 'none';
                    return;
                }

                // Debounce the API call to avoid making too many requests
                debounceTimer = setTimeout(() => {
                    fetchSuggestions(query);
                }, 300);
            });

            // Event listener for search button
            searchButton.addEventListener('click', function() {
                const query = searchInput.value.trim();
                if (query) {
                    window.location.href = "{{ route('admin.search') }}?search=" + encodeURIComponent(query);
                }
            });

            // Event listener for Enter key
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    const query = this.value.trim();
                    if (query) {
                        window.location.href = "{{ route('admin.search') }}?search=" + encodeURIComponent(query);
                    }
                }
            });

            // Close suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                    searchSuggestions.style.display = 'none';
                }
            });

            // Function to fetch suggestions from the API
            function fetchSuggestions(query) {
                fetch("{{ route('search.suggestions') }}?query=" + encodeURIComponent(query), {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    displaySuggestions(data);
                })
                .catch(error => {
                    console.error('Error fetching suggestions:', error);
                });
            }

            // Function to display suggestions
            function displaySuggestions(suggestions) {
                searchSuggestions.innerHTML = '';

                if (suggestions.length === 0) {
                    searchSuggestions.style.display = 'none';
                    return;
                }

                suggestions.forEach(item => {
                    const suggestionItem = document.createElement('div');
                    suggestionItem.className = 'search-suggestion-item';

                    // Create suggestion content
                    let statusClass = '';
                    switch(item.status.toLowerCase()) {
                        case 'menunggu':
                            statusClass = 'suggestion-status-menunggu';
                            break;
                        case 'proses':
                            statusClass = 'suggestion-status-proses';
                            break;
                        case 'selesai':
                            statusClass = 'suggestion-status-selesai';
                            break;
                    }

                    suggestionItem.innerHTML = `
                        <div class="suggestion-title">${item.kode_perbaikan} - ${item.nama_barang}</div>
                        <div class="suggestion-details">
                            <div class="suggestion-detail">
                                <i class="fas fa-user"></i> ${item.nama_pelanggan}
                            </div>
                            <div class="suggestion-detail">
                                <i class="fas fa-calendar"></i> ${item.tanggal}
                            </div>
                            <div class="suggestion-status ${statusClass}">
                                ${item.status}
                            </div>
                        </div>
                    `;

                    // Add click event to redirect to detail page
                    suggestionItem.addEventListener('click', function() {
                        window.location.href = item.url;
                    });

                    searchSuggestions.appendChild(suggestionItem);
                });

                searchSuggestions.style.display = 'block';
            }
        });
    </script>
</body>
</html>
