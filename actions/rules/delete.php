<?php
include '../../config/koneksi.php';

$id_rule = $_GET['id'];

$query = "DELETE FROM rules WHERE id_rule = '$id_rule'";
mysqli_query($koneksi, $query);

header('Location: ../../dashboard/divisi.php');
?>
