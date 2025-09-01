<?php

include '../../config/koneksi.php';
include '../../actions/auth_check.php';
include '../../config.php';

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan.";
    exit;
}

$id = $_GET['id'];
$query = "SELECT * FROM pendaftaran_siswa WHERE id_registration = '$id'";
$result = mysqli_query($koneksi, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Data tidak ditemukan.";
    exit;
}

// Jika siswa, hanya boleh akses data sendiri
if (isSiswa() && $data['id'] != $userId) {
    echo "Anda tidak memiliki akses.";
    exit;
}

$data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Detail Pendaftaran</title>
    <link rel="stylesheet" href="../css/dashboard.css"/>
    <link rel="stylesheet" href="../css/modal.css"/>
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

        .btn-cetak {
            padding: 10px 20px;
            background-color: #2563eb;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }

    </style>
</head>
<body>
  

<div class="dashboard-container">
    <?php include '../partials/sidebar.php'; ?>

    <main class="content">
        <?php include '../partials/navbar.php'; ?>
         
         <div class="data-card">
            <h2>Detail Data Pendaftaran Siswa</h2>

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

                  <div class="data-item">
                      <p><strong>Divisi yang dipilih:</strong> 
                        <?php
                            $divisiQuery = "SELECT nama_divisi FROM divisi WHERE id_divisi = ?";
                            $stmtDiv = $koneksi->prepare($divisiQuery);
                            $stmtDiv->bind_param("i", $data['id_divisi']);
                            $stmtDiv->execute();
                            $divisiResult = $stmtDiv->get_result();
                            $divisiData = $divisiResult->fetch_assoc();
                            echo htmlspecialchars($divisiData['nama_divisi']);
                        ?>
                    </p>
                      <?php if (empty($data['sertifikat_divisi'])): ?>
                            <p>Tidak Ada Data Sertifikat</p>
                        <?php else: ?>
                            <div class="form-group">
                                <!-- <label>Sertifikat Divisi:</label><br> -->
                                <a href="../../uploads/sertifikat_divisi/<?= htmlspecialchars($data['sertifikat_divisi']) ?>" target="_blank" class="btn-cetak">Lihat Sertifikat</a>
                            </div>
                        <?php endif; ?>
                    </div>
            
        </div>

        <div class="foto-wrapper">
            <span class="data-item-title">Foto</span>
            <img src="../../uploads/<?= htmlspecialchars($data['pas_photo']) ?>" alt="Foto" class="img-preview">
        </div>

        </div>
           <a href="../../lib/cetak_kartu.php?id=<?= $data['id_registration'] ?>" target="_blank" class="btn-cetak">Cetak Kartu</a>
        </div>

    </main>
 
    
</div>

</body>
</html>
