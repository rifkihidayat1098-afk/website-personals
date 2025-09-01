<?php

include '../../actions/auth_check.php';

if (!isAdmin()) {
    header('Location: /login.php');
    exit;
}

include '../../config/koneksi.php';

// Tambah Blog
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add') {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $konten = mysqli_real_escape_string($koneksi, $_POST['konten']);

    mysqli_query($koneksi, "INSERT INTO blog (judul, konten) VALUES ('$judul', '$konten')");
    header('Location: index.php');
    exit;
}

// Edit Blog
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'edit') {
    $id = (int) $_POST['id'];
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $konten = mysqli_real_escape_string($koneksi, $_POST['konten']);

    mysqli_query($koneksi, "UPDATE blog SET judul='$judul', konten='$konten' WHERE id=$id");
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Blog</title>
    <link rel="stylesheet" href="../css/dashboard.css"/>
    <link rel="stylesheet" href="../css/modal.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="dashboard-container">
    <?php include '../partials/sidebar.php'; ?>

    <main class="content">
    
    <div class="heading">
        <h1>Manajemen Blog</h1>
        <button class="btn-tambah" onclick="openModal('modalTambah')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-plus-icon lucide-circle-plus"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>    
            Add Blog
       </button>
    </div>

    <table>
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Tanggal</th>
            <th>Action</th>
        </tr>
        <?php
        $result = mysqli_query($koneksi, 'SELECT * FROM blog ORDER BY tanggal DESC');
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
          echo '
                <tr>
                    <td>' . $no . '</td>
                    <td>' . htmlspecialchars($row['judul']) . '</td>
                    <td>' . $row['tanggal'] . '</td>
                    <td class="action">
                    <button 
                        class="btn-edit openEditModal" 
                        title="Edit"
                        data-id="' . $row['id'] . '"
                        data-judul="' . htmlspecialchars($row['judul']) . '"
                        data-konten="' . htmlspecialchars($row['konten']) . '"
                    >
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 20h9"/>
                                <path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
                            </svg>
                    </button>
                        
                        <a href="hapus.php?id=' . $row['id'] . '" class="btn-delete" onclick="return confirm(\'Hapus data?\')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        </a>
                    </td>
                </tr>';
                $no++;
            }
        ?>
    </table>

    </main>
    </div>

    <!-- Modal Tambah Blog -->
    <div id="modalTambah" class="modal">
    <div class="modal-content">
        <span class="btn-close" onclick="closeModal('modalTambah')">&times;</span>
        <h2>Tambah Artikel</h2>
        <form method="post">
            <input type="hidden" name="action" value="add" />
            <div class="form-group">
            <label>Judul</label>
            <input type="text" name="judul" required />
        </div>
        <div class="form-group">
            <label>Konten</label>
            <textarea name="konten" rows="5" required></textarea>
        </div>
        <input type="submit" value="Simpan" />
        </form>
    </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="modal">
    <div class="modal-content">
        <span class="btn-close" onclick="closeModal('editModal')">&times;</span>
        <h2>Edit Artikel</h2>
        <form method="post" id="editForm">
            <input type="hidden" name="action" value="edit" />
            <input type="hidden" name="id" id="editId" />
        <div class="form-group">
            <label for="editJudul">Judul</label>
            <input type="text" name="judul" id="editJudul" required />
        </div>
        <div class="form-group">
            <label for="editKonten">Konten</label>
            <textarea name="konten" id="editKonten" rows="5" required></textarea>
        </div>
        <input type="submit" value="Update" />
        </form>
    </div>
    </div>


<script>
function openModal(id) {
  document.getElementById(id).classList.add('show');
}

function closeModal(id) {
  document.getElementById(id).classList.remove('show');
}

document.querySelectorAll('.openEditModal').forEach(button => {
  button.addEventListener('click', function () {
    const id = this.dataset.id;
    const judul = this.dataset.judul;
    const konten = this.dataset.konten;

    document.getElementById('editId').value = id;
    document.getElementById('editJudul').value = judul;
    document.getElementById('editKonten').value = konten;

    openModal('editModal');
  });
});

// Close modal if click outside
window.onclick = function (event) {
  const modals = ['modalTambah', 'editModal'];
  modals.forEach(id => {
    const modal = document.getElementById(id);
    if (event.target === modal) {
      closeModal(id);
    }
  });
};
</script>


</body>
</html>
