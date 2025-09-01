<?php
include '../../config/koneksi.php';
include '../../actions/auth_check.php';
include '../../config.php';

$role = $_SESSION['user']['role'];
$userId = $_SESSION['user']['id'];

// Pencarian
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$searchSql = '';
if ($search !== '') {
    $searchEscaped = mysqli_real_escape_string($koneksi, $search);
    $searchSql = "WHERE (
        name_event LIKE '%$searchEscaped%' OR 
        date_event LIKE '%$searchEscaped%' OR 
        detail_event LIKE '%$searchEscaped%'
    )";
}

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Hitung total data event
$totalResult = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM event $searchSql");
$totalData = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalData / $limit);

// Ambil data event
$query = "SELECT * FROM event $searchSql ORDER BY date_event DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Kegiatan</title>
    <link rel="stylesheet" href="../css/dashboard.css"/>
    <link rel="stylesheet" href="../css/modal.css"/>
    <link rel="stylesheet" href="../css/pagination.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        input[type="file"],
        input[type="date"] {
            padding: 0.7rem;
            margin-top: 0.5rem;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        .btn-daftar {
          background-color: rgb(255, 157, 19);
          text-decoration: none;
          font-weight: 500;
          padding: 10px 10px;
          border-radius: 10px;
          text-align: center;
        }

        .btn-daftar svg {
          color: #fff !important;
          vertical-align: middle;
          transition: stroke 0.2s ease;
          width: 20px;
          height: 20px;
        }

        .btn-daftar:hover svg {
          stroke: #dedede;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <?php include '../partials/sidebar.php'; ?>

    <main class="content">
        <?php include '../partials/navbar.php'; ?>
        <div class='heading-container'>
            <h1>Data Kegiatan</h1>
            <a class="btn-tambah">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Tambah Kegiatan</a>
        </div>

        <?php include '../partials/search.php' ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Detail Kegiatan</th>
                        <th>Gambar</th>
                        <?php if (isAdmin()) echo '<th>Action</th>'; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = $offset + 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['name_event']) ?></td>
                                <td><?= htmlspecialchars($row['date_event']) ?></td>
                                <td><?= htmlspecialchars(mb_strimwidth($row['detail_event'], 0, 50, '...')) ?></td>
                                <td>
                                    <?php if (!empty($row['img_event'])): ?>
                                        <img src="../../uploads/event/<?= htmlspecialchars($row['img_event']) ?>" alt="Gambar Kegiatan" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                    <?php else: ?>
                                        Tidak ada gambar
                                    <?php endif; ?>
                                </td>

                                <?php if (isAdmin()): ?>
                                    <td class="action">
                                        <!-- Tombol Edit -->
                                        <a class="btn-edit" href="#"
                                          data-id="<?= $row['id_event'] ?>"
                                          data-name="<?= htmlspecialchars($row['name_event']) ?>"
                                          data-detail="<?= htmlspecialchars($row['detail_event']) ?>"
                                          data-date="<?= htmlspecialchars($row['date_event']) ?>"
                                          data-img="<?= htmlspecialchars($row['img_event']) ?>"
                                          onclick="openEditModal(this)"
                                          title="Edit">
                                            <!-- Icon edit -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 20h9"/>
                                                <path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
                                            </svg>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <a class="btn-delete" href="../../actions/event/delete.php?id=<?= $row['id_event'] ?>" onclick="return confirm('Yakin hapus kegiatan ini?')" title="Hapus">
                                            <!-- Icon hapus -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"/>
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                                <line x1="10" y1="11" x2="10" y2="17"/>
                                                <line x1="14" y1="11" x2="14" y2="17"/>
                                            </svg>
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= isAdmin() ? '6' : '5' ?>" style="text-align: center; color: #777;">
                                Tidak ada data kegiatan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="pagination-container">
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <!-- Tombol Previous -->
                        <a href="?page=<?= $page - 1 ?>" class="<?= $page <= 1 ? 'disabled' : '' ?>">&laquo;</a>

                        <?php
                        if ($page > 3) {
                            echo '<a href="?page=1">1</a>';
                            if ($page > 4) echo '<span class="dots">...</span>';
                        }

                        for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++) {
                            $active = $i == $page ? 'active' : '';
                            echo "<a href=\"?page=$i\" class=\"$active\">$i</a>";
                        }

                        if ($page < $totalPages - 2) {
                            if ($page < $totalPages - 3) echo '<span class="dots">...</span>';
                            echo '<a href="?page=' . $totalPages . '">' . $totalPages . '</a>';
                        }
                        ?>

                        <!-- Tombol Next -->
                        <a href="?page=<?= $page + 1 ?>" class="<?= $page >= $totalPages ? 'disabled' : '' ?>">&raquo;</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </main>
    
