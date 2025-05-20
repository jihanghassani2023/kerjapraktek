<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MG Tech - Tracking Perbaikan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background-color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .diagonal-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #8c3a3a 50%, #fff 50%);
            z-index: -1;
        }
        .header {
            padding: 30px 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
        }
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
        }
        .logo img {
            height: 380px;
            margin-bottom: 0;
        }
        .logo-text {
            margin-left: 10px;
            color: #000;
            font-size: 28px;
            font-weight: bold;
        }
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 0 20px 20px;
            margin-top: -80px;
        }
        .tracking-container {
            width: 100%;
            max-width: 360px;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 30px 20px;
            text-align: center;
        }
        .tracking-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #000;
            text-transform: uppercase;
        }
        .input-group {
            margin-bottom: 25px;
        }
        .input-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            background-color: #f0f0f0;
        }
        .submit-btn {
            width: 120px;
            padding: 12px;
            background-color: #8c3a3a;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
        }
        .submit-btn:hover {
            background-color: #6d2d2d;
        }
        .alert {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .result-container {
            width: 100%;
            max-width: 360px;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .result-header {
            background-color: #8c3a3a;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .result-header h2 {
            font-size: 20px;
        }
        .result-body {
            padding: 30px;
        }
        .customer-info {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .customer-info h3 {
            margin-bottom: 10px;
            color: #333;
        }
        .repair-card {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #8c3a3a;
        }
        .repair-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .info-row {
            display: flex;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .info-label {
            width: 130px;
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
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        /* Tampilan untuk status perbaikan */
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
        /* Menambahkan sedikit transparansi pada kartu perbaikan yang sudah selesai */
        .repair-card[data-status="Selesai"] {
            background-color: #f9f9f9;
            border-left: 4px solid #28a745;
        }
        /* Kartu perbaikan yang sedang proses */
        .repair-card[data-status="Proses"] {
            border-left: 4px solid #ffaa00;
        }
        /* Kartu perbaikan yang menunggu */
        .repair-card[data-status="Menunggu"] {
            border-left: 4px solid #ff6b6b;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #f0f0f0;
            color: #333;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }
        .back-btn:hover {
            background-color: #e0e0e0;
        }

        /* Styling untuk progress pengerjaan */
        .latest-progress {
            margin-top: 15px;
            background-color: rgba(140, 58, 58, 0.05);
            border-radius: 5px;
            padding: 10px;
            border-left: 3px solid #8c3a3a;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .progress-date {
            font-weight: bold;
        }

        .progress-content {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .progress-link {
            font-size: 12px;
            color: #8c3a3a;
            cursor: pointer;
            text-align: right;
        }

        .progress-link:hover {
            text-decoration: underline;
        }

        .full-progress {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            margin-top: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .progress-title {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }

        .progress-timeline {
            position: relative;
            padding-left: 20px;
            border-left: 2px solid #ddd;
            margin-left: 5px;
        }

        .progress-item {
            position: relative;
            margin-bottom: 15px;
        }

        .progress-dot {
            position: absolute;
            left: -26px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #8c3a3a;
        }

        .progress-step {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .progress-time {
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="diagonal-bg"></div>

    <div class="header">
        <div class="logo">
            <img src="{{ asset('img/Mg-Tech.png') }}" alt="MG Tech" onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'380\' height=\'380\' viewBox=\'0 0 60 60\'><rect width=\'60\' height=\'60\' fill=\'%23ffffff\'/><text x=\'50%\' y=\'50%\' font-size=\'30\' text-anchor=\'middle\' fill=\'%238c3a3a\' font-family=\'Arial\' dominant-baseline=\'middle\'>MG</text></svg>'">
        </div>
        <a href="{{ route('login') }}" class="login-btn">LOGIN</a>
    </div>

    <div class="main-content">
        @if(session('error'))
            <div class="tracking-container">
                <div class="alert">
                    {{ session('error') }}
                </div>
                <div class="tracking-form">
                    <div class="tracking-title">SILAHKAN<br>MASUKAN NOMOR TELEPON</div>
                    <form action="{{ route('tracking.check') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="key" class="input-control" placeholder="Nomor Telepon Anda" required>
                        </div>
                        <button type="submit" class="submit-btn">SUBMIT</button>
                    </form>
                </div>
            </div>
        @elseif(isset($perbaikanList))
            <div class="result-container">
                <div class="result-header">
                    <h2>SELAMAT DATANG iGENGS!</h2>
                </div>
                <div class="result-body">
                    <div class="customer-info">
                        <h3>Informasi Pelanggan</h3>
                        <div class="info-row">
                            <div class="info-label">Nama</div>
                            <div class="info-value">{{ $pelanggan->nama_pelanggan }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">No. Telepon</div>
                            <div class="info-value">{{ $pelanggan->nomor_telp }}</div>
                        </div>
                        @if($pelanggan->email)
                        <div class="info-row">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $pelanggan->email }}</div>
                        </div>
                        @endif
                    </div>

                    <h3>Perbaikan Aktif ({{ $perbaikanList->count() }})</h3>

                    @foreach($perbaikanList as $perbaikan)
                    <div class="repair-card" data-status="{{ $perbaikan->status }}">
                        <div class="repair-title">
                            <span>{{ $perbaikan->nama_barang }}</span>
                            <span class="status-badge status-{{ strtolower($perbaikan->status) }}">
                                {{ $perbaikan->status }}
                            </span>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Kode</div>
                            <div class="info-value">{{ $perbaikan->kode_perbaikan }}</div>
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
                            <div class="info-label">Tanggal Masuk</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($perbaikan->tanggal_perbaikan)->format('d F Y') }}</div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Teknisi</div>
                            <div class="info-value">{{ $perbaikan->user->name ?? 'Belum ditugaskan' }}</div>
                        </div>

                        @if($perbaikan->harga > 0)
                        <div class="info-row">
                            <div class="info-label">Estimasi Biaya</div>
                            <div class="info-value">Rp. {{ number_format($perbaikan->harga, 0, ',', '.') }}</div>
                        </div>
                        @endif

                        @if($perbaikan->garansi)
                        <div class="info-row">
                            <div class="info-label">Garansi</div>
                            <div class="info-value">{{ $perbaikan->garansi }}</div>
                        </div>
                        @endif

                        <!-- Tampilkan progress terakhir -->
                       @if(!empty($perbaikan->proses_pengerjaan) && count($perbaikan->proses_pengerjaan) > 0)
    <?php
        $prosesArray = $perbaikan->proses_pengerjaan;
        $latestProcess = $prosesArray[count($prosesArray) - 1];
    ?>
    <div class="latest-progress">
        <div class="progress-header">
            <span>Progress Terakhir:</span>
            <span class="progress-date">{{ \Carbon\Carbon::parse($latestProcess['timestamp'])->format('d M Y H:i') }}</span>
        </div>
        <div class="progress-content">{{ $latestProcess['step'] }}</div>
        <div class="progress-link" onclick="toggleProgress('progress-{{ $perbaikan->id }}')">
            Lihat semua progress <i class="fas fa-chevron-down"></i>
        </div>
    </div>
@endif
                    </div>

                    <!-- Progress lengkap (awalnya tersembunyi) -->
                    @if(!empty($perbaikan->proses_pengerjaan) && count($perbaikan->proses_pengerjaan) > 0)
                        <div id="progress-{{ $perbaikan->id }}" class="full-progress" style="display: none;">
                            <div class="progress-title">Riwayat Proses Pengerjaan</div>
                            <div class="progress-timeline">
                                @foreach($perbaikan->proses_pengerjaan as $process)
                                    <div class="progress-item">
                                        <div class="progress-dot"></div>
                                        <div class="progress-content">
                                            <div class="progress-step">{{ $process['step'] }}</div>
                                            <div class="progress-time">{{ \Carbon\Carbon::parse($process['timestamp'])->format('d M Y H:i:s') }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @endforeach

                    <div style="text-align: center;">
                        <a href="{{ route('tracking.index') }}" class="back-btn">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="tracking-container">
                <div class="tracking-form">
                    <div class="tracking-title">SILAHKAN<br>MASUKAN NOMOR TELEPON</div>
                    <form action="{{ route('tracking.check') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="key" class="input-control" placeholder="Nomor Telepon Anda" required>
                        </div>
                        <button type="submit" class="submit-btn">SUBMIT</button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <script>
        function toggleProgress(id) {
            const progressElement = document.getElementById(id);
            const icon = event.target.tagName === 'I' ? event.target : event.target.querySelector('i');

            if (progressElement.style.display === 'none') {
                progressElement.style.display = 'block';
                if (icon) icon.className = 'fas fa-chevron-up';
            } else {
                progressElement.style.display = 'none';
                if (icon) icon.className = 'fas fa-chevron-down';
            }
        }
    </script>
</body>
</html>
