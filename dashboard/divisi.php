<?php
include '../config/koneksi.php';
include '../actions/auth_check.php';
include '../config.php';

$role = $_SESSION['user']['role'];

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Hitung total data
$searchSql = "";
$totalResult = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM divisi $searchSql");
$totalData = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalData / $limit);

// Ambil data divisi
$query = "SELECT * FROM divisi $searchSql LIMIT $limit OFFSET $offset";
$result = mysqli_query($koneksi, $query);

// AMBIL DATA PERTANYAAN
$queryPertanyaan = "
  SELECT p.*, d.nama_divisi 
  FROM pertanyaan_divisi p
  LEFT JOIN divisi d ON p.id_divisi = d.id_divisi
  ORDER BY p.id_pertanyaan DESC
";
$resultPertanyaan = mysqli_query($koneksi, $queryPertanyaan);

// Ambil semua opsi pertanyaan sekaligus
$opsiQuery = "SELECT id_pertanyaan, opsi_text, nilai_opsional FROM opsi_pertanyaan";
$opsiResult = mysqli_query($koneksi, $opsiQuery);

// Format ke dalam array asosiatif
$opsiMap = [];
while ($opsi = mysqli_fetch_assoc($opsiResult)) {
    $id = $opsi['id_pertanyaan'];
    $opsiMap[$id][] = $opsi;
}


?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Data Divisi</title>
    <link rel="stylesheet" href="css/dashboard.css"/>
    <!-- <link rel="stylesheet" href="css/modal.css"/> -->
    <link rel="stylesheet" href="css/pagination.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
      .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
      }

      .modal.show {
        display: flex;
      }

      .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        position: relative;
        box-shadow: 0 0 20px rgba(0,0,0,0.3);
      }

      @keyframes slideDown {
        from {
          transform: translateY(-20px);
          opacity: 0;
        }
        to {
          transform: translateY(0);
          opacity: 1;
        }
      }

      .modal-content h2 {
        margin-top: 0;
      }

      .form-group {
        margin-bottom: 1rem;
      }

      .form-group label {
        display: block;
        font-weight: 500;
        margin-bottom: 0.5rem;
      }

      .form-group input[type="password"],
      .form-group input[type="text"],
      .form-group textarea {
        padding: 0.75rem;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 1rem;
      }


      .btn-close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 20px;
        cursor: pointer;
        color: #888;
      }

      .btn-close:hover {
        color: #000;
      }

      .btn-simpan {
        background-color: #435ebe;
        text-decoration: none;
        font-weight: 500;
        padding: 10px 12px;
        border-radius: 10px;
        color: white;
        display: inline-flex;
        align-items: center;
        gap: 6px;
      }

      .btn-tambah {
        background-color: #435ebe;
        text-decoration: none;
        font-weight: 500;
        padding: 10px 12px;
        border-radius: 10px;
        color: white;
        display: inline-flex;
        align-items: center;
        gap: 6px;
      }

      .btn-tambah svg {
        stroke: white;
      }

      .btn-question {
        background-color: rgb(255, 157, 19);
        text-decoration: none;
        font-weight: 500;
        padding: 10px 12px;
        border-radius: 10px;
        color: white;
        display: inline-flex;
        align-items: center;
        gap: 6px;
      }

      .form-group select {
          width: 100%;
          padding: 0.75rem;
          border: 1px solid #ccc;
          border-radius: 8px;
          font-size: 1rem;
          background-color: #fff;
          appearance: none;
          -webkit-appearance: none;
          -moz-appearance: none;
          background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='gray' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 5.646a.5.5 0 0 1 .708 0L8 11.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
          background-repeat: no-repeat;
          background-position: right 0.75rem center;
          background-size: 1rem;
          cursor: pointer;
      }

      .form-group select:focus {
          border-color: #435ebe;
          outline: none;
          box-shadow: 0 0 0 2px rgba(67, 94, 190, 0.2);
      }

      .btn-tambah-opsi {
        margin-top: 10px;
        padding: 6px 12px;
        background-color: #435EBE;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
      }
      

      .btn-tambah-opsi:hover {
        background-color: #3b4ec7;
      }

      .btn-hapus-opsi {
        margin-left: 10px;
        padding: 4px 10px;
        background-color: #E74C3C;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9em;
      }

      .btn-hapus-opsi:hover {
        background-color: #c0392b;
      }

      .opsi-item {
        display: flex;
        gap: 10px;
        margin-bottom: 8px;
      }

      .opsi-item input[type="text"] {
        flex: 1;
      }


    </style>
