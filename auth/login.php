<?php 
session_start();
include '../config.php';

$error = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']); // Hapus setelah ditampilkan

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Akun</title>
  <link rel="stylesheet" href="<?= $base_url ?>/assets/css/login.css">

  <style>
    .error-msg {
      background-color: #ffe0e0;
      color: #d8000c;
      border-left: 4px solid #d8000c;
      padding: 12px 16px;
      margin-bottom: 16px;
      border-radius: 6px;
      font-size: 0.95rem;
      font-weight: 500;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
  </style>

</head>
<body>
  <div class="login-container">
    <h2>Login</h2>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'register_berhasil'): ?>
      <p class="success-msg">Pendaftaran berhasil! Silakan login.</p>
    <?php endif; ?>

    <form action="<?= $base_url ?>/actions/login_process.php" method="POST" class="login-form">
      <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" required>
      </div>

      <div class="form-group">
        <label>Password:</label>
        <input type="password" name="password" required>
      </div>


       <?php if ($error): ?>
          <p class="error-msg"><?= htmlspecialchars($error) ?></p>
       <?php endif; ?>

      <button type="submit" class="btn-login">Login</button>
    </form>
   
    <p class="register-text">Belum punya akun? 
      <a href="register.php">Daftar di sini</a><br><br>
      <a href="forgot_password.php">Lupa Password?</a>
    </p>

    <p class="register-text">Kembali ke
      <a href="<?= $base_url ?>/index.php">Beranda</a>
    </p>

  </div>
</body>
</html>