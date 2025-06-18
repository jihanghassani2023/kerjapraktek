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

        .login-btn {
            padding: 10px 20px;
            background-color: #8c3a3a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .login-btn:hover {
            background-color: #6d2d2d;
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
            position: relative;
        }

        .input-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            background-color: #f0f0f0;
        }



        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: none;
            text-align: left;
            padding-left: 2px;
        }

        .error-message.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            max-width: 420px;
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
            padding: 20px;
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

        .section-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
            margin-top: 24px;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title .icon {
            font-size: 20px;
            color: #8c3a3a;
            background: linear-gradient(45deg, #8c3a3a, #e74c3c);
            border-radius: 8px;
            padding: 8px;
            color: white;
        }

        .section-title .count {
            background: linear-gradient(45deg, #8c3a3a, #e74c3c);
            color: white;
            padding: 4px 10px;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(140, 58, 58, 0.3);
        }

        .repair-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 16px;
            border: none;
            box-shadow: 0 4px 20px rgba(140, 58, 58, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .repair-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #8c3a3a 0%, #ff6b6b 100%);
        }

        .repair-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(140, 58, 58, 0.15);
        }

        .repair-title {
            font-weight: 700;
            margin-bottom: 16px;
            color: #2c3e50;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
        }

        .repair-title span:first-child {
            background: linear-gradient(45deg, #8c3a3a, #e74c3c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .info-row {
            display: flex;
            margin-bottom: 10px;
            font-size: 14px;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .info-label {
            width: 120px;
            font-weight: 600;
            color: #6c757d;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            flex: 1;
            color: #495057;
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .status-menunggu {
            background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
            color: white;
        }

        .status-proses {
            background: linear-gradient(45deg, #ffa726, #ffcc02);
            color: white;
        }

        .status-selesai {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }

        .repair-card[data-status="Selesai"] {
            background-color: #f9f9f9;
            border-left: 4px solid #28a745;
        }

        .repair-card[data-status="Proses"] {
            border-left: 4px solid #ffaa00;
        }

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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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

        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }

        .completed-info {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            border: 1px solid #28a745;
            border-radius: 12px;
            padding: 12px;
            margin-top: 12px;
            font-size: 13px;
            color: #155724;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }

        .completed-info i {
            color: #28a745;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="diagonal-bg"></div>

    <div class="header">
        <div class="logo">
            <img src="{{ asset('img/Mg-Tech.png') }}" alt="MG Tech"
                onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'380\' height=\'380\' viewBox=\'0 0 60 60\'><rect width=\'60\' height=\'60\' fill=\'%23ffffff\'/><text x=\'50%\' y=\'50%\' font-size=\'30\' text-anchor=\'middle\' fill=\'%238c3a3a\' font-family=\'Arial\' dominant-baseline=\'middle\'>MG</text></svg>'">
        </div>
        <a href="{{ route('login') }}" class="login-btn">LOGIN</a>
    </div>

    <div class="main-content">
        @if (session('error'))
            <div class="tracking-container">
                <div class="alert">
                    {{ session('error') }}
                </div>
                <div class="tracking-form">
                    <div class="tracking-title">SILAHKAN<br>MASUKAN NOMOR TELEPON</div>
                    <form action="{{ route('tracking.check') }}" method="POST" id="trackingForm">
                        @csrf
                        <div class="input-group">
                            <input type="text"
                                   id="phoneInput"
                                   name="key"
                                   class="input-control"
                                   placeholder="Nomor Telepon Anda"
                                   maxlength="13"
                                   value="{{ old('key') }}">
                            <div class="error-message" id="errorMessage">Nomor telepon tidak boleh kosong.</div>
                        </div>
                        <button type="submit" class="submit-btn" id="submitBtn">SUBMIT</button>
                    </form>
                </div>
            </div>
        @elseif(isset($perbaikanAktif))
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
                        @if ($pelanggan->email)
                            <div class="info-row">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $pelanggan->email }}</div>
                            </div>
                        @endif
                    </div>

                    <!-- Status Perbaikan -->
                    <div class="section-title">
                        <i class="fas fa-tools icon"></i>
                        Status Perbaikan
                        <span class="count">{{ $perbaikanAktif->count() }}</span>
                    </div>

                    @if($perbaikanAktif->count() > 0)
                        @foreach ($perbaikanAktif as $perbaikan)
                            <div class="repair-card" data-status="{{ $perbaikan->status }}">
                                <div class="repair-title">
                                    <span>{{ $perbaikan->nama_device }}</span>
                                    <span class="status-badge status-{{ strtolower($perbaikan->status) }}">
                                        {{ $perbaikan->status }}
                                    </span>
                                </div>

                                <div class="info-row">
                                    <div class="info-label">Kode</div>
                                    <div class="info-value">{{ $perbaikan->id }}</div>
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
                                    <div class="info-value">
                                        {{ $perbaikan->tanggal_formatted }}</div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label">Teknisi</div>
                                    <div class="info-value">{{ $perbaikan->user->name ?? 'Belum ditugaskan' }}</div>
                                </div>

                                @if ($perbaikan->harga > 0)
                                    <div class="info-row">
                                        <div class="info-label">Estimasi Biaya</div>
                                        <div class="info-value">Rp. {{ number_format($perbaikan->harga, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endif

                                @if ($perbaikan->garansiItems && $perbaikan->garansiItems->count() > 0)
                                    <div class="info-row">
                                        <div class="info-label">Garansi</div>
                                        <div class="info-value">
                                            @foreach ($perbaikan->garansiItems as $garansi)
                                                <div style="margin-bottom: 3px;">
                                                    {{ $garansi->garansi_sparepart }}: {{ $garansi->garansi_periode }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @elseif ($perbaikan->garansi)
                                    <div class="info-row">
                                        <div class="info-label">Garansi</div>
                                        <div class="info-value">{{ $perbaikan->garansi }}</div>
                                    </div>
                                @endif

                                @if ($perbaikan->status === 'Selesai')
                                    <div class="completed-info">
                                        <i class="fas fa-check-circle"></i> Perbaikan selesai! Anda dapat mengambil device di toko kami.
                                    </div>
                                @endif

                                <!-- Progress terakhir -->
@php
    // FIXED: Gunakan method yang sudah difilter untuk menghindari duplikasi
    $distinctProses = $perbaikan->getDistinctProsesPengerjaan();
@endphp

@if ($distinctProses && $distinctProses->count() > 0)
    @php
        $latestProcess = $distinctProses->first();
    @endphp
    <div class="latest-progress">
        <div class="progress-header">
            <span>Progress Terakhir:</span>
            <span class="progress-date">{{ $latestProcess->created_at->format('d M Y H:i') }}</span>
        </div>
        <div class="progress-content">{{ $latestProcess->process_step }}</div>
        <div class="progress-link"
            onclick="toggleProgress('progress-{{ $perbaikan->id }}')">
            Lihat semua progress <i class="fas fa-chevron-down"></i>
        </div>
    </div>
@endif
                            </div>

                          <!-- Progress lengkap -->
@if ($distinctProses && $distinctProses->count() > 0)
    <div id="progress-{{ $perbaikan->id }}" class="full-progress" style="display: none;">
        <div class="progress-title">Riwayat Proses Pengerjaan</div>
        <div class="progress-timeline">
            {{-- FIXED: Gunakan $distinctProses yang sudah difilter --}}
            @foreach ($distinctProses as $process)
                <div class="progress-item">
                    <div class="progress-dot"></div>
                    <div class="progress-content">
                        <div class="progress-step">{{ $process->process_step }}</div>
                        <div class="progress-time">
                            {{ $process->created_at->format('d M Y H:i:s') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
                        @endforeach
                    @else
                        <div class="no-data">
                            Tidak ada perbaikan aktif saat ini.
                        </div>
                    @endif

                    <div style="text-align: center; margin-top: 30px;">
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
                    <form action="{{ route('tracking.check') }}" method="POST" id="trackingForm">
                        @csrf
                        <div class="input-group">
                            <input type="text"
                                   id="phoneInput"
                                   name="key"
                                   class="input-control"
                                   placeholder="Nomor Telepon Anda"
                                   maxlength="13"
                                   value="{{ old('key') }}">

                            @if ($errors->has('key'))
                                <div class="error-message show">{{ $errors->first('key') }}</div>
                            @else
                                <div class="error-message" id="errorMessage">Nomor telepon tidak boleh kosong.</div>
                            @endif
                        </div>
                        <button type="submit" class="submit-btn" id="submitBtn">SUBMIT</button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Script untuk toggle progress
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

        // Script validasi nomor telepon - HANYA TIDAK BOLEH KOSONG
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.getElementById('phoneInput');
            const errorMessage = document.getElementById('errorMessage');
            const trackingForm = document.getElementById('trackingForm');

            // Jika tidak ada element, keluar dari script
            if (!phoneInput) return;

            // Fungsi untuk filter hanya angka
            function filterNumbers(value) {
                return value.replace(/[^0-9]/g, '');
            }

            // Auto filter input hanya angka
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value;
                const numbersOnly = filterNumbers(value);

                if (value !== numbersOnly) {
                    e.target.value = numbersOnly;
                }
            });

            // Filter paste input
            phoneInput.addEventListener('paste', function(e) {
                setTimeout(() => {
                    const value = filterNumbers(e.target.value);
                    e.target.value = value;
                }, 0);
            });

            // Prevent non-numeric keypress
            phoneInput.addEventListener('keypress', function(e) {
                const char = String.fromCharCode(e.which);
                if (!/[0-9]/.test(char)) {
                    e.preventDefault();
                }
            });

            // Validasi HANYA tidak boleh kosong saat submit
            if (trackingForm) {
                trackingForm.addEventListener('submit', function(e) {
                    const phoneValue = phoneInput.value.trim();

                    if (!phoneValue || phoneValue === '') {
                        e.preventDefault();
                        if (errorMessage) {
                            errorMessage.classList.add('show');
                        }
                        phoneInput.focus();
                        return false;
                    }

                    // Hide error jika ada isi
                    if (errorMessage) {
                        errorMessage.classList.remove('show');
                    }
                });
            }
        });
    </script>
</body>
</html>
