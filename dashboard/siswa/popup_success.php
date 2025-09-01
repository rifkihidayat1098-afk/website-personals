<?php
    include '../actions/auth_check.php';
?>

 <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Pendaftaran Berhasil</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: #f5f7fa;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }

            .popup {
                background-color: #fff;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                text-align: center;
                max-width: 400px;
                animation: fadeIn 0.4s ease-in-out;
            }

            .popup h2 {
                color: #28a745;
                margin-bottom: 10px;
            }

            .popup p {
                color: #555;
                margin-bottom: 20px;
            }

            .popup button {
                background-color: #28a745;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 8px;
                cursor: pointer;
                transition: background-color 0.2s ease-in-out;
            }

            .popup button:hover {
                background-color: #218838;
            }

            @keyframes fadeIn {
                from {opacity: 0; transform: scale(0.95);}
                to {opacity: 1; transform: scale(1);}
            }
        </style>
    </head>
    <body>

        <div class="popup">
            <h2>✅ Pendaftaran Berhasil</h2>
            <p>Data Anda telah tersimpan dengan baik.</p>

            <?php if (isAdmin()) : ?>
                <button onclick="window.location.href='../dashboard/pendaftaran/index.php'">Kembali ke Dashboard</button>
            <?php elseif (isSiswa()) : ?>
                <button onclick="window.location.href='../dashboard/siswa/pendaftaran.php'">Kembali</button>
            <?php endif; ?>
        </div>
    </body>
    </html>