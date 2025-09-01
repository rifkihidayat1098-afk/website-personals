<?php
include '../../actions/auth_check.php';
include '../../config/koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect jika belum login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$currentUser = $_SESSION['user'];

// Ambil ID user yang ingin didaftarkan dari parameter GET (misal dari halaman tabel users)
$userId = isset($_GET['id']) ? (int)$_GET['id'] : $currentUser['id'];

// Cek apakah user sudah pernah melakukan pendaftaran
$stmt = $koneksi->prepare("SELECT * FROM pendaftaran_siswa WHERE casis_id_registration = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Sudah mendaftar, redirect ke dashboard siswa atau tampilkan pesan
    header("Location: index.php?error=sudah_mendaftar");
    exit;
}

// Generate kode registrasi otomatis
$tahun = date('Y');
$randomNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT); // 0001 - 9999
$codeReg = "REG{$tahun}{$randomNumber}";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Detail Pendaftaran</title>
    <link rel="stylesheet" href="../css/dashboard.css"/>
    <link rel="stylesheet" href="../css/modal.css"/>
    <link rel="stylesheet" href="../css/siswa.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="dashboard-container">
    <?php include '../partials/sidebar.php'; ?>

    <main class="content">
        <?php include '../partials/navbar.php'; ?>

        <div class="form-container">
            <h2>Formulir Pendaftaran Siswa</h2>
            <form action="../../actions/proses_pendaftaran.php" method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label>Kode Pendaftaran</label>
                        <input type="text" name="code_registration" value="<?= htmlspecialchars($codeReg) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="full_name" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Tempat Lahir</label>
                        <input type="text" name="place_birthdate" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="birthdate" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" name="address" required>
                    </div>
                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="text" name="numphone" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Ayah</label>
                        <input type="text" name="father_name" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Ibu</label>
                        <input type="text" name="mother_name" required>
                    </div>
                </div>

                <div class="form-row">
                  <div class="form-group">
                      <label>Nama Wali</label>
                      <input type="text" name="wali_name" required>
                  </div>
                  <div class="form-group">
                      <label>NIK</label>
                      <input type="text" name="nik" pattern="\d{16}" maxlength="16" minlength="16" required>
                  </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                      <label>NISN</label>
                      <input type="text" name="nisn" pattern="\d{10}" maxlength="16" minlength="10" required>
                    </div>
                  <div class="form-group">
                      <label>Golongan Darah</label>
                      <select name="golongan_darah" class="form-control" required>
                        <option value="">-- Pilih Golongan Darah --</option>
                        <option value="O">O</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                      </select>
                    </div>
                </div> 

                <div class="form-row">
                    <div class="form-group">
                        <label>Asal Sekolah</label>
                        <input type="text" name="asal_school" required>
                    </div>
                    <div class="form-group">
                        <label>Nomor Ijazah</label>
                        <input type="text" name="ijazah_number" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Pas Foto (JPG/PNG, max 1MB)</label>
                        <input type="file" name="pas_photo" accept=".jpg,.jpeg,.png" required>
                    </div>
                    <div class="form-group">
                        <label>Dokumen Ijazah (PDF/DOC/DOCX, max 1MB)</label>
                        <input type="file" name="ijasah_document" accept=".pdf,.doc,.docx" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Dokumen Tambahan (PDF/DOC/DOCX)</label>
                        <input type="file" name="doc_tambahan" accept=".pdf,.doc,.docx">
                    </div>
                </div>

                <!-- Ini akan mengirim ID user yang diklik (bisa dari admin atau siswa login sendiri) -->
                <input type="hidden" name="casis_id_registration" value="<?= htmlspecialchars($userId) ?>">

                <div class="form-submit">
                    <button type="submit">Kirim Pendaftaran</button>
                </div>
            </form>
        </div>
    </main>
</div>

</body>
</html>
