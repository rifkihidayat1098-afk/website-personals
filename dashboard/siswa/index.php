<?php
include '../../config/koneksi.php';
include '../../actions/auth_check.php';
include '../../config.php';

$role = $_SESSION['user']['role'];
$userId = $_SESSION['user']['id'];

// Ambil data pendaftaran siswa berdasarkan id user login
$query = "SELECT * FROM pendaftaran_siswa WHERE casis_id_registration = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

  if ($data !== null) {
      $idRegistration = $data['id_registration'];
       
  }


// Mengambil Data Divisi
$queryDivisi = "SELECT id_divisi, nama_divisi FROM divisi ORDER BY nama_divisi ASC";
$resultDivisi = $koneksi->query($queryDivisi);

$divisiList = [];
while ($row = $resultDivisi->fetch_assoc()) {
    $divisiList[] = $row;
}

// cek apakah user sudah punya jawaban divisi
$adaJawaban = false;
if (!empty($data) && !empty($data['id_divisi'])) {
    $adaJawaban = true;
}


// Mengambil Semua Pertanyaan dan Opsi
$pertanyaanList = [];

// Query ambil semua pertanyaan beserta opsi-nya
$queryPertanyaan = "
    SELECT 
        pd.id_pertanyaan, pd.id_divisi, pd.judul_pertanyaan, pd.isi_pertanyaan, 
        pd.tipe_pertanyaan, pd.is_required,
        o.id_opsi, o.opsi_text, o.nilai_opsional, o.urutan
    FROM pertanyaan_divisi pd
    LEFT JOIN opsi_pertanyaan o ON pd.id_pertanyaan = o.id_pertanyaan
    ORDER BY pd.id_pertanyaan ASC, o.urutan ASC
";

$result = $koneksi->query($queryPertanyaan);

// Gabungkan pertanyaan dengan opsi
while ($row = $result->fetch_assoc()) {
    $idPertanyaan = $row['id_pertanyaan'];
    if (!isset($pertanyaanList[$idPertanyaan])) {
        $pertanyaanList[$idPertanyaan] = [
            'id_pertanyaan' => $row['id_pertanyaan'],
            'id_divisi' => $row['id_divisi'],
            'judul_pertanyaan' => $row['judul_pertanyaan'],
            'isi_pertanyaan' => $row['isi_pertanyaan'],
            'tipe_pertanyaan' => $row['tipe_pertanyaan'],
            'is_required' => $row['is_required'],
            'opsi' => []
        ];
    }

    // Tambahkan opsi jika ada
    if ($row['id_opsi']) {
        $pertanyaanList[$idPertanyaan]['opsi'][] = [
            'id_opsi' => $row['id_opsi'],
            'opsi_text' => $row['opsi_text'],
            'nilai_opsional' => $row['nilai_opsional'],
            'urutan' => $row['urutan']
        ];
    }
}



// Upload Sertifikat Divisi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_sertifikat'])) {
    if (isset($_FILES['sertifikat_divisi']) && $_FILES['sertifikat_divisi']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['sertifikat_divisi'];
        $fileName = basename($file['name']);
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];

        // Validasi ekstensi
        $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "<script>alert('Ekstensi file tidak diizinkan. Hanya PDF, DOC, DOCX, JPG, JPEG, dan PNG');</script>";
        } elseif ($fileSize > 5 * 1024 * 1024) {
            echo "<script>alert('Ukuran file terlalu besar. Maksimal 5MB.');</script>";
        } else {
            // Lokasi simpan
            $uploadDir = '../../uploads/sertifikat_divisi/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Nama file unik
            $newFileName = 'sertifikat_' . $userId . '_' . time() . '.' . $fileExtension;
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpName, $destination)) {
                // Simpan path ke database
                $updateQuery = "UPDATE pendaftaran_siswa SET sertifikat_divisi = ? WHERE casis_id_registration = ?";
                $stmt = $koneksi->prepare($updateQuery);
                $stmt->bind_param("si", $newFileName, $userId);

                if ($stmt->execute()) {
                    echo "<script>alert('Sertifikat berhasil diupload.'); window.location.href = window.location.href;</script>";
                    exit;
                } else {
                    echo "<script>alert('Gagal menyimpan sertifikat ke database.');</script>";
                }
            } else {
                echo "<script>alert('Gagal mengupload file.');</script>";
            }
        }
    } else {
        echo "<script>alert('Tidak ada file yang diupload atau terjadi kesalahan.');</script>";
    }
}


