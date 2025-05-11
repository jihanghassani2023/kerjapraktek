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
            background-color: #f0f0f0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header {
            background-color: #8c3a3a;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            display: flex;
            align-items: center;
        }
        .logo img {
            height: 40px;
            margin-right: 10px;
        }
        .logo span {
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        .login-btn {
            background-color: transparent;
            color: white;
            border: 2px solid white;
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }
        .login-btn:hover {
            background-color: white;
            color: #8c3a3a;
        }
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }
        .tracking-container {
            width: 100%;
            max-width: 500px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .tracking-header {
            background-color: #8c3a3a;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .tracking-header img {
            height: 60px;
            margin-bottom: 10px;
        }
        .tracking-header h2 {
            font-size: 20px;
        }
        .tracking-body {
            padding: 30px;
        }
        .tracking-form {
            text-align: center;
        }
        .tracking-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }
        .input-group {
            margin-bottom: 20px;
        }
        .input-control {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .submit-btn {
            width: 100%;
            padding: 15px;
            background-color: #8c3a3a;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
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
            max-width: 600px;
            background-color: white;
            border-radius: 10px;
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
        .status-menunggu {
            background-color: #ffeaea;
            color: #ff6b6b;
        }
        .status-proses {
            background-color: #fff4e0;
            color: #ffaa00;
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
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ asset('img/Mg-Tech.png') }}" alt="MG Tech" onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\' viewBox=\'0 0 40 40\'><rect width=\'40\' height=\'40\' fill=\'%23ffffff\'/><text x=\'50%\' y=\'50%\' font-size=\'20\' text-anchor=\'middle\' fill=\'%238c3a3a\' font-family=\'Arial\' dominant-baseline=\'middle\'>MG</text></svg>'">
            <span>MG TECH</span>
        </div>
        <a href="{{ route('login') }}" class="login-btn">LOGIN</a>
    </div>

    <div class="main-content">
        @if(session('error'))
            <div class="tracking-container">
                <div class="tracking-header">
                    <img src="{{ asset('img/Mg-Tech.png') }}" alt="MG Tech" onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'60\' height=\'60\' viewBox=\'0 0 60 60\'><rect width=\'60\' height=\'60\' fill=\'%23ffffff\'/><text x=\'50%\' y=\'50%\' font-size=\'30\' text-anchor=\'middle\' fill=\'%238c3a3a\' font-family=\'Arial\' dominant-baseline=\'middle\'>MG</text></svg>'">
                    <h2>Tracking Perbaikan</h2>
                </div>
                <div class="tracking-body">
                    <div class="alert">
                        {{ session('error') }}
                    </div>
                    <div class="tracking-form">
                        <div class="tracking-title">MASUKKAN NOMOR TELEPON ANDA</div>
                        <form action="{{ route('tracking.check') }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="key" class="input-control" placeholder="Masukkan nomor telepon Anda" required>
                            </div>
                            <button type="submit" class="submit-btn">CARI PERBAIKAN</button>
                        </form>
                    </div>
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
                    <div class="repair-card">
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
                    </div>
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
                <div class="tracking-header">
                    <img src="{{ asset('img/Mg-Tech.png') }}" alt="MG Tech" onerror="this.src='data:image/svg+xml;charset=UTF-8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'60\' height=\'60\' viewBox=\'0 0 60 60\'><rect width=\'60\' height=\'60\' fill=\'%23ffffff\'/><text x=\'50%\' y=\'50%\' font-size=\'30\' text-anchor=\'middle\' fill=\'%238c3a3a\' font-family=\'Arial\' dominant-baseline=\'middle\'>MG</text></svg>'">
                    <h2>Tracking Perbaikan</h2>
                </div>
                <div class="tracking-body">
                    <div class="tracking-form">
                        <div class="tracking-title">MASUKKAN NOMOR TELEPON ANDA</div>
                        <form action="{{ route('tracking.check') }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="key" class="input-control" placeholder="Masukkan nomor telepon Anda" required>
                            </div>
                            <button type="submit" class="submit-btn">CARI PERBAIKAN</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
