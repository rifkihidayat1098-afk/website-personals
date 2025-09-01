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
    $searchSql = "AND (
        full_name LIKE '%$searchEscaped%' OR 
        code_registration LIKE '%$searchEscaped%' OR 
        asal_school LIKE '%$searchEscaped%' OR 
        address LIKE '%$searchEscaped%'
    )";
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Query utama berdasarkan role
$whereClause = "WHERE 1=1 $searchSql";

if (!isAdmin() && !isKepalaSekolah()) {
    $whereClause .= " AND user_id = '$userId'";
}

// Hitung total data
$totalQuery = "SELECT COUNT(*) as total FROM pendaftaran_siswa $whereClause";
$totalResult = mysqli_query($koneksi, $totalQuery);
$totalData = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalData / $limit);

// Ambil data pendaftaran
$query = "SELECT * FROM pendaftaran_siswa $whereClause LIMIT $limit OFFSET $offset";
$result = mysqli_query($koneksi, $query);
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Data Pendaftaran Siswa</title>
    <link rel="stylesheet" href="../css/dashboard.css"/>
    <link rel="stylesheet" href="../css/modal.css"/>
    <link rel="stylesheet" href="../css/pagination.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
   <style>
    .modal .form-row {
      display: flex !important;
      justify-content: space-between !important;
      gap: 20px !important;
    }

    .modal .form-group.half {
      flex: 1 !important;
    }

    input[type="date"] {
      padding: 0.7rem;
      border-radius: 5px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }

    .status-pending {
        border: 2px solid #ffc107;
        border-radius: 25px;
        color: #ffc107;
        font-weight: bold;
        padding: 4px 12px;
        display: inline-block;
        width: fit-content;
    }

    .status-diterima {
        border: 2px solid #28a745;
        border-radius: 25px;
        color: #28a745;
        font-weight: bold;
        padding: 4px 12px;
        display: inline-block;
        width: fit-content;
    }

    .status-ditolak {
        border: 2px solid #dc3545;
        border-radius: 25px;
        color: #dc3545;
        font-weight: bold;
        padding: 4px 12px;
        display: inline-block;
        width: fit-content;
    }

    .status-unknown {
        border: 2px solid #6c757d;
        border-radius: 25px;
        font-weight: bold;
        padding: 4px 12px;
        display: inline-block;
        width: fit-content;
        color: #6c757d;
        font-style: italic;
    }

    .btn-detail {
      background-color: rgb(255, 157, 19);
      text-decoration: none;
      font-weight: 500;
      padding: 10px 10px;
      border-radius: 10px;
      text-align: center;
    }

    .btn-detail svg {
      color: #fff !important;
      vertical-align: middle;
      transition: stroke 0.2s ease;
      width: 20px;
      height: 20px;
    }

    .btn-detail:hover svg {
      stroke: #dedede;
    }

</style>

<div class="dashboard-container">
    <?php include '../partials/sidebar.php'; ?>

    <main class="content">
        <?php include '../partials/navbar.php'; ?>
        <h1>Data Pendaftaran Siswa</h1>

        <?php include '../partials/search.php' ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>No Registrasi</th>
                        <th>Asal Sekolah</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <?php if (isAdmin() || isKepalaSekolah()) echo '<th>Action</th>'; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['full_name']) ?></td>
                                <td><?= htmlspecialchars($row['code_registration']) ?></td>
                                <td><?= htmlspecialchars($row['asal_school']) ?></td>
                                <td><?= htmlspecialchars($row['address']) ?></td>
                                <td>
                                  <?php
                                      $status = $row['status'];
                                      $class = '';
                                      $text = '';

                                      if ($status === 'pending') {
                                          $class = 'status-pending';
                                          $text = 'Pending';
                                      } elseif ($status === 'diterima') {
                                          $class = 'status-diterima';
                                          $text = 'Diterima';
                                      } elseif ($status === 'ditolak') {
                                          $class = 'status-ditolak';
                                          $text = 'Ditolak';
                                      } else {
                                          $class = 'status-unknown';
                                          $text = 'Tidak Diketahui';
                                      }
                                  ?>
                                  <span class="<?= $class ?>"><?= $text ?></span>
                                </td>

                                <td class="action">
                                  <!-- Tombol Detail data pendaftaran untuk Admin dan Kepala Sekolah -->
                                  <?php if (isAdmin() || isKepalaSekolah()): ?>
                                      <a class="btn-detail" href="detail.php?id=<?= $row['id_registration'] ?>" title="Detail">
                                          <!-- Icon detail -->
                                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                      </a>
                                  <?php endif; ?>
                                  <?php if (isAdmin()): ?>
                                        <!-- Tombol Edit -->
                                        <a class="btn-edit" href="#"
                                          data-id="<?= $row['id_registration'] ?>"
                                          data-code_registration="<?= htmlspecialchars($row['code_registration']) ?>"
                                          data-full_name="<?= htmlspecialchars($row['full_name']) ?>"
                                          data-place_birthdate="<?= htmlspecialchars($row['place_birthdate']) ?>"
                                          data-birthdate="<?= htmlspecialchars($row['birthdate']) ?>"
                                          data-address="<?= htmlspecialchars($row['address']) ?>"
                                          data-numphone="<?= htmlspecialchars($row['numphone']) ?>"
                                          data-father_name="<?= htmlspecialchars($row['father_name']) ?>"
                                          data-mother_name="<?= htmlspecialchars($row['mother_name']) ?>"
                                          data-asal_school="<?= htmlspecialchars($row['asal_school']) ?>"
                                          data-ijazah_number="<?= htmlspecialchars($row['ijazah_number']) ?>"
                                          data-status="<?= htmlspecialchars($row['status']) ?>"
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
                                        <a class="btn-delete" href="../../actions/pendaftaran/delete.php?id=<?= $row['id_registration'] ?>" onclick="return confirm('Yakin hapus?')" title="Hapus">
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
                                    
                                  <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= isAdmin() ? '7' : '6' ?>" style="text-align: center; color: #777;">
                                Tidak ada data pendaftaran.
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


