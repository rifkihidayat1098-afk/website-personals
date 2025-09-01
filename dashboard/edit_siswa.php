<?php
include '../config/koneksi.php';
include '../actions/auth_check.php';
if (!isAdmin())
    exit('Akses ditolak');

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pendaftaran WHERE id=$id"));
?>

<h2>Edit Pendaftaran</h2>
<form action="../actions/update_data.php" method="POST">
    <input type="hidden" name="id" value="<?= $data['id'] ?>">

    <label>Nama Lengkap:</label><br>
    <input type="text" name="nama_lengkap" value="<?= $data['nama_lengkap'] ?>" required><br><br>

    <label>NISN:</label><br>
    <input type="text" name="nisn" value="<?= $data['nisn'] ?>" required><br><br>

    <label>Asal Sekolah:</label><br>
    <input type="text" name="asal_sekolah" value="<?= $data['asal_sekolah'] ?>" required><br><br>

    <label>Status:</label><br>
    <select name="status">
        <option <?= $data['status'] == 'pending' ? 'selected' : '' ?>>pending</option>
        <option <?= $data['status'] == 'diterima' ? 'selected' : '' ?>>diterima</option>
        <option <?= $data['status'] == 'ditolak' ? 'selected' : '' ?>>ditolak</option>
    </select><br><br>

    <input type="submit" value="Update">
</form>
