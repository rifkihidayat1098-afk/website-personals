<?php
include '../config/koneksi.php';
include '../actions/auth_check.php';

$userId = $_SESSION['user']['id'];

$query = "SELECT username, email, role FROM users WHERE id = '$userId'";
$result = mysqli_query($koneksi, $query);
$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - My Profile</title>
  <link rel="stylesheet" href="css/dashboard.css"/>
  <link rel="stylesheet" href="css/modal.css"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    .profile-container {
      margin-top: 10px;
      background: #fff;
      padding: 2rem;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }

    .profile-container h1 {
      margin-bottom: 1rem;
    }

    .profile-info {
      margin-bottom: 1rem;
    }

    .profile-info h3 {
      margin: 0 0 4px;
      color: #555;
    }

    .btn-update {
      background-color: #435ebe;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
    }

    .btn-update:hover {
      background-color:rgb(49, 69, 142);
    }

    .modal-content {
      background-color: #fefefe;
      margin: 5% auto;
      padding: 2rem;
      border: 1px solid #888;
      width: 100%;
      max-width: 500px;
      z-index: 2001;
      border-radius: 8px;
    }

    .btn-close {
      float: right;
      font-size: 20px;
      cursor: pointer;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 6px;
    }

    .form-group input {
      width: 100%;
      padding: 8px;
      box-sizing: border-box;
    }

    input[type="submit"] {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

<div class="dashboard-container">
  <?php include 'partials/sidebar.php'; ?>

  <main class="content">
    <?php include 'partials/navbar.php'; ?>
    
    <div class="profile-container">
      <h1>My Profile</h1>

      <div class="profile-info">
        <h3>Username</h3>
        <div><?= htmlspecialchars($user['username']) ?></div>
      </div>

      <div class="profile-info">
        <h3>Email</h3>
        <div><?= htmlspecialchars($user['email']) ?></div>
      </div>

      <div class="profile-info">
        <h3>Role</h3>
        <div><?= htmlspecialchars($user['role']) ?></div>
      </div>

      <button class="btn-update" onclick="openModal()">Update Profile</button>
    </div>
  </main>
</div>

<!-- Modal Edit -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="btn-close" onclick="closeModal()">&times;</span>
    <h2>Edit Profil</h2>
    <form action="../actions/update_profile.php" method="POST">
      <input type="hidden" name="id" value="<?= $userId ?>">

      <div class="form-group">
        <label for="edit-username">Username:</label>
        <input type="text" name="username" id="edit-username" value="<?= htmlspecialchars($user['username']) ?>" required>
      </div>

      <div class="form-group">
        <label for="edit-email">Email:</label>
        <input type="email" name="email" id="edit-email" value="<?= htmlspecialchars($user['email']) ?>" required>
      </div>

      <div class="form-group">
        <label for="current-password">Password Saat Ini:</label>
        <input type="password" name="current_password" required>
      </div>

      <div class="form-group">
        <label for="new-password">Password Baru (Opsional):</label>
        <input type="password" name="new_password">
      </div>

      <input type="submit" value="Update">
    </form>
  </div>
</div>

<script>
  function openModal() {
    document.getElementById("editModal").style.display = "block";
  }

  function closeModal() {
    document.getElementById("editModal").style.display = "none";
  }

  window.onclick = function(event) {
    const modal = document.getElementById("editModal");
    if (event.target === modal) {
      closeModal();
    }
  }
</script>

</body>
</html>
