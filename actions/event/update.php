<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name_event = mysqli_real_escape_string($koneksi, $_POST['name_event']);
    $detail_event = mysqli_real_escape_string($koneksi, $_POST['detail_event']);
    $date_event = $_POST['date_event'];

    // Cek apakah ada file gambar yang diunggah
    if (!empty($_FILES['img_event']['name'])) {
        $img_name = $_FILES['img_event']['name'];
        $img_tmp = $_FILES['img_event']['tmp_name'];
        $img_size = $_FILES['img_event']['size'];

        // Validasi ukuran gambar maks 1MB (1MB = 1048576 byte)
        if ($img_size > 1048576) {
            echo "<script>
                    alert('Ukuran gambar terlalu besar! Maksimal 1MB.');
                    window.history.back();
                  </script>";
            exit;
        }

        // Buat nama unik dan path upload
        $new_img_name = time() . '_' . basename($img_name);
        $target_dir = "../../uploads/event/";
        $target_file = $target_dir . $new_img_name;

        // Pindahkan gambar ke folder target
        if (move_uploaded_file($img_tmp, $target_file)) {
            // Query update dengan gambar
            $query = "UPDATE event SET 
                        name_event = '$name_event', 
                        detail_event = '$detail_event', 
                        date_event = '$date_event', 
                        img_event = '$new_img_name' 
                      WHERE id_event = '$id'";
        } else {
            echo "Gagal mengupload gambar.";
            exit;
        }
    } else {
        // Jika gambar tidak diubah
        $query = "UPDATE event SET 
                    name_event = '$name_event', 
                    detail_event = '$detail_event', 
                    date_event = '$date_event' 
                  WHERE id_event = '$id'";
    }

    // Eksekusi query
    if (mysqli_query($koneksi, $query)) {
        header("Location: ../../dashboard/kegiatan/index.php?success=update");
        exit;
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
} else {
    echo "Akses tidak sah.";
}
?>
