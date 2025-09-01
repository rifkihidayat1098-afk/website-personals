<?php
include '../config.php';

$token = $_GET['token'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="<?= $base_url ?>/assets/css/login.css">
</head>
<body>
<div class="login-container">
  <h2>Reset Password</h2>

  <form action="<?= $base_url ?>/actions/reset_password_process.php" method="POST" class="login-form">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

    <div class="form-group">
      <label>Password Baru:</label>
      <input type="password" name="password" required>
    </div>

    <button type="submit" class="btn-login">Reset Password</button>
  </form>
</div>
</body>
</html>