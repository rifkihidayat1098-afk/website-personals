<?php
include '../../config/koneksi.php';
$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM blog WHERE id=$id");
header('Location: index.php');
