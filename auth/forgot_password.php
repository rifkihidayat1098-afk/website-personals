<?php
include '../config.php';
$error = $_GET['error'] ?? null;
$success = $_GET['success'] ?? null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Lupa Password</title>
  <link rel="stylesheet" href="<?= $base_url ?>/assets/css/login.css">
</head>
<body>
<div class="login-container">
  <h2>Lupa Password</h2>

  <?php if ($error): ?>
    <p class="error-msg"><?= htmlspecialchars($error) ?></p>
  <?php elseif ($success): ?>
    <p class="success-msg"><?= htmlspecialchars($success) ?></p>
  <?php endif; ?>

  <form action="<?= $base_url ?>/actions/send_reset_link.php" method="POST" class="login-form">
    <div class="form-group">
      <label>Email:</label>
      <input type="email" name="email" required>
    </div>
    <button type="submit" class="btn-login">Kirim Link Reset</button>
  </form>
</div>
</body>
</html>