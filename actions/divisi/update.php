<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_divisi']);
    $nama_divisi = mysqli_real_escape_string($koneksi, $_POST['nama_divisi']);

    $query = "UPDATE divisi SET nama_divisi = '$nama_divisi' WHERE id_divisi = $id";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['success'] = "Divisi berhasil diperbarui.";
    } else {
        $_SESSION['error'] = "Gagal memperbarui divisi: " . mysqli_error($koneksi);
    }
}

header("Location: ../../dashboard/divisi.php");
exit;
