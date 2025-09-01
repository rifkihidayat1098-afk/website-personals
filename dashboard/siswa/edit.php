<?php
include '../../config/koneksi.php';
include '../../actions/auth_check.php';
include '../../config.php';

$role = $_SESSION['user']['role'];
$userId = $_SESSION['user']['id'];

$query = "SELECT * FROM pendaftaran_siswa WHERE casis_id_registration = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Proses update saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [];
    $params = [];
    $types = '';

    // Input teks
    $allowedFields = ['full_name', 'place_birthdate', 'birthdate', 'address', 'numphone', 'father_name', 'mother_name', 'asal_school', 'ijazah_number'];

    foreach ($allowedFields as $field) {
        if (!empty($_POST[$field])) {
            $fields[] = "$field = ?";
            $params[] = $_POST[$field];
            $types .= 's';
        }
    }

    // Pas Foto
    $toastMessage = '';
    if (!empty($_FILES['pas_photo']['name'])) {
        if ($_FILES['pas_photo']['size'] > 1048576) {
            $toastMessage = 'Ukuran pas foto maksimal 1 MB';
        }
        $photoName = 'foto_' . time() . '_' . basename($_FILES['pas_photo']['name']);
        $photoPath = "uploads/$photoName";
        move_uploaded_file($_FILES['pas_photo']['tmp_name'], "../../$photoPath");
        $fields[] = "pas_photo = ?";
        $params[] = $photoName;
        $types .= 's';
    }

    // Ijazah
    if (!empty($_FILES['ijasah_document']['name'])) {
        if ($_FILES['ijasah_document']['size'] > 1048576) {
            $toastMessage = 'Ukuran dokumen ijazah tidak boleh lebih dari 1MB';
        }
        $docName = 'ijasah_' . time() . '_' . basename($_FILES['ijasah_document']['name']);
        $docPath = "uploads/$docName";
        move_uploaded_file($_FILES['ijasah_document']['tmp_name'], "../../$docPath");
        $fields[] = "ijasah_document = ?";
        $params[] = $docName;
        $types .= 's';
    }

    // Dokumen Tambahan
    if (!empty($_FILES['doc_tambahan']['name'])) {
        if ($_FILES['doc_tambahan']['size'] > 1048576) {
            $toastMessage = 'Ukuran dokumen tambahan tidak boleh lebih dari 1MB.';
        }
        $extraName = 'tambahan_' . time() . '_' . basename($_FILES['doc_tambahan']['name']);
        $extraPath = "uploads/$extraName";
        move_uploaded_file($_FILES['doc_tambahan']['tmp_name'], "../../$extraPath");
        $fields[] = "doc_tambahan = ?";
        $params[] = $extraName;
        $types .= 's';
    }

    if (!empty($toastMessage)) {
    // Stop eksekusi update, karena validasi gagal
    } else if (!empty($fields)) {
      $params[] = $userId;
      $types .= 'i';

      $sql = "UPDATE pendaftaran_siswa SET " . implode(", ", $fields) . " WHERE casis_id_registration = ?";
      $stmt = $koneksi->prepare($sql);
      $stmt->bind_param($types, ...$params);
      $stmt->execute();

      header("Location: ../siswa/index.php?update=success");
      exit;
    }

}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data - <?= ucfirst($role) ?></title>
    <link rel="stylesheet" href="../css/toast.css">
    <link rel="stylesheet" href="../css/dashboard.css"/> 
    <link rel="stylesheet" href="../css/siswa.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
      .title-maks {
        font-size: 13px;
      }
    </style>
</head>
<body>

<div class="dashboard-container">
  <?php include '../partials/sidebar.php'; ?>

  <main class="content">
    <div class="form-container">
      <h2>Edit Data</h2>

      <form action="" method="post" enctype="multipart/form-data">
        <div class="form-row">
          <div class="form-group">
            <label for="full_name">Nama Lengkap</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($data['full_name']) ?>">
          </div>
          <div class="form-group">
            <label for="place_birthdate">Tempat Lahir</label>
            <input type="text" name="place_birthdate" value="<?= htmlspecialchars($data['place_birthdate']) ?>">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="birthdate">Tanggal Lahir</label>
            <input type="date" name="birthdate" value="<?= htmlspecialchars($data['birthdate']) ?>">
          </div>
          <div class="form-group">
            <label for="address">Alamat</label>
            <input type="text" name="address" value="<?= htmlspecialchars($data['address']) ?>">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="numphone">No. HP</label>
            <input type="text" name="numphone" value="<?= htmlspecialchars($data['numphone']) ?>">
          </div>
          <div class="form-group">
            <label for="father_name">Nama Ayah</label>
            <input type="text" name="father_name" value="<?= htmlspecialchars($data['father_name']) ?>">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="mother_name">Nama Ibu</label>
            <input type="text" name="mother_name" value="<?= htmlspecialchars($data['mother_name']) ?>">
          </div>
          <div class="form-group">
            <label for="asal_school">Asal Sekolah</label>
            <input type="text" name="asal_school" value="<?= htmlspecialchars($data['asal_school']) ?>">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="ijazah_number">No. Ijazah</label>
            <input type="text" name="ijazah_number" value="<?= htmlspecialchars($data['ijazah_number']) ?>">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="pas_photo">Pas Foto</label>
            <span class="title-maks">Pas Foto Maksimal 1MB</span>
            <input type="file" name="pas_photo" accept="image/*">
            <?php if ($data['pas_photo']): ?>
              <img src="../../uploads/<?= htmlspecialchars($data['pas_photo']) ?>" class="img-preview">
            <?php endif; ?>
          </div>
          <div class="form-group">
            <label for="ijasah_document">Ijazah</label>
            <span class="title-maks">File Ijazah Maksimal 1MB (doc, PDF, word)</span>
            <input type="file" name="ijasah_document" accept="application/pdf">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="doc_tambahan">Dokumen Tambahan</label>
            <span class="title-maks">Dokumen Tambahan Maksimal 1MB (doc, PDF, word)</span>
            <input type="file" name="doc_tambahan" accept="application/pdf">
          </div>
        </div>

        <div class="form-submit">
          <button type="submit">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </main>
</div>

<!-- TOAST CONTAINER -->
<div id="toast-container"></div>

<!-- TOAST JS -->
<script src="../js/toast.js"></script>
<?php if (!empty($toastMessage)): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    showToast("<?= addslashes($toastMessage) ?>");
  });
</script>
<?php endif; ?>

</body>
</html>
