<?php
include '../../config/koneksi.php';
include '../auth_check.php';

if (!isAdmin()) {
    exit('Akses ditolak');
}

// Ambil data dari form
$id_divisi = $_POST['id_divisi'] ?? null;
$judul_pertanyaan = trim($_POST['judul_pertanyaan'] ?? '');
$isi_pertanyaan = trim($_POST['isi_pertanyaan'] ?? '');
$tipe_pertanyaan = $_POST['tipe_pertanyaan'] ?? '';
$is_required = isset($_POST['is_required']) ? 1 : 0;
$opsi_texts = $_POST['opsi_text'] ?? [];
$nilai_opsionals = $_POST['nilai_opsional'] ?? [];

if (
    !$id_divisi || !is_numeric($id_divisi) ||
    empty($judul_pertanyaan) || empty($isi_pertanyaan) ||
    !in_array($tipe_pertanyaan, ['text', 'textarea', 'radio', 'checkbox', 'select'])
) {
    exit('Data tidak valid');
}

// Insert ke tabel pertanyaan_divisi
$stmt = mysqli_prepare($koneksi, "INSERT INTO pertanyaan_divisi 
    (id_divisi, judul_pertanyaan, isi_pertanyaan, tipe_pertanyaan, is_required) 
    VALUES (?, ?, ?, ?, ?)");

if (!$stmt) {
    die('Gagal mempersiapkan query: ' . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "isssi", $id_divisi, $judul_pertanyaan, $isi_pertanyaan, $tipe_pertanyaan, $is_required);
if (!mysqli_stmt_execute($stmt)) {
    die('Gagal menyimpan pertanyaan: ' . mysqli_stmt_error($stmt));
}

$id_pertanyaan = mysqli_insert_id($koneksi);
mysqli_stmt_close($stmt);

// Jika pertanyaannya bertipe pilihan, masukkan ke tabel opsi_pertanyaan
if (in_array($tipe_pertanyaan, ['radio', 'checkbox', 'select'])) {
    for ($i = 0; $i < count($opsi_texts); $i++) {
        $opsi = trim($opsi_texts[$i]);
        $nilai = trim($nilai_opsionals[$i] ?? null);
        $urutan = $i + 1;

        if ($opsi !== '') {
            $stmt_opsi = mysqli_prepare($koneksi, "INSERT INTO opsi_pertanyaan 
                (id_pertanyaan, opsi_text, nilai_opsional, urutan) 
                VALUES (?, ?, ?, ?)");

            if (!$stmt_opsi) {
                die('Gagal mempersiapkan query opsi: ' . mysqli_error($koneksi));
            }

            mysqli_stmt_bind_param($stmt_opsi, "issi", $id_pertanyaan, $opsi, $nilai, $urutan);
            if (!mysqli_stmt_execute($stmt_opsi)) {
                die('Gagal menyimpan opsi: ' . mysqli_stmt_error($stmt_opsi));
            }

            mysqli_stmt_close($stmt_opsi);
        }
    }
}

// Redirect setelah berhasil
header('Location: ../../dashboard/divisi.php?add_pertanyaan=success');
exit;
