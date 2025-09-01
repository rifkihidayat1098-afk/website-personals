<?php
$koneksi = mysqli_connect('localhost', 'root', '', 'sma');

if (!$koneksi) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}

?>
