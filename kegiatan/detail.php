<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config.php'; 
include '../config/koneksi.php';
session_start();

// Cek apakah user login
$isLoggedIn = isset($_SESSION['user']);
$role = $isLoggedIn ? $_SESSION['user']['role'] : null;

// Tentukan path dashboard berdasarkan role
$dashboardPath = '#';
if ($isLoggedIn) {
  if ($role === 'admin' || $role === 'kepala_sekolah') {
    $dashboardPath = $base_url . '/dashboard/index.php';
  } elseif ($role === 'siswa') {
    $dashboardPath = $base_url . '/dashboard/siswa/index.php';
  }
}


$id_event = isset($_GET['id']) ? intval($_GET['id']) : 0;

$kegiatan = null;
if ($id_event > 0) {
    $sql = "SELECT * FROM event WHERE id_event = $id_event LIMIT 1";
    $result = mysqli_query($koneksi, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $kegiatan = mysqli_fetch_assoc($result);
    } else {
        $kegiatan = null;
    }
} else {
    $kegiatan = null;
}

?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detail Kegiatan - SMAN 2 SIMEULUE BAR</title>
    <link rel="stylesheet" href="../assets/style.css" />
    <link rel="stylesheet" href="../assets/details.css" />
  </head>
  <body>
    <header>
      <div class="container nav">
        <div class="logo-toggle">
          <a class="logo-content" href="/">
            <img src="../assets/img/logo.png" alt="logo" />
            <span> SMA Negeri<br />2 Simeulue Barat </span>
          </a>
          <button id="menu-toggle" class="menu-toggle">&#9776;</button>
        </div>

        <nav class="nav-desktop">
          <ul class="nav-links">
            <li>
              <a href="<?= $base_url ?>/index.php" class="icon-nav">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="lucide lucide-house-icon lucide-house"
                >
                  <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                  <path
                    d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"
                  />
                </svg>
                Beranda</a
              >
            </li>
            <li>
              <a href="<?= $base_url ?>/#tentang" class="icon-nav">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="lucide lucide-star-icon lucide-star"
                >
                  <path
                    d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"
                  />
                </svg>
                Tentang
              </a>
            </li>
            <li>
              <a href="<?= $base_url ?>/berita/index.php" class="icon-nav">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="lucide lucide-newspaper-icon lucide-newspaper"
                >
                  <path d="M15 18h-5" />
                  <path d="M18 14h-8" />
                  <path
                    d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0v-9a2 2 0 0 1 2-2h2"
                  />
                  <rect width="8" height="4" x="10" y="6" rx="1" />
                </svg>
                Berita
              </a>
            </li>
            <li>
              <a href="<?= $base_url ?>/kegiatan/index.php" class="icon-nav">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="lucide lucide-calendar-fold-icon lucide-calendar-fold"
                >
                  <path d="M8 2v4" />
                  <path d="M16 2v4" />
                  <path
                    d="M21 17V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h11Z"
                  />
                  <path d="M3 10h18" />
                  <path d="M15 22v-4a2 2 0 0 1 2-2h4" />
                </svg>
                Kegiatan
              </a>
            </li>
            <?php if ($isLoggedIn): ?>
            <li>
              <a href="<?= $dashboardPath ?>" class="btn">Dashboard</a>
            </li>
            <?php else: ?>
            <li>
              <a href="<?= $base_url ?>/auth/login.php" class="btn">Login</a>
            </li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>

      <!-- Sidebar untuk Mobile -->
      <div id="sidebar" class="sidebar">
        <a class="logo-content-sidebar" href="/">
          <img src="../assets/img/logo.png" alt="logo" />
          <span> SMA Negeri 2 Simeulue Barat </span>
        </a>
        <ul>
          <li>
            <a href="<?= $base_url ?>/index.php" class="icon-nav">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-house-icon lucide-house"
              >
                <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                <path
                  d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"
                />
              </svg>
              Beranda</a
            >
          </li>
          <li>
            <a href="<?= $base_url ?>/#tentang" class="icon-nav">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-star-icon lucide-star"
              >
                <path
                  d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"
                />
              </svg>
              Tentang
            </a>
          </li>
          <li>
            <a href="<?= $base_url ?>/berita/index.php" class="icon-nav">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-newspaper-icon lucide-newspaper"
              >
                <path d="M15 18h-5" />
                <path d="M18 14h-8" />
                <path
                  d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0v-9a2 2 0 0 1 2-2h2"
                />
                <rect width="8" height="4" x="10" y="6" rx="1" />
              </svg>
              Berita
            </a>
          </li>
          <li>
            <a href="<?= $base_url ?>/kegiatan/index.php" class="icon-nav">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-calendar-fold-icon lucide-calendar-fold"
              >
                <path d="M8 2v4" />
                <path d="M16 2v4" />
                <path
                  d="M21 17V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h11Z"
                />
                <path d="M3 10h18" />
                <path d="M15 22v-4a2 2 0 0 1 2-2h4" />
              </svg>
              Kegiatan
            </a>
          </li>
          <?php if ($isLoggedIn): ?>
          <li>
            <a href="<?= $dashboardPath ?>" class="btn">Dashboard</a>
          </li>
          <?php else: ?>
          <li>
            <a href="<?= $base_url ?>/auth/login.php" class="btn">Login</a>
          </li>
          <?php endif; ?>
        </ul>
      </div>
    </header>
 
    <main>
        <div class="container-detail">
            <?php if ($kegiatan): ?>
            <img src="../uploads/event/<?= htmlspecialchars($kegiatan['img_event']) ?>" alt="gambar event">
            <h1>
                <?= htmlspecialchars($kegiatan['name_event']) ?>
            </h1>
            <div class="date"><?= date('d M Y', strtotime($kegiatan['date_event'])) ?>
            </div>
            <div class="content"><?= nl2br(htmlspecialchars($kegiatan['detail_event'])) ?></div>
            <?php else: ?>
            <p>Kegiatan tidak ditemukan.</p>
            <?php endif; ?>
            <a href="index.php" class="back-link">&larr; Kembali ke daftar Kegiatan</a>
        </div>
    </main>

    <footer class="footer">
      <div class="footer-container">
        <div class="footer-brand">
          <a href="/" class="logo-footer">
            <img src="../assets/img/logo.png" alt="logo" />
            <div class="school-info">
              <p class="school-name">SMA Negeri 2 Simeulue Barat</p>
              <p class="school-address">
                Jl. Nusantara No. 02, Desa Sigulai,<br />
                Kec. Simeulue Barat, Kab. Simeulue, 23892
              </p>
            </div>
          </a>
        </div>

        <div class="footer-right">
          <div class="social-icons">
            <a href="#" target="_blank" aria-label="Instagram">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-instagram-icon lucide-instagram"
              >
                <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
              </svg>
            </a>
            <a href="#" target="_blank" aria-label="Facebook">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-facebook-icon lucide-facebook"
              >
                <path
                  d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"
                />
              </svg>
            </a>
            <a href="#" target="_blank" aria-label="Website">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-globe-icon lucide-globe"
              >
                <circle cx="12" cy="12" r="10" />
                <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                <path d="M2 12h20" />
              </svg>
            </a>

            <a href="#" target="_blank" aria-label="Email">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-mail-icon lucide-mail"
              >
                <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" />
                <rect x="2" y="4" width="20" height="16" rx="2" />
              </svg>
            </a>
          </div>
          <p class="copyright">
            &copy;
            <?= date('Y') ?>
            SMAN 2 SIMEULUE BARAT. Semua Hak Dilindungi.
          </p>
        </div>
      </div>
    </footer>
  </body>

  <script>
    const menuToggle = document.getElementById("menu-toggle");
    const sidebar = document.getElementById("sidebar");

    menuToggle.addEventListener("click", () => {
      sidebar.classList.toggle("active");

      if (sidebar.classList.contains("active")) {
        menuToggle.innerHTML = "&times;";
      } else {
        menuToggle.innerHTML = "&#9776;";
      }
    });

    document.querySelectorAll(".sidebar a").forEach((link) => {
      link.addEventListener("click", () => {
        sidebar.classList.remove("active");
        menuToggle.innerHTML = "&#9776;";
      });
    });

    window.addEventListener("resize", () => {
      if (window.innerWidth > 768) {
        sidebar.classList.remove("active");
        menuToggle.innerHTML = "&#9776;";
      }
    });

  </script>
</html>
