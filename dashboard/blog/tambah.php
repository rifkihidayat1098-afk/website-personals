<?php
include '../../config/koneksi.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $konten = $_POST['konten'];
    mysqli_query($koneksi, "INSERT INTO blog (judul, konten) VALUES ('$judul', '$konten')");
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Blog</title>
    
    <link rel="stylesheet" href="../css/dashboard.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="dashboard-container">
    <?php include '../partials/sidebar.php'; ?>

    <main class="content">

    <div class="form-container">
        <h2>Tambah Artikel</h2>
        <form method="post">
            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="judul" required>
            </div>
            <label>Konten</label>
            <textarea name="konten" rows="5" required></textarea>
            <input type="submit" value="Simpan">
        </form>
    </div>
    </main>
</body>
</html>
