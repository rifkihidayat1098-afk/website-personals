<?php
include '../config/koneksi.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = 'siswa';  // default role siswa

// Cek apakah email sudah terdaftar
$check = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");
if (mysqli_num_rows($check) > 0) {
    header('Location: ../auth/register.php?error=Email%20sudah%20terdaftar');
    exit;
}

// Proses simpan
$query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
$result = mysqli_query($koneksi, $query);

if ($result) {
    header('Location: ../auth/login.php?msg=register_berhasil');
} else {
    $errorMsg = urlencode('Gagal mendaftar: ' . mysqli_error($koneksi));
    header("Location: ../auth/register.php?error=$errorMsg");
}
