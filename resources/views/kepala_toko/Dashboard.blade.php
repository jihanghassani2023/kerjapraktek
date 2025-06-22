<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kepala Toko - MG TECH</title>
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

        .dashboard-title {
            margin: 25px 0;
            font-size: 1.5em;
            color: #333;
        }

        .dashboard-title span {
            color: #888;
            font-size: 0.8em;
            margin-left: 10px;
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

        .stat-icon.karyawan {
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

        .stat-icon.karyawan i {
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
            flex-wrap: wrap;
            gap: 15px;
        }

        .section-title {
            font-size: 1.2em;
            color: #333;
        }

        .filter-controls {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-controls select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: white;
            color: #333;
            font-size: 14px;
            min-width: 120px;
        }

        .filter-controls select:focus {
            outline: none;
            border-color: #8c3a3a;
        }

        .filter-controls button {
            padding: 8px 15px;
            background-color: #8c3a3a;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .filter-controls button:hover {
            background-color: #6d2d2d;
        }

        .chart-container {
            height: 300px;
            width: 100%;
            margin-top: 20px;
        }

        .legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .legend-color {
            width: 15px;
            height: 15px;
            border-radius: 3px;
        }

        .legend-selesai {
            background-color: #28a745;
        }

        .legend-proses {
            background-color: #ffc107;
        }

        .legend-menunggu {
            background-color: #dc3545;
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

            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .filter-controls {
                width: 100%;
                justify-content: flex-start;
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
            <img src="{{ asset('img/Mg-Tech.png') }}" alt="MG Tech Logo"
                onerror="this.src='https://via.placeholder.com/80'">
        </div>
        <a href="{{ route('kepala-toko.dashboard') }}" class="menu-item active">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('user.index') }}" class="menu-item">
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
                <h2>Dashboard <span class="user-role">Kepala Toko</span></h2>
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
                <div class="stat-icon karyawan">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Teknisi</h3>
                    <p>{{ $teknisiCount ?? 0 }}</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon harian">
                    <i class="fas fa-money-bill"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Transaksi Harian</h3>
                    <p>Rp. {{ number_format($totalTransaksiHariIni ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bulanan">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>
                        @if ($selectedMonth === 'all')
                            Total Transaksi Tahunan
                        @else
                            Total Transaksi {{ $monthOptions[$selectedMonth] ?? 'Bulanan' }}
                        @endif
                    </h3>
                    <p>Rp. {{ number_format($totalTransaksiBulanIni ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="content-section">
            <div class="section-header">
                <h3 class="section-title">
                    @if ($selectedMonth === 'all')
                        Statistik Status Perbaikan Bulanan ({{ $selectedYear ?? date('Y') }})
                    @else
                        Statistik Status Perbaikan {{ $monthOptions[$selectedMonth] ?? 'Bulan' }}
                        {{ $selectedYear ?? date('Y') }} (Per Minggu)
                    @endif
                </h3>
                <div class="filter-controls">
                    <form method="GET" action="{{ route('kepala-toko.dashboard') }}"
                        style="display: flex; gap: 10px; align-items: center;">
                        <select name="year" id="yearSelect">
                            @foreach ($yearOptions ?? [] as $year)
                                <option value="{{ $year }}"
                                    {{ ($selectedYear ?? date('Y')) == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>

                        <select name="month" id="monthSelect">
                            @foreach ($monthOptions ?? [] as $value => $name)
                                <option value="{{ $value }}"
                                    {{ ($selectedMonth ?? 'all') == $value ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit">Filter</button>
                    </form>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="monthlyRepairChart"></canvas>
            </div>
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color legend-selesai"></div>
                    <span>Selesai</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color legend-proses"></div>
                    <span>Proses</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color legend-menunggu"></div>
                    <span>Menunggu</span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthlyData = @json($monthlyRepairCounts ?? []);

            const labels = monthlyData.map(item => item.month);
            const selesaiData = monthlyData.map(item => item.selesai);
            const prosesData = monthlyData.map(item => item.proses);
            const menungguData = monthlyData.map(item => item.menunggu);

            const ctx = document.getElementById('monthlyRepairChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Selesai',
                            data: selesaiData,
                            backgroundColor: '#28a745',
                            borderColor: '#218838',
                            borderWidth: 1
                        },
                        {
                            label: 'Proses',
                            data: prosesData,
                            backgroundColor: '#ffc107',
                            borderColor: '#e0a800',
                            borderWidth: 1
                        },
                        {
                            label: 'Menunggu',
                            data: menungguData,
                            backgroundColor: '#dc3545',
                            borderColor: '#c82333',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                title: function(context) {
                                    return context[0].label;
                                },
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y +
                                        ' perbaikan';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            grid: {
                                display: false
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 0
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                stepSize: 1,
                                callback: function(value) {
                                    return value + ' perbaikan';
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });

            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');
            const searchSuggestions = document.getElementById('searchSuggestions');
            let searchTimeout;

            function performSearch() {
                const searchTerm = searchInput.value.trim();
                if (searchTerm.length >= 1) {
                    window.location.href =
                        `{{ route('kepala-toko.search') }}?search=${encodeURIComponent(searchTerm)}`;
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
        });
    </script>
</body>

</html>
