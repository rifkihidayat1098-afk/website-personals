<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Gagal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff3f3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .popup {
            background-color: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 400px;
            animation: fadeIn 0.3s ease-in-out;
        }

        .popup h2 {
            color: #dc3545;
            margin-bottom: 10px;
        }

        .popup p {
            color: #333;
            margin-bottom: 20px;
        }

        .popup button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
        }

        .popup button:hover {
            background-color: #c82333;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: scale(0.95);}
            to {opacity: 1; transform: scale(1);}
        }
    </style>
</head>
<body>
    <div class="popup">
        <h2>❌ Gagal</h2>
        <p><?= htmlspecialchars($error_message ?? 'Terjadi kesalahan.') ?></p>
        <button onclick="history.back()">Kembali</button>
    </div>
</body>
</html>
