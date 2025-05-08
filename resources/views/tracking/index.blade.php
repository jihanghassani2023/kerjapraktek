<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MG TECH Palembang</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        body {
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #8c3a3a 50%, #f5f5f5 50%);
            background-attachment: fixed;
        }
        
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            width: 100%;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-text {
            margin-left: 15px;
        }
        
        .logo-mg {
            width: 200px;
            height: auto;
        }
        
        .logo-tech {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            margin-left: 5px;
        }
        
        .card {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 320px;
            text-align: center;
        }
        
        .card-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 50px;
            background-color: #f3f3f3;
            font-size: 14px;
            text-align: center;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #8c3a3a;
        }
        
        .btn-submit {
            background-color: #8c3a3a;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-submit:hover {
            background-color: #6d2d2d;
        }
        
        .login-link {
            margin-top: 30px;
            font-size: 14px;
        }
        
        .login-link a {
            color: #8c3a3a;
            text-decoration: none;
            font-weight: bold;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
        
        /* Track Result Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
            width: 90%;
            max-width: 500px;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            align-items: center;
        }
        
        .modal-title {
            font-size: 18px;
            font-weight: bold;
        }
        
        .close-button {
            font-size: 24px;
            cursor: pointer;
            color: #888;
        }
        
        .track-info {
            margin-bottom: 20px;
        }
        
        .info-item {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .info-label {
            width: 140px;
            font-weight: bold;
            color: #555;
        }
        
        .info-value {
            flex: 1;
            text-align: right;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }
        
        .status-menunggu {
            background-color: #ff6b6b;
        }
        
        .status-proses {
            background-color: #ffaa00;
        }
        
        .status-selesai {
            background-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="{{ asset('img/Mg-Tech.png') }}" alt="Apple Logo" class="logo-mg" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg'">
        </div>
        
        <div class="card">
            <h2 class="card-title">SILAHKAN MASUKAN KEY</h2>
            <div class="form-group">
                <input type="text" id="tracking-key" class="form-control" placeholder="Key Anda">
                <div class="error-message" id="error-message">Kode perbaikan tidak ditemukan</div>
            </div>
            <button id="submit-button" class="btn-submit">SUBMIT</button>
        </div>
        
        <div class="login-link">
            <a href="{{ route('login') }}">Login untuk Petugas</a>
        </div>
    </div>
    
    <!-- Track Result Modal -->
    <div class="modal" id="result-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Informasi Perbaikan</h3>
                <span class="close-button" id="close-modal">&times;</span>
            </div>
            
            <div class="track-info" id="track-info">
                <!-- Data will be filled via JavaScript -->
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const submitButton = document.getElementById('submit-button');
            const trackingKey = document.getElementById('tracking-key');
            const errorMessage = document.getElementById('error-message');
            const resultModal = document.getElementById('result-modal');
            const closeModal = document.getElementById('close-modal');
            const trackInfo = document.getElementById('track-info');
            
            submitButton.addEventListener('click', function() {
                // Reset display
                errorMessage.style.display = 'none';
                
                const key = trackingKey.value.trim();
                if (!key) {
                    errorMessage.textContent = 'Silakan masukkan kode perbaikan';
                    errorMessage.style.display = 'block';
                    return;
                }
                
                // Call the API to search for the tracking code
                fetch(`/api/tracking/${key}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Kode perbaikan tidak ditemukan');
                        }
                        return response.json();
                    })
                    .then(data => {
                        displayResult(data);
                        resultModal.style.display = 'flex';
                    })
                    .catch(error => {
                        errorMessage.textContent = error.message;
                        errorMessage.style.display = 'block';
                    });
                
                // This is a simulation for demo - remove in production
                simulateTracking(key);
            });
            
            closeModal.addEventListener('click', function() {
                resultModal.style.display = 'none';
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === resultModal) {
                    resultModal.style.display = 'none';
                }
            });
            
            // Simulation function for demo - remove in production
            function simulateTracking(key) {
                setTimeout(() => {
                    if (key.toUpperCase().startsWith('MG')) {
                        // Simulate found data
                        const mockData = {
                            kode_perbaikan: key.toUpperCase(),
                            nama_barang: "iPhone 13 Pro",
                            tanggal_perbaikan: "2025-05-07",
                            masalah: "Layar retak",
                            nama_pelanggan: "Budi Santoso",
                            status: "Proses",
                            teknisi: "Tengkuh"
                        };
                        
                        displayResult(mockData);
                        resultModal.style.display = 'flex';
                    } else {
                        // Simulate not found
                        errorMessage.textContent = 'Kode perbaikan tidak ditemukan';
                        errorMessage.style.display = 'block';
                    }
                }, 500);
            }
            
            function displayResult(data) {
                // Create result HTML
                let html = `
                <div class="info-item">
                    <div class="info-label">Kode Perbaikan</div>
                    <div class="info-value">${data.kode_perbaikan}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Nama Barang</div>
                    <div class="info-value">${data.nama_barang}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal</div>
                    <div class="info-value">${formatDate(data.tanggal_perbaikan)}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Masalah</div>
                    <div class="info-value">${data.masalah}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Pelanggan</div>
                    <div class="info-value">${data.nama_pelanggan}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="status-badge status-${data.status.toLowerCase()}">${data.status}</span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Teknisi</div>
                    <div class="info-value">${data.teknisi}</div>
                </div>`;
                
                trackInfo.innerHTML = html;
            }
            
            function formatDate(dateString) {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateString).toLocaleDateString('id-ID', options);
            }
        });
    </script>
</body>
</html>