</head>
<body>
<div class="dashboard-container">
    <?php include 'partials/sidebar.php'; ?>

    <main class="content">
        <?php include 'partials/navbar.php'; ?>
        <div class='heading-container'>
            <h1>Data Divisi</h1>
            <a class="btn-tambah" onclick="openAddModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>    
                Tambah Divisi
            </a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Divisi</th>
                        <?php if (isAdmin()) echo '<th>Action</th>'; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php $no = $offset + 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_divisi']) ?></td>
                                <?php if (isAdmin()): ?>
                                <td class="action">
                                    <!-- Tombol Edit -->
                                    <a class="btn-edit" href="#"
                                       data-id="<?= $row['id_divisi'] ?>"
                                       data-nama_divisi="<?= htmlspecialchars($row['nama_divisi']) ?>"
                                       onclick="openEditModal(this)">
                                        Edit
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <a class="btn-delete" href="../actions/divisi/delete.php?id=<?= $row['id_divisi'] ?>" onclick="return confirm('Yakin ingin menghapus divisi ini?')">Hapus</a>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= isAdmin() ? '3' : '2' ?>" style="text-align:center;">Tidak ada data divisi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="pagination-container">
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <a href="?page=<?= $page - 1 ?>" class="<?= $page <= 1 ? 'disabled' : '' ?>">&laquo;</a>
                        <?php
                        for ($i = 1; $i <= $totalPages; $i++) {
                            $active = $i == $page ? 'active' : '';
                            echo "<a href=\"?page=$i\" class=\"$active\">$i</a>";
                        }
                        ?>
                        <a href="?page=<?= $page + 1 ?>" class="<?= $page >= $totalPages ? 'disabled' : '' ?>">&raquo;</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

         <!-- DATA PERTANYAAN DIVISI -->
        <div class='heading-container'>
            <h1>Data Pertanyaan Divisi</h1>
            <a class="btn-tambah" onclick="openAddPertanyaanModal()">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/><path d="M12 5v14"/>
              </svg>
              Tambah Pertanyaan Divisi
            </a>
        </div>

      <div class="table-container">
          <table>
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Divisi</th>
                      <th>Judul</th>
                      <th>Isi</th>
                      <th>Tipe</th>
                      <th>Required?</th>
                      <th>Opsi</th>
                      <?php if (isAdmin()) echo '<th>Action</th>'; ?>
                  </tr>
              </thead>
              <tbody>
                  <?php if (mysqli_num_rows($resultPertanyaan) > 0): ?>
                      <?php $no = 1; ?>
                      <?php while ($row = mysqli_fetch_assoc($resultPertanyaan)): ?>
                          <tr>
                              <td><?= $no++ ?></td>
                              <td><?= htmlspecialchars($row['nama_divisi']) ?></td>
                              <td><?= htmlspecialchars($row['judul_pertanyaan']) ?></td>
                              <td><?= nl2br(htmlspecialchars($row['isi_pertanyaan'])) ?></td>
                              <td><?= $row['tipe_pertanyaan'] ?></td>
                              <td><?= $row['is_required'] ? 'Ya' : 'Tidak' ?></td>
                              <td>
                                  <?php if (!empty($opsiMap[$row['id_pertanyaan']])): ?>
                                          <?php foreach ($opsiMap[$row['id_pertanyaan']] as $o): ?>
                                              <p><?= htmlspecialchars($o['opsi_text']) ?></p>
                                          <?php endforeach; ?>
                                  <?php else: ?>
                                      -
                                  <?php endif; ?>
                              </td>
                              <?php if (isAdmin()): ?>
                              <td class="action">
                                  <a class="btn-delete" href="../actions/pertanyaan_divisi/delete.php?id=<?= $row['id_pertanyaan'] ?>" onclick="return confirm('Yakin ingin menghapus pertanyaan ini?')">Hapus</a>
                              </td>
                              <?php endif; ?>
                          </tr>
                      <?php endwhile; ?>
                  <?php else: ?>
                      <tr>
                          <td colspan="<?= isAdmin() ? '8' : '7' ?>" style="text-align:center;">Tidak ada data pertanyaan divisi.</td>
                      </tr>
                  <?php endif; ?>
              </tbody>
          </table>
      </div>
       
    </main> 
</div>

<!-- Modal Tambah Divisi -->
<div id="addModal" class="modal">
  <div class="modal-content">
    <span class="btn-close" onclick="closeAddModal()">&times;</span>
    <h2>Tambah Divisi</h2>
    <form action="../actions/divisi/create.php" method="POST">
      <div class="form-group">
        <label for="nama_divisi">Nama Divisi:</label>
        <input type="text" name="nama_divisi" id="nama_divisi" required>
      </div>
      <button type="submit" class="btn-simpan">Simpan</button>
    </form>
  </div>
</div>