</div>


<!-- Modal Tambah Kegiatan -->
<div id="addModal" class="modal">
  <div class="modal-content">
    <span class="btn-close" onclick="closeAddModal()">&times;</span>
    <h2>Tambah Kegiatan Baru</h2>
    <form action="../../actions/event/create.php" method="POST" id="addForm" enctype="multipart/form-data">

      <div class="form-group">
        <label for="add-nama_kegiatan">Nama Kegiatan:</label>
        <input type="text" name="judul" id="add-nama_kegiatan" required>
      </div>

      <div class="form-group">
        <label for="add-detail_kegiatan">Detail Kegiatan:</label>
        <textarea name="konten" id="add-detail_kegiatan" rows="5" required></textarea>
      </div>

      <div class="form-row">
        <div class="form-group half">
          <label for="add-tanggal">Tanggal:</label>
          <input type="date" name="tanggal" id="add-tanggal" required>
        </div>
      </div>

      <div class="form-group">
        <label for="add-gambar">Gambar Kegiatan:</label>
        <input type="file" name="gambar" id="add-gambar" accept="image/*" required>
      </div>

      <input type="submit" value="Simpan">
    </form>
  </div>
</div>

<!-- Modal Edit Kegiatan -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="btn-close" onclick="closeModal()">&times;</span>
    <h2>Edit Kegiatan</h2>
    <form action="../../actions/event/update.php" method="POST" id="editForm" enctype="multipart/form-data">

      <input type="hidden" name="id" id="edit-id">

      <div class="form-group">
        <label for="edit-nama_kegiatan">Nama Kegiatan:</label>
        <input type="text" name="name_event" id="edit-nama_kegiatan" required>
      </div>

      <div class="form-group">
        <label for="edit-detail_kegiatan">Detail Kegiatan:</label>
        <textarea name="detail_event" id="edit-detail_kegiatan" rows="5" required></textarea>
      </div>

      <div class="form-row">
        <div class="form-group half">
          <label for="edit-tanggal">Tanggal:</label>
          <input type="date" name="date_event" id="edit-tanggal" required>
        </div>
      </div>

      <div class="form-group">
        <label for="edit-gambar">Gambar Kegiatan (biarkan kosong jika tidak diubah):</label>
        <input type="file" name="img_event" id="edit-gambar" accept="image/*">
        <img id="edit-preview-gambar" src="" alt="Preview Gambar" style="max-width: 100px; display: none;">
      </div>

      <input type="submit" value="Update">
    </form>
  </div>
</div>


<script>
  // Buka modal tambah kegiatan
  document.querySelector('.btn-tambah').addEventListener('click', function (e) {
    e.preventDefault();
    document.getElementById('addModal').style.display = 'flex';
  });

  function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
  }

  // Buka modal edit kegiatan
  function openEditModal(el) {
    document.getElementById('edit-id').value = el.dataset.id;
    document.getElementById('edit-nama_kegiatan').value = el.dataset.name;
    document.getElementById('edit-detail_kegiatan').value = el.dataset.detail;
    document.getElementById('edit-tanggal').value = el.dataset.date;
    document.getElementById('editModal').style.display = 'flex';
  }

  function closeModal() {
    document.getElementById('editModal').style.display = 'none';
  }
</script>



</body>
</html>