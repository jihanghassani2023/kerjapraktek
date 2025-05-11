<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Konfirmasi Status - MG TECH</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fff;
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
            text-decoration: none;
            display: inline-block;
        }
        .btn-yes {
            background-color: #28a745;
            color: white;
        }
        .btn-no {
            background-color: #ff6b6b;
            color: white;
        }
    </style>
</head>
<body>
    <div class="modal-content">
        <h3 class="modal-title">APAKAH DEVICE INI AKAN ANDA {{ $status == 'Proses' ? 'KERJAKAN' : 'SELESAIKAN' }}?</h3>
        <div class="modal-buttons">
            <a href="#" onclick="confirmStatus('{{ $status }}'); return false;" class="btn btn-yes">YA</a>
            <a href="{{ route('teknisi.progress') }}" class="btn btn-no">TIDAK</a>
        </div>
    </div>

    <script>
       function confirmStatus(status) {
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Send AJAX request
    fetch('/perbaikan/{{ $perbaikan->id }}/status', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect back to progress page
            window.location.href = '{{ route("teknisi.progress") }}';
        } else {
            alert('Gagal mengubah status. Silakan coba lagi.');
        }
    })
    .catch(error => {
        console.error('Error updating status:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
}
    </script>
</body>
</html>
