<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/koneksi.php';
include '../auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_registration'] ?? null;

    if (!$id || !is_numeric($id)) {
        die("ID tidak valid.");
    }

    // Ambil data dari form
    $code_registration = $_POST['code_registration'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $place_birthdate = $_POST['place_birthdate'] ?? '';
    $birthdate = $_POST['birthdate'] ?? '';
    $address = $_POST['address'] ?? '';
    $numphone = $_POST['numphone'] ?? '';
    $father_name = $_POST['father_name'] ?? '';
    $mother_name = $_POST['mother_name'] ?? '';
    $asal_school = $_POST['asal_school'] ?? '';
    $ijazah_number = $_POST['ijazah_number'] ?? '';
    $status = $_POST['status'] ?? '';

    // Mapping dari label ke enum database
    if ($status === 'Menunggu') {
        $status = 'pending';
    } elseif ($status === 'Diterima') {
        $status = 'diterima';
    } elseif ($status === 'Ditolak') {
        $status = 'ditolak';
    }

    // Validasi enum status
    $valid_status = ['pending', 'diterima', 'ditolak'];
    if (!in_array($status, $valid_status)) {
        die("Status tidak valid.");
    }

    // Query update lengkap (langsung update semua data)
    $stmt = mysqli_prepare($koneksi, "UPDATE pendaftaran_siswa SET
        code_registration = ?, full_name = ?, place_birthdate = ?, birthdate = ?,
        address = ?, numphone = ?, father_name = ?, mother_name = ?, asal_school = ?,
        ijazah_number = ?, status = ?
        WHERE id_registration = ?");

    if (!$stmt) {
        die("Gagal menyiapkan query: " . mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param($stmt, "sssssssssssi",
        $code_registration,
        $full_name,
        $place_birthdate,
        $birthdate,
        $address,
        $numphone,
        $father_name,
        $mother_name,
        $asal_school,
        $ijazah_number,
        $status,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {
        header('Location: ../../dashboard/pendaftaran/index.php?update=success');
        exit;
    } else {
        echo "Gagal mengupdate data: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Akses tidak valid.";
}
?>
