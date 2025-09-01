<?php
include '../config.php';
include '../config/koneksi.php';

$email = $_POST['email'];
$token = bin2hex(random_bytes(50));

// Cek apakah email terdaftar
$result = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");
if (mysqli_num_rows($result) == 0) {
    header("Location: ../auth/forgot_password.php?error=Email tidak ditemukan");
    exit;
}

// Simpan token ke database
mysqli_query($koneksi, "INSERT INTO password_resets (email, token) VALUES ('$email', '$token')");

// Kirim email (simulasi: tampilkan link reset di browser)
$reset_link = "$base_url/auth/reset_password.php?token=$token";
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Link Reset Password</title>
  <link rel="stylesheet" href="<?= $base_url ?>/assets/css/login.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f2f4f8;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .reset-container {
      background-color: #fff;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      text-align: center;
      max-width: 500px;
    }
    .reset-container h2 {
      margin-bottom: 20px;
      color: #333;
    }
    .reset-container p {
      font-size: 16px;
      color: #555;
    }
    .reset-container a {
      display: inline-block;
      margin-top: 15px;
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
      transition: background-color 0.3s;
    }
    .reset-container a:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="reset-container">
    <h2>Link Reset Password Dikirim</h2>
    <p>Silakan klik link berikut untuk mengatur ulang password Anda:</p>
    <a href="<?= $reset_link ?>">Reset Password</a>
    <p style="margin-top: 10px; font-size: 13px; color: #888;"></p>
  </div>
</body>
</html>