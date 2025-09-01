<?php
include '../config/koneksi.php';
include '../actions/auth_check.php';
include '../config.php';

$role = $_SESSION['user']['role'];
$userId = $_SESSION['user']['id'];

// Query data pendaftaran tergantung role
if (isAdmin() || isKepalaSekolah()) {
    $queryPendaftaran = 'SELECT * FROM pendaftaran_siswa';
    $resultPendaftaran = mysqli_query($koneksi, $queryPendaftaran);
} else {
    $queryPendaftaran = "SELECT * FROM pendaftaran_siswa WHERE id = '$userId'";
    $resultPendaftaran = mysqli_query($koneksi, $queryPendaftaran);
}

// Query total user
$totalUser = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users"))['total'];

// Query total siswa
$totalSiswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE role = 'siswa'"))['total'];

// Query total pendaftaran siswa
$totalPendaftaran = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pendaftaran_siswa"))['total'];

// Query total status pending
$totalPending = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pendaftaran_siswa WHERE status = 'pending'"))['total'];

// Query total status diterima (terverifikasi)
$totalDiterima = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pendaftaran_siswa WHERE status = 'diterima'"))['total'];


// Total berita
$totalBerita = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM news"))['total'];

// Total kegiatan (event)
$totalKegiatan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM event"))['total'];


?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - <?= ucfirst($role) ?></title>
  <link rel="stylesheet" href="css/dashboard.css"/>
  <link rel="stylesheet" href="css/modal.css"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>

    .card-container {
      margin: 30px 0px;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }

    .card {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 65px
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .card h3 {
        margin: 0;
        font-size: 16px;
        color: #555;
    }

    .card .value {
        font-size: 20px;
        font-weight: 600;
        color: #0077b6;
        margin-top: auto;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    @media (max-width: 600px) {
        .cards {
            grid-template-columns: 1fr;
        }

        .card {
            height: auto;
        }
    }

  </style>
</head>
<body>

<div class="dashboard-container">
  <?php include 'partials/sidebar.php'; ?>

  <main class="content">
    <?php include 'partials/navbar.php'; ?>
    <h1>Dashboard - <?= ucwords(str_replace('_', ' ', $role)) ?></h1>

    <div class="card-container">
      <h3>Data User</h3>
      <div class="cards">
        <div class="card">
          <h3>Total User</h3>
          <div class="value">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round-icon lucide-users-round"><path d="M18 21a8 8 0 0 0-16 0"/><circle cx="10" cy="8" r="5"/><path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"/></svg>
            <?= $totalUser ?> 
          User
          </div>
        </div>

        <div class="card">
          <h3>Total Siswa</h3>
          <div class="value">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-backpack-icon lucide-backpack"><path d="M4 10a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/><path d="M8 10h8"/><path d="M8 18h8"/><path d="M8 22v-6a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v6"/><path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg>
            <?= $totalSiswa ?> Siswa</div>
        </div>

        <div class="card">
          <h3>Total Pendaftaran Siswa</h3>
          <div class="value">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-pen-icon lucide-clipboard-pen"><rect width="8" height="4" x="8" y="2" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5.5"/><path d="M4 13.5V6a2 2 0 0 1 2-2h2"/><path d="M13.378 15.626a1 1 0 1 0-3.004-3.004l-5.01 5.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/></svg>
            <?= $totalPendaftaran ?> Pendaftaran</div>
        </div>

        <div class="card">
          <h3>Total Siswa status Pending</h3>
          <div class="value">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-dot-dashed-icon lucide-circle-dot-dashed"><path d="M10.1 2.18a9.93 9.93 0 0 1 3.8 0"/><path d="M17.6 3.71a9.95 9.95 0 0 1 2.69 2.7"/><path d="M21.82 10.1a9.93 9.93 0 0 1 0 3.8"/><path d="M20.29 17.6a9.95 9.95 0 0 1-2.7 2.69"/><path d="M13.9 21.82a9.94 9.94 0 0 1-3.8 0"/><path d="M6.4 20.29a9.95 9.95 0 0 1-2.69-2.7"/><path d="M2.18 13.9a9.93 9.93 0 0 1 0-3.8"/><path d="M3.71 6.4a9.95 9.95 0 0 1 2.7-2.69"/><circle cx="12" cy="12" r="1"/></svg>
          <?= $totalPending ?> Siswa</div>
        </div>

        <div class="card">
          <h3>Total Siswa Diterima</h3>
          <div class="value">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-check-icon lucide-badge-check"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/></svg>
          <?= $totalDiterima ?> Siswa</div>
        </div>
      </div>
    </div>

    <div class="card-container">
      <h3>Data Berita dan Kegiatan</h3>
      <div class="cards">
        <div class="card">
          <h3>Total Berita</h3>
          <div class="value">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-newspaper-icon lucide-newspaper"><path d="M15 18h-5"/><path d="M18 14h-8"/><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0v-9a2 2 0 0 1 2-2h2"/><rect width="8" height="4" x="10" y="6" rx="1"/></svg>
            <?= $totalBerita ?> 
          Berita
          </div>
        </div>

        <div class="card">
          <h3>Total Kegiatan</h3>
          <div class="value">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-check2-icon lucide-calendar-check-2"><path d="M8 2v4"/><path d="M16 2v4"/><path d="M21 14V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8"/><path d="M3 10h18"/><path d="m16 20 2 2 4-4"/></svg>
            <?= $totalKegiatan ?> Kegiatan</div>
        </div>
      </div>
    </div>
  </main>
</div>

</body>
</html>
