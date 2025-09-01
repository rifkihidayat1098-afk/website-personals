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
    $searchSql = "WHERE (news_datestamp LIKE '%$searchEscaped%' OR news_content LIKE '%$searchEscaped%' OR news_title LIKE '%$searchEscaped%')";
}

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Hitung total data
$totalResult = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM news $searchSql");
$totalData = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalData / $limit);

// Ambil data berita
$query = "SELECT * FROM news $searchSql ORDER BY news_datestamp DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($koneksi, $query);
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Berita</title>
    <link rel="stylesheet" href="../css/dashboard.css"/>
    <link rel="stylesheet" href="../css/modal.css"/>
    <link rel="stylesheet" href="../css/pagination.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
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
            <h1>Data Berita</h1>
            <a class="btn-tambah">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Tambah Berita</a>
        </div>

        <?php include '../partials/search.php' ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Konten</th>
                        <th>Tanggal Berita</th>
                        <?php if (isAdmin()) echo '<th>Action</th>'; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = $offset + 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['news_title']) ?></td>
                                <td><?= htmlspecialchars($row['news_content']) ?></td>
                                <td><?= htmlspecialchars($row['news_datestamp']) ?></td>
                                
                                <?php if (isAdmin()): ?>
                                    <td class="action">
                                        
                                        <!-- Tombol Edit -->
                                        <a class="btn-edit" href="#"
                                          data-id="<?= $row['id_news'] ?>"
                                          data-title="<?= htmlspecialchars($row['news_title']) ?>"
                                          data-content="<?= htmlspecialchars($row['news_content']) ?>"
                                          data-date="<?= htmlspecialchars($row['news_datestamp']) ?>"
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
                                        <a class="btn-delete" href="../../actions/news/delete.php?id=<?= $row['id_news'] ?>" onclick="return confirm('Yakin hapus?')" title="Hapus">
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
                            <td colspan="<?= isAdmin() ? '7' : '6' ?>" style="text-align: center; color: #777;">
                                Tidak ada data Berita.
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

                        // Tampilkan 2 halaman sebelum dan sesudah current page
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


<!-- Modal Tambah Berita -->
<div id="addModal" class="modal">
  <div class="modal-content">
    <span class="btn-close" onclick="closeAddModal()">&times;</span>
    <h2>Tambah Berita Baru</h2>
    <form action="../../actions/news/create.php" method="POST" id="addForm">

      <div class="form-group">
        <label for="add-title">Judul Berita:</label>
        <input type="text" name="judul" id="add-title" required>
      </div>

      <div class="form-group">
        <label for="add-content">Konten:</label>
        <textarea name="konten" id="add-content" rows="5" required></textarea>
      </div>

      <div class="form-row">
        <div class="form-group half">
          <label for="add-date">Tanggal:</label>
          <input type="date" name="tanggal" id="add-date" required>
        </div>
      </div>

      <input type="submit" value="Simpan">
    </form>
  </div>
</div>


<!-- Modal Edit Berita -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="btn-close" onclick="closeModal()">&times;</span>
    <h2>Edit Berita</h2>
    <form action="../../actions/news/update.php" method="POST" id="editForm">

      <input type="hidden" name="id" id="edit-id">

      <div class="form-group">
        <label for="edit-title">Judul Berita:</label>
        <input type="text" name="judul" id="edit-title" required>
      </div>

      <div class="form-group">
        <label for="edit-content">Konten:</label>
        <textarea name="konten" id="edit-content" rows="5" required></textarea>
      </div>

      <div class="form-row">
        <div class="form-group half">
          <label for="edit-date">Tanggal:</label>
          <input type="date" name="tanggal" id="edit-date" required>
        </div>
      </div>

      <input type="submit" value="Update">
    </form>
  </div>
</div>


<script>
  // Fungsi buka modal tambah berita
  document.querySelector('.btn-tambah').addEventListener('click', function (e) {
    e.preventDefault();
    document.getElementById('addModal').style.display = 'flex';
  });

  function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
  }

  // Fungsi buka modal edit berita
  function openEditModal(el) {
    document.getElementById('edit-id').value = el.dataset.id;
    document.getElementById('edit-title').value = el.dataset.title;
    document.getElementById('edit-content').value = el.dataset.content;
    document.getElementById('edit-date').value = el.dataset.date;

    document.getElementById('editModal').style.display = 'flex';
  }

  function closeModal() {
    document.getElementById('editModal').style.display = 'none';
  }
</script>


</body>
</html>