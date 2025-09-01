<?php
include '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['judul'];
    $content = $_POST['konten'];
    $date = $_POST['tanggal'];

    // Sanitize the data
    $title = mysqli_real_escape_string($koneksi, $title);
    $content = mysqli_real_escape_string($koneksi, $content);
    $date = mysqli_real_escape_string($koneksi, $date);

    // Update query
    $query = "UPDATE news SET news_title = '$title', news_content = '$content', news_datestamp = '$date' WHERE id_news = '$id'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: ../../dashboard/berita/index.php");
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($koneksi);
    }
} else {
    echo "Invalid request method.";
}
?>
