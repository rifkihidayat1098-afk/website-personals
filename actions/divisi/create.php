<?php
session_start();
include '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_divisi = mysqli_real_escape_string($koneksi, $_POST['nama_divisi']);

    $query = "INSERT INTO divisi (nama_divisi) VALUES ('$nama_divisi')";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['success'] = "Divisi berhasil ditambahkan.";
    } else {
        $_SESSION['error'] = "Gagal menambahkan divisi: " . mysqli_error($koneksi);
    }
}

header("Location: ../../dashboard/divisi.php");
exit;
