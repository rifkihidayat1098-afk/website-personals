<?php
include '../config/koneksi.php';

$token = $_POST['token'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Ambil email berdasarkan token
$result = mysqli_query($koneksi, "SELECT * FROM password_resets WHERE token = '$token'");
if (mysqli_num_rows($result) === 0) {
    die('Token tidak valid!');
}

$data = mysqli_fetch_assoc($result);
$email = $data['email'];

// Update password
mysqli_query($koneksi, "UPDATE users SET password = '$password' WHERE email = '$email'");

// Hapus token setelah digunakan
mysqli_query($koneksi, "DELETE FROM password_resets WHERE email = '$email'");

header("Location: ../auth/login.php?msg=Password berhasil direset");
exit;