<!-- Modal Edit Divisi -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="btn-close" onclick="closeEditModal()">&times;</span>
    <h2>Edit Divisi</h2>
    <form action="../actions/divisi/update.php" method="POST">
      <input type="hidden" name="id_divisi" id="edit-id">
      <div class="form-group">
        <label for="edit-nama_divisi">Nama Divisi:</label>
        <input type="text" name="nama_divisi" id="edit-nama_divisi" required>
      </div>
      <button type="submit" class="btn-simpan">Update</button>
    </form>
  </div>
</div>

<!-- Modal Tambah Pertanyaan Divisi -->
<div id="addPertanyaanModal" class="modal">
  <div class="modal-content">
    <span class="btn-close" onclick="closeAddPertanyaanModal()">&times;</span>
    <h2>Tambah Pertanyaan Divisi</h2>
    <form action="../actions/pertanyaan_divisi/create.php" method="POST">
      <div class="form-group">
        <label for="id_divisi">Divisi:</label>
        <select name="id_divisi" id="id_divisi" required>
          <?php
          // Ambil data divisi untuk dropdown
          $divisiResult = mysqli_query($koneksi, "SELECT id_divisi, nama_divisi FROM divisi");
          while ($divisi = mysqli_fetch_assoc($divisiResult)) {
              echo "<option value='{$divisi['id_divisi']}'>" . htmlspecialchars($divisi['nama_divisi']) . "</option>";
          }
          ?>
        </select>
      </div>

      <div class="form-group">
        <label for="judul_pertanyaan">Judul Pertanyaan:</label>
        <input type="text" name="judul_pertanyaan" id="judul_pertanyaan" required>
      </div>

      <div class="form-group">
        <label for="isi_pertanyaan">Isi Pertanyaan:</label>
        <textarea name="isi_pertanyaan" id="isi_pertanyaan" required></textarea>
      </div>

      <div class="form-group">
        <label for="tipe_pertanyaan">Tipe Pertanyaan:</label>
        <select name="tipe_pertanyaan" id="tipe_pertanyaan" onchange="toggleOpsiInput()" required>
          <option value="text">Text</option>
          <option value="textarea">Textarea</option>
          <option value="radio">Radio</option>
          <option value="checkbox">Checkbox</option>
          <option value="select">Select</option>
        </select>
      </div>

     <div id="opsi-container" style="display: none;">
      <label>Opsi Pertanyaan:</label>
      <div id="opsi-list">
        <div class="form-group opsi-item">
          <input type="text" name="opsi_text[]" placeholder="Teks Opsi">
          <input type="text" name="nilai_opsional[]" placeholder="Nilai Opsional (opsional)">
          <button type="button" class="btn-hapus-opsi" onclick="hapusOpsi(this)">Hapus</button>
        </div>
      </div>
      <button type="button" class="btn-tambah-opsi" onclick="tambahOpsi()">+ Tambah Opsi</button>
    </div>


      <div class="form-group">
        <label>
          <input type="checkbox" name="is_required" value="1">
          Wajib Diisi
        </label>
      </div>

      <button type="submit" class="btn-simpan">Simpan</button>
    </form>
  </div>
</div>


<script>
  function openAddModal() {
      document.getElementById('addModal').style.display = 'block';
  }

  function closeAddModal() {
      document.getElementById('addModal').style.display = 'none';
  }

  function openEditModal(button) {
      document.getElementById('edit-id').value = button.getAttribute('data-id');
      document.getElementById('edit-nama_divisi').value = button.getAttribute('data-nama_divisi');
      document.getElementById('editModal').style.display = 'block';
  }

  function closeEditModal() {
      document.getElementById('editModal').style.display = 'none';
  }

    // MODAL PERTANYAAN
  function openAddPertanyaanModal() {
    document.getElementById("addPertanyaanModal").style.display = "block";
  }

  function closeAddPertanyaanModal() {
    document.getElementById("addPertanyaanModal").style.display = "none";
  }

  function toggleOpsiInput() {
    const tipe = document.getElementById("tipe_pertanyaan").value;
    const opsiContainer = document.getElementById("opsi-container");
    const isOpsi = ["radio", "checkbox", "select"].includes(tipe);
    opsiContainer.style.display = isOpsi ? "block" : "none";
  }

   function tambahOpsi() {
    const container = document.getElementById("opsi-list");
    const item = document.createElement("div");
    item.classList.add("form-group", "opsi-item");
    item.innerHTML = `
      <input type="text" name="opsi_text[]" placeholder="Teks Opsi">
      <input type="text" name="nilai_opsional[]" placeholder="Nilai Opsional (opsional)">
      <button type="button" class="btn-hapus-opsi" onclick="hapusOpsi(this)">Hapus</button>
    `;
    container.appendChild(item);
  }

  function hapusOpsi(button) {
    const item = button.parentElement;
    item.remove();
  }

</script>

</body>
</html>
