<?php
include '../../config/koneksi.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];

$query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
if (mysqli_query($koneksi, $query)) {
    header("Location: ../../dashboard/users.php");
    exit;
} else {
    echo "Gagal menambahkan user: " . mysqli_error($koneksi);
}
