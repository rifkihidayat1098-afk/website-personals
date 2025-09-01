<?php
session_start();
include '../config/koneksi.php';

// Validasi awal
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../auth/login.php');
    exit;
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Cek input kosong
if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = 'Email dan password wajib diisi.';
    header('Location: ../auth/login.php');
    exit;
}

// Ambil data user
$stmt = $koneksi->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah user ditemukan
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        // Set session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'username' => $user['username'],
            'role' => $user['role']
        ];

        // Redirect berdasarkan role
        if ($user['role'] === 'siswa') {
            header('Location: ../dashboard/siswa/index.php');
        } elseif ($user['role'] === 'admin' || $user['role'] === 'kepala_sekolah') {
            header('Location: ../dashboard/index.php');
        } else {
            $_SESSION['login_error'] = 'Role tidak dikenali!';
            header('Location: ../auth/login.php');
        }

        exit;
    } else {
        $_SESSION['login_error'] = 'Password salah!';
        header('Location: ../auth/login.php');
        exit;
    }
} else {
    $_SESSION['login_error'] = 'Akun tidak ditemukan!';
    header('Location: ../auth/login.php');
    exit;
}
