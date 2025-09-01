<?php
include '../config/koneksi.php';
include '../actions/auth_check.php';
include '../config.php';

$role = $_SESSION['user']['role'];
$userId = $_SESSION['user']['id'];

// Pencarian
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$searchSql = '';
if ($search !== '') {
    $searchEscaped = mysqli_real_escape_string($koneksi, $search);
    $searchSql = "AND (username LIKE '%$searchEscaped%' OR email LIKE '%$searchEscaped%')";
}

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Query sesuai role
if (isAdmin() || isKepalaSekolah()) {
    $totalResult = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE 1=1 $searchSql");
    $query = "SELECT * FROM users WHERE 1=1 $searchSql LIMIT $limit OFFSET $offset";
} else {
    $totalResult = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE id = '$userId'");
    $query = "SELECT * FROM users WHERE id = '$userId' LIMIT $limit OFFSET $offset";
}

$totalData = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalData / $limit);

$result = mysqli_query($koneksi, $query);

// Format role
function formatRole($role) {
    return ucwords(str_replace('_', ' ', $role));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Data Users</title>
    <link rel="stylesheet" href="css/dashboard.css"/>
    <link rel="stylesheet" href="css/modal.css"/>
    <link rel="stylesheet" href="css/pagination.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
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
    <?php include 'partials/sidebar.php'; ?>

    <main class="content">
        <?php include 'partials/navbar.php'; ?>
        <div class='heading-container'>
            <h1>Data Users</h1>
            <a class="btn-tambah">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>    
            Tambah User</a>
        </div>

        <?php include 'partials/search.php' ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <?php if (isAdmin()) echo '<th>Action</th>'; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = $offset + 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars(formatRole($row['role'])) ?></td>

                                <?php if (isAdmin()): ?>
                                    <td class="action">
                                       <!-- Tombol daftar -->
                                        <?php if ($row['role'] === 'siswa'): ?>
                                            <?php
                                                $idSiswa = $row['id'];
                                                $cekQuery = "SELECT 1 FROM pendaftaran_siswa WHERE casis_id_registration = '$idSiswa' LIMIT 1";
                                                $cekResult = mysqli_query($koneksi, $cekQuery);
                                                $sudahDaftar = mysqli_num_rows($cekResult) > 0;
                                            ?>
                                            <?php if (!$sudahDaftar): ?>
                                                <a class="btn-daftar" href="pendaftaran/add.php?id=<?= $idSiswa ?>" title="Daftar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-plus-icon lucide-circle-plus"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        
                                        <!-- Tombol Edit -->
                                        <a class="btn-edit" href="#"
                                          data-id="<?= $row['id'] ?>"
                                          data-username="<?= htmlspecialchars($row['username']) ?>"
                                          data-email="<?= htmlspecialchars($row['email']) ?>"
                                          data-password="<?= htmlspecialchars($row['password']) ?>"
                                          data-role="<?= htmlspecialchars($row['role']) ?>"
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
                                        <a class="btn-delete" href="../actions/users/delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus?')" title="Hapus">
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
                                Tidak ada data users.
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


<!-- Modal Tambah -->
<div id="addModal" class="modal">
  <div class="modal-content">
    <span class="btn-close" onclick="closeAddModal()">&times;</span>
    <h2>Tambah User Baru</h2>
    <form action="../actions/users/create.php" method="POST" id="addForm">

      <div class="form-row">
        <div class="form-group half">
          <label for="add-username">Username:</label>
          <input type="text" name="username" id="add-username" required>
        </div>
        <div class="form-group half">
          <label for="add-email">Email:</label>
          <input type="email" name="email" id="add-email" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group half">
          <label for="add-password">Password:</label>
          <input type="password" name="password" id="add-password" required>
        </div>
        <div class="form-group half">
          <label for="add-role">Role:</label>
          <select name="role" id="add-role" required>
            <option value="siswa">Siswa</option>
            <option value="admin">Admin</option>
            <option value="kepala_sekolah">Kepala Sekolah</option>
          </select>
        </div>
      </div>

      <input type="submit" value="Simpan">
    </form>
  </div>
</div>


<!-- Modal Edit -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="btn-close" onclick="closeModal()">&times;</span>
    <h2>Edit Data User</h2>
    <form action="../actions/users/update.php" method="POST" id="editForm">
      
      <input type="hidden" name="id" id="edit-id">

      <div class="form-row">
        <div class="form-group half">
          <label for="edit-username">Username:</label>
          <input type="text" name="username" id="edit-username" required>
        </div>
        <div class="form-group half">
          <label for="edit-email">Email:</label>
          <input type="email" name="email" id="edit-email" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group half">
          <label for="edit-password">Password (Kosongkan jika tidak diubah):</label>
          <input type="password" name="password" id="edit-password">
        </div>
        <div class="form-group half">
          <label for="edit-role">Role:</label>
          <select name="role" id="edit-role" required>
            <option value="siswa">Siswa</option>
            <option value="admin">Admin</option>
            <option value="kepala_sekolah">Kepala Sekolah</option>
          </select>
        </div>
      </div>

      <input type="submit" value="Update">
    </form>
  </div>
</div>


<script>

    // ADD MODAL FUNC
    document.querySelector('.btn-tambah').addEventListener('click', function (e) {
      e.preventDefault();
      document.getElementById('addModal').style.display = 'flex';
    });


    function closeAddModal() {
      document.getElementById('addModal').style.display = 'none';
    }

    // EDIT MODAL FUNC
    function openEditModal(el) {
        document.getElementById('edit-id').value = el.dataset.id;
        document.getElementById('edit-username').value = el.dataset.username;
        document.getElementById('edit-email').value = el.dataset.email;
        document.getElementById('edit-password').value = '';

        const roleSelect = document.getElementById('edit-role');
        const currentRole = el.dataset.role;
        for (let i = 0; i < roleSelect.options.length; i++) {
            if (roleSelect.options[i].value === currentRole) {
                roleSelect.selectedIndex = i;
                break;
            }
        }

        document.getElementById('editModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>

</body>
</html>
