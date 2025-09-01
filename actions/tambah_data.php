<?php
include '../config/koneksi.php';
include 'auth_check.php';
if (!isAdmin())
    exit('Akses ditolak');

$nama = $_POST['nama_lengkap'];
$nisn = $_POST['nisn'];
$asal = $_POST['asal_sekolah'];
$tanggal = date('Y-m-d');

$query = "INSERT INTO pendaftaran (nama_lengkap, nisn, asal_sekolah, tanggal_daftar) 
          VALUES ('$nama', '$nisn', '$asal', '$tanggal')";
mysqli_query($koneksi, $query);

header('Location: ../dashboard/index.php');
