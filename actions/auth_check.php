<?php
// Mulai session hanya jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Fungsi pengecekan role
function isAdmin()
{
    return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}

function isKepalaSekolah()
{
    return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'kepala_sekolah';
}

function isSiswa()
{
    return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'siswa';
}
