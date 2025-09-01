<?php
session_start();
require_once '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Akses tidak valid.");
}

$id_registration = isset($_POST['id_registration']) ? (int)$_POST['id_registration'] : 0;
$id_divisi = isset($_POST['selected_divisi']) ? (int)$_POST['selected_divisi'] : 0;
$jawaban = $_POST['jawaban'] ?? [];

if ($id_registration <= 0) {
    die("ID pendaftaran siswa tidak ditemukan.");
}

if ($id_divisi <= 0) {
    die("Divisi tidak boleh kosong (validasi internal).");
}

if (empty($jawaban)) {
    die("Jawaban tidak boleh kosong.");
}

$koneksi->begin_transaction();

try {
    // Prepare statement untuk insert jawaban pertanyaan
    $stmtJawaban = $koneksi->prepare("INSERT INTO jawaban_pertanyaan (id_registration, id_pertanyaan, jawaban) VALUES (?, ?, ?)");
    if (!$stmtJawaban) {
        throw new Exception("Gagal prepare insert jawaban: " . $koneksi->error);
    }

    // Prepare statement untuk insert opsi jawaban
    $stmtOpsi = $koneksi->prepare("INSERT INTO jawaban_pertanyaan_opsi (id_jawaban, id_opsi) VALUES (?, ?)");
    if (!$stmtOpsi) {
        throw new Exception("Gagal prepare insert opsi jawaban: " . $koneksi->error);
    }

    foreach ($jawaban as $id_pertanyaan => $jawaban_isi) {
        $id_pertanyaan = (int)$id_pertanyaan;

        if (is_array($jawaban_isi)) {
            // Jika jawaban berupa array (multiple opsi, misalnya checkbox)
            $gabunganJawaban = implode(', ', array_map('trim', $jawaban_isi));

            // Insert jawaban gabungan ke jawaban_pertanyaan
            $stmtJawaban->bind_param("iis", $id_registration, $id_pertanyaan, $gabunganJawaban);
            if (!$stmtJawaban->execute()) {
                throw new Exception("Gagal insert jawaban pertanyaan: " . $stmtJawaban->error);
            }

            // Ambil id_jawaban yang baru saja diinsert
            $id_jawaban = $stmtJawaban->insert_id;

            // Insert opsi jawaban satu per satu
            foreach ($jawaban_isi as $id_opsi) {
                $id_opsi = (int)$id_opsi; // pastikan id opsi int
                $stmtOpsi->bind_param("ii", $id_jawaban, $id_opsi);
                if (!$stmtOpsi->execute()) {
                    throw new Exception("Gagal insert jawaban opsi: " . $stmtOpsi->error);
                }
            }
        } else {
            // Jawaban berupa teks / input biasa
            $jawaban_bersih = trim($jawaban_isi);
            $stmtJawaban->bind_param("iis", $id_registration, $id_pertanyaan, $jawaban_bersih);
            if (!$stmtJawaban->execute()) {
                throw new Exception("Gagal insert jawaban pertanyaan: " . $stmtJawaban->error);
            }
        }
    }

    $stmtJawaban->close();
    $stmtOpsi->close();

    // Update id_divisi di tabel pendaftaran_siswa
    $stmtDivisi = $koneksi->prepare("UPDATE pendaftaran_siswa SET id_divisi = ? WHERE id_registration = ?");
    if (!$stmtDivisi) {
        throw new Exception("Gagal prepare update divisi: " . $koneksi->error);
    }
    $stmtDivisi->bind_param("ii", $id_divisi, $id_registration);
    if (!$stmtDivisi->execute()) {
        throw new Exception("Gagal update divisi: " . $stmtDivisi->error);
    }
    $stmtDivisi->close();

    $koneksi->commit();

    $_SESSION['pesan'] = "Data berhasil disimpan.";
    header("Location: ../dashboard/siswa/index.php");
    exit;

} catch (Exception $e) {
    $koneksi->rollback();
    die("Gagal menyimpan data: " . $e->getMessage());
}
