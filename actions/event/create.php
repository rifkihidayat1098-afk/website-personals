<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/koneksi.php';

session_start();

// Cek jika method bukan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../dashboard/kegiatan/index.php');
    exit;
}

// Helper: cek ekstensi file
function allowed_extension($filename, $allowed) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, $allowed);
}

// Fungsi upload gambar
function upload_file($file_input, $target_dir, $allowed_exts, $max_size_mb) {
    if (!isset($_FILES[$file_input]) || $_FILES[$file_input]['error'] === 4) {
        return null; // Tidak ada file diunggah
    }

    $file = $_FILES[$file_input];
    $filename = basename($file["name"]);
    $file_tmp = $file["tmp_name"];
    $file_size = $file["size"];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_exts)) {
        return ['error' => "File $file_input harus berformat: " . implode(', ', $allowed_exts) . "<br>"];
    }

    if ($file_size > ($max_size_mb * 1024 * 1024)) {
        return ['error' => "Ukuran file $file_input maksimal {$max_size_mb}MB.<br>"];
    }

    $new_filename = uniqid($file_input . '_') . '.' . $ext;
    $destination = $target_dir . $new_filename;

    if (!move_uploaded_file($file_tmp, $destination)) {
        return ['error' => "Gagal mengupload file $file_input.<br>"];
    }

    return ['path' => $destination];
}

// Folder upload gambar event
$upload_dir = '../../uploads/event/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Ambil data dari form
$judul = $_POST['judul'];
$konten = $_POST['konten'];
$tanggal = $_POST['tanggal'];

// Upload gambar event
$gambar_event = upload_file('gambar', $upload_dir, ['jpg', 'jpeg', 'png'], 1);

// Cek error upload
if (isset($gambar_event['error'])) {
    echo "<h3>Gagal mengupload gambar:</h3>";
    echo "<div style='color:red;'>{$gambar_event['error']}</div>";
    echo "<a href='../../dashboard/kegiatan/index.php'>Kembali ke halaman event</a>";
    exit;
}

// Simpan ke database
$nama_file = isset($gambar_event['path']) ? basename($gambar_event['path']) : null;
$query = "INSERT INTO event (name_event, detail_event, date_event, img_event) 
          VALUES ('$judul', '$konten', '$tanggal', '$nama_file')";

if (mysqli_query($koneksi, $query)) {
    header("Location: ../../dashboard/kegiatan/index.php");
    exit;
} else {
    echo "Gagal menambahkan event: " . mysqli_error($koneksi);
}
?>
