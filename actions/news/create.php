<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/koneksi.php';

$judul = $_POST['judul'];
$konten = $_POST['konten'];
$tanggal = $_POST['tanggal'];

$query = "INSERT INTO news (news_title, news_content, news_datestamp) VALUES ('$judul', '$konten', '$tanggal')";
if (mysqli_query($koneksi, $query)) {
    header("Location: ../../dashboard/berita/index.php");
    exit;
} else {
    echo "Gagal menambahkan berita: " . mysqli_error($koneksi);
}