?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - <?= ucfirst($role) ?></title>
    <link rel="stylesheet" href="../css/dashboard.css"/> 
    <link rel="stylesheet" href="../css/siswa.css"/> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .data-wrapper {
            display: flex;
            gap: 2rem;
            align-items: flex-start;
            margin-bottom: 2rem;
            flex-wrap: wrap; /* agar mobile jadi stack */
        }

        .data-container {
            flex: 2;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .foto-wrapper {
            flex: 1;
            max-width: 200px;
            text-align: center;
        }

        .img-preview {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .data-item-title {
            font-weight: 600;
            padding-bottom: 5px;
        }

        /* Responsive Mobile: gambar di bawah konten */
        @media (max-width: 768px) {
            .data-wrapper {
                flex-direction: column;
            }

            .foto-wrapper {
                max-width: 100%;
                margin-top: 1rem;
                text-align: center;
            }

            .data-container {
                grid-template-columns: repeat(2, 1fr);
            }

            @media (max-width: 480px) {
                .data-container {
                    grid-template-columns: 1fr;
                }
            }
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

        .content-btn {
            display: flex;
            flex-direction: row;
            gap: 10px;
            align-items: center;
        }

        .btn-cetak {
            padding: 10px 20px;
            background-color: #6c757d;
            color: #fff; 
            border: 1px solid #6c757d;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s ease, border-color 0.3s ease; 
        }

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
      
      .close {
        float: right;
        font-size: 24px;
        cursor: pointer;
      }


    </style>
</head>
<body>


<div class="dashboard-container">
    <?php include '../partials/sidebar.php'; ?>

    <main class="content">
        <?php include '../partials/navbar.php'; ?>
        <h1>Selamat Datang, <?= ucfirst($role) ?></h1>

        <div class="data-card">
            <?php if ($data): ?>
                <h2>Data Pendaftaran</h2>

             <div class="data-wrapper">
                <div class="data-container">
                    <div class="data-item">
                        <span class="data-item-title">Kode Pendaftaran</span> 
                        <?= htmlspecialchars($data['code_registration']) ?>
                    </div>
                <div class="data-item">
                    <span class="data-item-title">Nama Lengkap</span> 
                    <?= htmlspecialchars($data['full_name']) ?>
                </div>
            <div class="data-item">
                <span class="data-item-title">Tempat Lahir</span> 
                <?= htmlspecialchars($data['place_birthdate']) ?>
            </div>
            <div class="data-item">
                <span class="data-item-title">Tanggal Lahir</span> 
                <?= htmlspecialchars($data['birthdate']) ?>
            </div>
            <div class="data-item">
                <span class="data-item-title">Alamat</span> 
                <?= htmlspecialchars($data['address']) ?>
            </div>
            <div class="data-item">
                <span class="data-item-title">No. HP</span> 
                <?= htmlspecialchars($data['numphone']) ?>
            </div>
            <div class="data-item">
                <span class="data-item-title">Nama Ayah</span> 
                <?= htmlspecialchars($data['father_name']) ?>
            </div>
            <div class="data-item">
                <span class="data-item-title">Nama Ibu</span> 
                <?= htmlspecialchars($data['mother_name']) ?>
            </div>
            <div class="data-item">
                <span class="data-item-title">Nama Wali</span> 
                <?= htmlspecialchars($data['wali_name']) ?>
            </div>
            <div class="data-item">
                <span class="data-item-title">NIK</span> 
                <?= htmlspecialchars($data['nik']) ?>
            </div>
            <div class="data-item">
                <span class="data-item-title">NISN</span> 
                <?= htmlspecialchars($data['nisn']) ?>
            </div>
            <div class="data-item">
                <span class="data-item-title">Golongan Darah</span> 
                <?= htmlspecialchars($data['golongan_darah']) ?>
            </div>
            <div class="data-item">
                <span class="data-item-title">Asal Sekolah</span> 
                <?= htmlspecialchars($data['asal_school']) ?>
            </div>
            <div class="data-item">
                <span class="data-item-title">No. Ijazah</span> 
                <?= htmlspecialchars($data['ijazah_number']) ?>
            </div>
           <div class="data-item">
                <span class="data-item-title">Status Verifikasi</span>
                <?php
                    $status = $data['status'];
                    $class = '';

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
                        $text = 'Tidak Diketahui';
                        $class = 'status-unknown';
                    }
                ?>
                <span class="<?= $class ?>"><?= $text ?></span>
            </div>

            <div class="data-item">
                <span class="data-item-title">Ijazah</span>
                <a href="../../uploads/<?= htmlspecialchars($data['ijasah_document']) ?>" target="_blank">Lihat Dokumen</a>
            </div>
            <?php if ($data['doc_tambahan']): ?>
                <div class="data-item">
                    <span class="data-item-title">Dokumen Tambahan</span>
                    <a href="../../uploads/<?= htmlspecialchars($data['doc_tambahan']) ?>" target="_blank">Lihat Dokumen Tambahan</a>
                </div>
            <?php endif; ?>

            <!-- PILIH DIVISI -->
            <div class="data-item">
                <?php if (!$adaJawaban): ?>
                    <!-- Belum ada jawaban pertanyaan -->
                    <button id="btnPilihDivisi" class="btn-cetak"  onclick="bukaModal()">Pilih Divisi</button>

                <?php else: ?>
                    <!-- Sudah ada jawaban -->
                    <?php if (!$data['id_divisi']): ?>
                        <p><strong>Divisi:</strong> Belum memilih divisi</p>
                    <?php else: ?>
                        <p><strong>Divisi yang dipilih:</strong>
                            <?php
                                $divisiQuery = "SELECT nama_divisi FROM divisi WHERE id_divisi = ?";
                                $stmtDiv = $koneksi->prepare($divisiQuery);
                                $stmtDiv->bind_param("i", $data['id_divisi']);
                                $stmtDiv->execute();
                                $divisiResult = $stmtDiv->get_result();
                                $divisiData = $divisiResult->fetch_assoc();
                                echo htmlspecialchars($divisiData['nama_divisi']);
                                $stmtDiv->close();
                            ?>
                        </p>
                    <?php endif; ?>

                    <div class="data-item">
                        <?php if (empty($data['sertifikat_divisi'])): ?>
                            <!-- Belum upload sertifikat -->
                            <form method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="sertifikat_divisi">Upload Sertifikat Pendukung (PDF, DOC, DOCX)</label>
                                    <input type="file" name="sertifikat_divisi" id="sertifikat_divisi" accept=".pdf,.doc,.docx" required>
                                </div>
                                <button type="submit" name="upload_sertifikat" class="btn-cetak">Upload Sertifikat</button>
                            </form>
                        <?php else: ?>
                            <!-- Sudah upload sertifikat -->
                            <div class="form-group">
                                <a href="../../uploads/sertifikat_divisi/<?= htmlspecialchars($data['sertifikat_divisi']) ?>" target="_blank" class="btn-cetak">Lihat Sertifikat</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="foto-wrapper">
            <span class="data-item-title">Foto</span>
            <img src="../../uploads/<?= htmlspecialchars($data['pas_photo']) ?>" alt="Foto" class="img-preview">
        </div>
        </div>
            <div class="content-btn">
                <a class="btn-daftar" href="../siswa/edit.php">Update Data</a>

                <a href="../../lib/cetak_kartu.php?id=<?= $data['id_registration'] ?>" target="_blank" class="btn-cetak">Cetak Kartu</a>
            </div>
      
            <?php else: ?>
                <p>Anda belum melakukan pendaftaran. Silakan lengkapi pendaftaran Anda terlebih dahulu.</p>
                <a href="../siswa/pendaftaran.php" class="btn-daftar">Lengkapi Pendaftaran</a>
            <?php endif; ?>
        </div>
    </main>
</div>



<!-- Modal -->
<div id="modalDivisi" class="modal">
  <div class="modal-content">
    <span class="close" onclick="tutupModal()">&times;</span>
    <h2>Pilih Divisi dan Jawab Pertanyaan</h2>

    <form id="formJawaban" method="POST" action="../../actions/simpan_jawaban_divisi.php" onsubmit="return validasiSebelumSubmit()">
      <!-- Hidden input id_registration -->
       <input type="hidden" name="id_registration" value="<?= htmlspecialchars($idRegistration) ?>"> 

      <!-- Step 1: Pilih Divisi -->
      <div id="step1">
        <label><strong>Pilih Divisi:</strong></label><br>
        <?php foreach ($divisiList as $divisi): ?>
          <input type="radio" name="selected_divisi" value="<?= htmlspecialchars($divisi['id_divisi']) ?>" required>
          <?= htmlspecialchars($divisi['nama_divisi']) ?><br>
        <?php endforeach; ?>
        <br>
        <button type="button" onclick="lanjutKeStep2()">Berikutnya</button>
        <button type="button" onclick="tutupModal()">Batal</button>
      </div>

      <!-- Step 2: Pertanyaan dari pertanyaan_divisi -->
      <div id="step2" style="display:none">
        <div id="pertanyaanContainer">
          <!-- Pertanyaan akan dimasukkan lewat JS -->
        </div>
        <div class="navigation">
          <button type="button" onclick="pertanyaanSebelumnya()">Sebelumnya</button>
          <button type="button" onclick="pertanyaanBerikutnya()">Berikutnya</button>
          <button type="submit" id="btnSimpan" style="display:none">Simpan Jawaban</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
let pertanyaanList = Object.values(<?php echo json_encode($pertanyaanList); ?>);
let currentStep = 0;

function lanjutKeStep2() {
  const selectedDivisi = document.querySelector('input[name="selected_divisi"]:checked');
  if (!selectedDivisi) {
    alert("Silakan pilih divisi terlebih dahulu.");
    return;
  }
  document.getElementById("step1").style.display = "none";
  document.getElementById("step2").style.display = "block";

  currentStep = 0;
  tampilkanPertanyaan();
}

function tampilkanPertanyaan() {
  const container = document.getElementById("pertanyaanContainer");
  container.innerHTML = ""; // kosongkan dulu

  const pertanyaan = pertanyaanList[currentStep];
  if (!pertanyaan) {
    container.innerHTML = "<p>Tidak ada pertanyaan.</p>";
    return;
  }

  // Label Pertanyaan (judul + isi)
  const judulLabel = document.createElement("label");
  judulLabel.innerHTML = `<strong>${pertanyaan.judul_pertanyaan}</strong>`;
  container.appendChild(judulLabel);

  const isiLabel = document.createElement("p");
  isiLabel.textContent = pertanyaan.isi_pertanyaan;
  container.appendChild(isiLabel);

  // Input sesuai tipe pertanyaan
  if (pertanyaan.tipe_pertanyaan === 'text' || pertanyaan.tipe_pertanyaan === 'textarea') {
    const input = document.createElement(
      pertanyaan.tipe_pertanyaan === 'text' ? "input" : "textarea"
    );
    input.name = `jawaban[${pertanyaan.id_pertanyaan}]`;
    input.required = pertanyaan.is_required == "1";
    input.style.display = "block";
    input.style.marginTop = "8px";
    input.style.marginBottom= "8px";
    input.style.width = "50%";
    container.appendChild(input);
  } else if (
    pertanyaan.tipe_pertanyaan === 'radio' ||
    pertanyaan.tipe_pertanyaan === 'checkbox'
  ) {
    pertanyaan.opsi.forEach(op => {
      const wrapper = document.createElement("div");
      wrapper.style.marginTop = "4px";

      const input = document.createElement("input");
      input.type = pertanyaan.tipe_pertanyaan;
      input.name = pertanyaan.tipe_pertanyaan === 'radio'
        ? `jawaban[${pertanyaan.id_pertanyaan}]`
        : `jawaban[${pertanyaan.id_pertanyaan}][]`;
      input.value = op.id_opsi;
      if (pertanyaan.is_required == "1" && pertanyaan.tipe_pertanyaan === 'radio') {
        input.required = true;
      }

      const labelOpsi = document.createElement("label");
      labelOpsi.style.marginLeft = "4px";
      labelOpsi.textContent = op.opsi_text;

      wrapper.appendChild(input);
      wrapper.appendChild(labelOpsi);
      container.appendChild(wrapper);
    });
  }

  // Tombol navigasi
  document.querySelector("button[onclick='pertanyaanSebelumnya()']").style.display = currentStep === 0 ? "none" : "inline";
  document.querySelector("button[onclick='pertanyaanBerikutnya()']").style.display = currentStep === pertanyaanList.length - 1 ? "none" : "inline";
  document.getElementById("btnSimpan").style.display = currentStep === pertanyaanList.length - 1 ? "inline" : "none";
}

function validasiJawaban() {
  const pertanyaan = pertanyaanList[currentStep];
  if (!pertanyaan) return true;

  const container = document.getElementById("pertanyaanContainer");

  if (pertanyaan.tipe_pertanyaan === 'text' || pertanyaan.tipe_pertanyaan === 'textarea') {
    const input = container.querySelector(pertanyaan.tipe_pertanyaan === 'text' ? "input" : "textarea");
    if (pertanyaan.is_required == "1" && (!input || input.value.trim() === "")) {
      alert("Jawaban tidak boleh kosong.");
      return false;
    }
  } else if (pertanyaan.tipe_pertanyaan === 'radio') {
    const checked = container.querySelector(`input[type='radio']:checked`);
    if (pertanyaan.is_required == "1" && !checked) {
      alert("Silakan pilih salah satu opsi.");
      return false;
    }
  } else if (pertanyaan.tipe_pertanyaan === 'checkbox') {
    const checked = container.querySelectorAll(`input[type='checkbox']:checked`);
    if (pertanyaan.is_required == "1" && checked.length === 0) {
      alert("Silakan pilih minimal satu opsi.");
      return false;
    }
  }

  return true;
}

function pertanyaanBerikutnya() {
  if (!validasiJawaban()) return;
  if (currentStep < pertanyaanList.length - 1) {
    currentStep++;
    tampilkanPertanyaan();
  }
}

function pertanyaanSebelumnya() {
  if (currentStep > 0) {
    currentStep--;
    tampilkanPertanyaan();
  }
}

function validasiSebelumSubmit() {
  if (!validasiJawaban()) return false;

  // Pastikan divisi terpilih
  const selectedDivisi = document.querySelector('input[name="selected_divisi"]:checked');
  if (!selectedDivisi) {
    alert("Silakan pilih divisi.");
    kembaliKeStep1();
    return false;
  }
  return true;
}

function kembaliKeStep1() {
  currentStep = 0;
  document.getElementById("step2").style.display = "none";
  document.getElementById("step1").style.display = "block";
}

function tutupModal() {
  document.getElementById("modalDivisi").style.display = "none";
}

function bukaModal() {
  document.getElementById("modalDivisi").style.display = "block";
}
</script>




</body>
</html>

