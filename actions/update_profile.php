<?php
include '../config/koneksi.php';
session_start();

$userId = $_POST['id'];
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$email = mysqli_real_escape_string($koneksi, $_POST['email']);
$currentPassword = $_POST['current_password'];
$newPassword = $_POST['new_password'];

$query = "SELECT password FROM users WHERE id = '$userId'";
$result = mysqli_query($koneksi, $query);
$user = mysqli_fetch_assoc($result);

if (!$user || !password_verify($currentPassword, $user['password'])) {
    echo "<script>alert('Password saat ini salah!'); window.history.back();</script>";
    exit;
}

if (!empty($newPassword)) {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $updateQuery = "UPDATE users SET username='$username', email='$email', password='$hashedPassword' WHERE id='$userId'";
} else {
    $updateQuery = "UPDATE users SET username='$username', email='$email' WHERE id='$userId'";
}

if (mysqli_query($koneksi, $updateQuery)) {
    $_SESSION['user']['username'] = $username;
    $_SESSION['user']['email'] = $email;
    echo "<script>alert('Profil berhasil diperbarui'); window.location.href='../dashboard/my-profile.php';</script>";
} else {
    echo "<script>alert('Gagal memperbarui profil'); window.history.back();</script>";
}
