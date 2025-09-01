<?php



include '../../actions/auth_check.php';

session_start();

// Redirect jika belum login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Cek role user
if ($_SESSION['user']['role'] !== 'siswa') {
    http_response_code(403);
    echo "Akses ditolak. Halaman ini hanya untuk siswa.";
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Profil Siswa</title>
  <link rel="stylesheet" href="../css/dashboard.css"/>
  <link rel="stylesheet" href="../css/siswa.css"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="dashboard-container">
    <?php include '../partials/sidebar.php'; ?>

        <main class="content">
            <div class="profile-container">
            <h1>Profil Siswa</h1>
            <div class="profile-card">
                <p><strong>Nama Lengkap:</strong> <?= htmlspecialchars($user['nama']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>NISN:</strong> <?= htmlspecialchars($user['nisn']) ?></p>
                <p><strong>Asal Sekolah:</strong> <?= htmlspecialchars($user['asal_sekolah']) ?></p>
            </div>
            <a href="logout.php" class="btn-logout">Update</a>
            </div>
        </main>
    </div>
</body>
</html>
