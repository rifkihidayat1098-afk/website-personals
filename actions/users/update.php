<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/koneksi.php';
include '../auth_check.php';

if (!isAdmin()) {
    die('Akses ditolak. Hanya admin yang dapat mengubah data pengguna.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = $_POST['id'];
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $role     = $_POST['role'];
    $password = $_POST['password'];

    if (!in_array($role, ['admin', 'kepala_sekolah', 'siswa'])) {
        die('Role tidak valid.');
    }

    // Update data
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET username='$username', email='$email', role='$role', password='$hashedPassword' WHERE id='$id'";
    } else {
        $query = "UPDATE users SET username='$username', email='$email', role='$role' WHERE id='$id'";
    }

    if (mysqli_query($koneksi, $query)) {
        header("Location: ../../dashboard/users.php?success=1");
        exit;
    } else {
        die('Gagal mengupdate data: ' . mysqli_error($koneksi));
    }
} else {
    die('Metode tidak diizinkan.');
}
?>
