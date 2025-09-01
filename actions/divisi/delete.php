<?php
include '../../config/koneksi.php';
include '../auth_check.php';

// Cek apakah user adalah admin
if (!isAdmin()) {
    exit('Akses ditolak');
}

// Validasi ID divisi
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    exit('ID tidak valid.');
}

// Siapkan dan eksekusi query hapus
$stmt = mysqli_prepare($koneksi, "DELETE FROM divisi WHERE id_divisi = ?");
if (!$stmt) {
    die("Gagal menyiapkan statement: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id);
if (mysqli_stmt_execute($stmt)) {
    header('Location: ../../dashboard/divisi.php?delete=success');
    exit;
} else {
    echo "Gagal menghapus data: " . mysqli_stmt_error($stmt);
}

mysqli_stmt_close($stmt);
?>
