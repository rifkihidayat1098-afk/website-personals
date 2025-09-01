<?php
include '../../config/koneksi.php';
include '../auth_check.php';

if (!isAdmin()) {
    exit('Akses ditolak');
}

$id = $_GET['id'] ?? null;

if ($id && is_numeric($id)) {
    // Hapus terlebih dahulu opsi-opsinya
    $stmtOpsi = mysqli_prepare($koneksi, "DELETE FROM opsi_pertanyaan WHERE id_pertanyaan = ?");
    mysqli_stmt_bind_param($stmtOpsi, "i", $id);
    mysqli_stmt_execute($stmtOpsi);
    mysqli_stmt_close($stmtOpsi);

    // Hapus pertanyaannya
    $stmtPertanyaan = mysqli_prepare($koneksi, "DELETE FROM pertanyaan_divisi WHERE id_pertanyaan = ?");
    mysqli_stmt_bind_param($stmtPertanyaan, "i", $id);
    mysqli_stmt_execute($stmtPertanyaan);
    mysqli_stmt_close($stmtPertanyaan);
}

header('Location: ../../dashboard/divisi.php');
exit;
