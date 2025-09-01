<?php
include '../../config/koneksi.php';

$id_divisi = $_POST['id_divisi'];
$id_pertanyaan = $_POST['id_pertanyaan'];
$jawaban = $_POST['jawaban'];

$query = "INSERT INTO rules (id_divisi, id_pertanyaan, jawaban) VALUES ('$id_divisi', '$id_pertanyaan', '$jawaban')";
mysqli_query($koneksi, $query);

header('Location: ../../dashboard/divisi.php?add_rules=success');
?>
