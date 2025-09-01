<?php
include '../../config/koneksi.php';
$id = $_GET['id'];
$result = mysqli_query($koneksi, "SELECT * FROM blog WHERE id=$id");
$data = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $konten = $_POST['konten'];
    mysqli_query($koneksi, "UPDATE blog SET judul='$judul', konten='$konten' WHERE id=$id");
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Artikel</title>
    <link rel="stylesheet" href="../../assets/style.css">
</head>
<body>
<div class="container">
    <h1>Edit Artikel</h1>
    <form method="post">
        <label>Judul</label>
        <input type="text" name="judul" value="<?= $data['judul'] ?>" required>
        <label>Konten</label>
        <textarea name="konten" rows="5" required><?= $data['konten'] ?></textarea>
        <input type="submit" value="Update">
    </form>
</div>
</body>
</html>