<!-- Modal Edit -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="btn-close" onclick="closeModal()">&times;</span>
    <h2>Edit Pendaftaran Siswa</h2>
    <form action="../../actions/pendaftaran/update.php" method="POST" id="editForm">
      
      <input type="hidden" name="id_registration" value="<?= $data['id_registration'] ?>" id="edit-id">

      <div class="form-row">
        <div class="form-group half">
          <label for="edit-code-registration">Kode Pendaftaran:</label>
          <input type="text" name="code_registration" id="edit-code-registration" readonly>
        </div>
        <div class="form-group half">
          <label for="edit-full-name">Nama Lengkap:</label>
          <input type="text" name="full_name" id="edit-full-name" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group half">
          <label for="edit-place-birthdate">Tempat Lahir:</label>
          <input type="text" name="place_birthdate" id="edit-place-birthdate" required>
        </div>
        <div class="form-group half">
          <label for="edit-birthdate">Tanggal Lahir:</label>
          <input type="date" name="birthdate" id="edit-birthdate" required>
        </div>
      </div>

      <div class="form-group">
        <label for="edit-address">Alamat:</label>
        <textarea name="address" id="edit-address" required></textarea>
      </div>

      <div class="form-row">
        <div class="form-group half">
          <label for="edit-numphone">No. HP:</label>
          <input type="text" name="numphone" id="edit-numphone" required>
        </div>
        <div class="form-group half">
          <label for="edit-father-name">Nama Ayah:</label>
          <input type="text" name="father_name" id="edit-father-name" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group half">
          <label for="edit-mother-name">Nama Ibu:</label>
          <input type="text" name="mother_name" id="edit-mother-name" required>
        </div>
        <div class="form-group half">
          <label for="edit-asal-school">Asal Sekolah:</label>
          <input type="text" name="asal_school" id="edit-asal-school" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group half">
            <label for="edit-ijazah-number">Nomor Ijazah:</label>
            <input type="text" name="ijazah_number" id="edit-ijazah-number" required>
        </div>
        <div class="form-group half">
          <label>Status:</label>
          <select name="status" id="edit-status">
            <option value="pending">Pending</option>
            <option value="diterima">Diterima</option>
            <option value="ditolak">Ditolak</option>
          </select>
        </div>
      </div>

      <input type="submit" value="Update">
    </form>
  </div>
</div>

<script>

function openEditModal(el) {
    document.getElementById('edit-id').value = el.dataset.id;
    document.getElementById('edit-code-registration').value = el.dataset.code_registration;
    document.getElementById('edit-full-name').value = el.dataset.full_name;
    document.getElementById('edit-place-birthdate').value = el.dataset.place_birthdate;
    document.getElementById('edit-birthdate').value = el.dataset.birthdate;
    document.getElementById('edit-address').value = el.dataset.address;
    document.getElementById('edit-numphone').value = el.dataset.numphone;
    document.getElementById('edit-father-name').value = el.dataset.father_name;
    document.getElementById('edit-mother-name').value = el.dataset.mother_name;
    document.getElementById('edit-asal-school').value = el.dataset.asal_school;
    document.getElementById('edit-ijazah-number').value = el.dataset.ijazah_number;

    // Menyaring dan memilih option berdasarkan nilai currentStatus
    const statusSelect = document.getElementById('edit-status');
    const currentStatus = el.dataset.status;

    console.log("Current Status from Button:", currentStatus); // Debugging untuk memastikan nilai status

    // Mengatur nilai select option sesuai dengan data-status
    for (let i = 0; i < statusSelect.options.length; i++) {
        if (statusSelect.options[i].value === currentStatus) {
            statusSelect.selectedIndex = i;  // Pilih option yang sesuai
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
