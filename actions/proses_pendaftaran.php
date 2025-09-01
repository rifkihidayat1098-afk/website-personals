<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config/koneksi.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../dashboard/siswa/pendaftaran.php');
    exit;
}

// Helper: cek ekstensi
function allowed_extension($filename, $allowed) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, $allowed);
}

// Upload handler
function upload_file($file_input, $target_dir, $allowed_exts, $max_size_mb) {
    if (!isset($_FILES[$file_input]) || $_FILES[$file_input]['error'] === 4) {
        return null; // tidak ada file diunggah
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

// Set direktori upload
$upload_dir = '../uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Ambil data dari form
$code_registration = $_POST['code_registration'];
$full_name = $_POST['full_name'];
$place_birthdate = $_POST['place_birthdate'];
$birthdate = $_POST['birthdate'];
$address = $_POST['address'];
$numphone = $_POST['numphone'];
$father_name = $_POST['father_name'];
$mother_name = $_POST['mother_name'];
$asal_school = $_POST['asal_school'];
$ijazah_number = $_POST['ijazah_number'];
$casis_id_registration = $_POST['casis_id_registration'];
$wali_name = $_POST['wali_name'];
$nik = $_POST['nik'];
$nisn = $_POST['nisn'];
$golongan_darah = $_POST['golongan_darah'];


// Upload file utama
$photo_upload   = upload_file('pas_photo', $upload_dir, ['jpg', 'jpeg', 'png'], 1);
$ijazah_upload  = upload_file('ijasah_document', $upload_dir, ['pdf', 'doc', 'docx'], 2);
$tambahan_upload = upload_file('doc_tambahan', $upload_dir, ['pdf', 'doc', 'docx'], 2);

// Cek error upload
$errors = '';
if (isset($photo_upload['error'])) $errors .= $photo_upload['error'];
if (isset($ijazah_upload['error'])) $errors .= $ijazah_upload['error'];
if (isset($tambahan_upload['error'])) $errors .= $tambahan_upload['error'];

if (!empty($errors)) {
    echo "<h3>Gagal mengupload berkas:</h3>";
    echo "<div style='color:red;'>$errors</div>";
    echo "<a href='../dashboard/siswa/pendaftaran.php'>Kembali ke formulir</a>";
    exit;
}

// Ambil path file yang sukses di-upload
$pas_photo = isset($photo_upload['path']) ? basename($photo_upload['path']) : null;
$ijasah_document = isset($ijazah_upload['path']) ? basename($ijazah_upload['path']) : null;
$doc_tambahan = isset($tambahan_upload['path']) ? basename($tambahan_upload['path']) : null;


// Simpan data ke database
$query = "INSERT INTO pendaftaran_siswa (
    code_registration, full_name, place_birthdate, birthdate, address,
    numphone, father_name, mother_name, asal_school, ijazah_number,
    pas_photo, ijasah_document, doc_tambahan, casis_id_registration,
    wali_name, nik, nisn, golongan_darah
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


$stmt = $koneksi->prepare($query);
$stmt->bind_param(
    "sssssssssssssissss",
    $code_registration, $full_name, $place_birthdate, $birthdate, $address,
    $numphone, $father_name, $mother_name, $asal_school, $ijazah_number,
    $pas_photo, $ijasah_document, $doc_tambahan, $casis_id_registration,
    $wali_name, $nik, $nisn, $golongan_darah
);


if ($stmt->execute()) {
    // Ambil semua user dengan role admin sebagai penerima notifikasi
    $admin_query = "SELECT id FROM users WHERE role = 'admin'";
    $admin_result = $koneksi->query($admin_query);

    // Siapkan query notifikasi
    $notif_stmt = $koneksi->prepare("INSERT INTO notifications (
        casis_id_notif,
        reg_id_notif,
        receiver_id,
        title_notif,
        message_notif
    ) VALUES (?, ?, ?, ?, ?)");

    $title = "Pendaftaran Baru";
    $message = "Siswa <strong>$full_name</strong> telah melakukan pendaftaran dengan kode <strong>$code_registration</strong>.";

    // Loop setiap admin dan masukkan notifikasi
    while ($admin = $admin_result->fetch_assoc()) {
        $receiver_id = $admin['id'];
        $notif_stmt->bind_param(
            "isiss",
            $casis_id_registration,
            $code_registration,
            $receiver_id,
            $title,
            $message
        );
        $notif_stmt->execute();
    }
    include '../dashboard/siswa/popup_success.php';
    exit;
} else {
    $error_message = "Gagal menyimpan data: " . $stmt->error;
    include '../dashboard/siswa/popup_error.php';
}


?>
