<?php
include_once __DIR__ . '/../../config/koneksi.php';

$user_id = $_SESSION['user']['id'];
$user_role = $_SESSION['user']['role'];

$customStatusMessage = null;

if ($user_role === 'admin') {
    // Admin melihat semua notifikasi
    $query = "SELECT * FROM notifications 
              WHERE receiver_id = $user_id AND read_notif = 0 
              ORDER BY created_at DESC";
} elseif ($user_role === 'siswa') {
    // Ambil status pendaftaran siswa dari tabel pendaftaran_siswa
    $statusQuery = "SELECT status FROM pendaftaran_siswa WHERE casis_id_registration = $user_id LIMIT 1";
    $statusResult = mysqli_query($koneksi, $statusQuery);

    if ($statusResult && mysqli_num_rows($statusResult) > 0) {
        $statusRow = mysqli_fetch_assoc($statusResult);
        $status = $statusRow['status'];

        if ($status === 'diterima') {
            $customStatusMessage = [
                'title' => 'Selamat!',
                'message' => 'Anda diterima di sekolah ini.'
            ];
        } elseif ($status === 'ditolak') {
            $customStatusMessage = [
                'title' => 'Mohon Maaf',
                'message' => 'Anda tidak diterima di sekolah ini.'
            ];
        }
    }

    // Query notifikasi untuk siswa
    $query = "SELECT * FROM notifications 
              WHERE receiver_id = $user_id AND read_notif = 0 
              ORDER BY created_at DESC";
} else {
    // Default
    $query = "SELECT * FROM notifications 
              WHERE receiver_id = $user_id 
              ORDER BY created_at DESC";
}

$hasil = mysqli_query($koneksi, $query);
$total_notif = mysqli_num_rows($hasil);

// Tambahkan 1 jika siswa memiliki status khusus (diterima/ditolak)
if ($user_role === 'siswa' && $customStatusMessage) {
    $total_notif += 1;
}

?>


<style>
    .notif-wrapper {
        position: relative;
        display: inline-block;
        font-family: 'Segoe UI', sans-serif;
    }

    .notif-message {
        display: inline-flex;
        align-items: center;
        background-color: #f4f6f8;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 6px 12px;
        gap: 8px;
        font-size: 14px;
        color: #333;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .notif-message:hover {
        background-color: #e9ecef;
    }

    .notif-message .icon {
        stroke: #1d3557;
        width: 20px;
        height: 20px;
    }

    .notif-message .notif-count {
        font-weight: 500;
    }

    .dropdown-message {
        display: none;
        position: absolute;
        top: 110%;
        right: 0;
        width: 350px;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 12px;
        z-index: 1000;
        max-height: 400px;
        overflow-y: auto;
    }

    .dropdown-message.active {
        display: block;
    }

    .dropdown-message .notif-item {
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .dropdown-message .notif-item:last-child {
        border-bottom: none;
    }

    .notif-item strong {
        font-size: 14px;
        color: #222;
    }

    .notif-item p {
        margin: 4px 0;
        font-size: 13px;
        color: #444;
    }

    .btn-small {
        padding: 4px 8px;
        font-size: 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        margin-right: 6px;
    }

    .btn-read {
        background-color: #e0f7fa;
        color: #00796b;
    }

    .btn-hapus {
        background-color: #ffebee;
        color: #c62828;
    }

    .btn-read-all {
        background-color: #ede7f6;
        color: #4527a0;
        width: 100%;
        padding: 6px 10px;
        margin-top: 10px;
    }

    /* Base style */
    .custom-status-message {
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 12px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        animation: fadeIn 0.3s ease-in-out;
    }

    /* Pending */
    .custom-status-message.pending {
        background: linear-gradient(135deg, #fff8e1, #ffecb3);
        border-left: 4px solid #fbc02d;
        color: #795548;
    }

    .custom-status-message.pending strong {
        color: #f57f17;
    }

    /* Diterima */
    .custom-status-message.diterima {
        background: linear-gradient(135deg, #e0f7fa, #b2ebf2);
        border-left: 4px solid #0097a7;
        color: #004d40;
    }

    .custom-status-message.diterima strong {
        color: #006064;
    }

    /* Ditolak */
    .custom-status-message.ditolak {
        background: linear-gradient(135deg, #ffe0e0, #ffcccc);
        border-left: 4px solid #d32f2f;
        color: #b71c1c;
    }

    .custom-status-message.ditolak strong {
        color: #c62828;
    }

    .custom-status-message p {
        font-size: 13.5px;
        margin: 0;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    
</style>

<div class="notif-wrapper">
    <div class="notif-message" onclick="toggleDropdown()">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path d="M22 7L13.009 12.727a2 2 0 0 1-2.009 0L2 7" />
            <rect x="2" y="4" width="20" height="16" rx="2" />
        </svg>
        <span class="notif-count"><?= $total_notif ?> Pesan</span>
    </div>

  <div class="dropdown-message" id="notifDropdown">
    <?php if ($user_role === 'siswa' && $customStatusMessage): ?>
        <div class="custom-status-message <?= $status ?>">
            <strong><?= htmlspecialchars($customStatusMessage['title']) ?></strong>
            <p><?= htmlspecialchars($customStatusMessage['message']) ?></p>
        </div>
    <?php elseif ($total_notif > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($hasil)): ?>
            <div class="notif-item" id="notif-<?= $row['id_notif'] ?>">
                <strong><?= htmlspecialchars($row['title_notif']) ?></strong>
                <p><?= $row['message_notif'] ?></p>
            </div>
        <?php endwhile; ?>
        <button class="btn-small btn-read-all" onclick="deleteAll()">Tandai Semua Sudah Dibaca</button>
    <?php else: ?>
        <p>Tidak ada notifikasi baru.</p>
    <?php endif; ?>
   </div>
</div>


<script>
    function toggleDropdown() {
        document.getElementById("notifDropdown").classList.toggle("active");
    }

    document.addEventListener('click', function (event) {
        const wrapper = document.querySelector('.notif-wrapper');
        if (!wrapper.contains(event.target)) {
            document.getElementById('notifDropdown').classList.remove('active');
        }
    });

    function deleteAll() {
    fetch('../actions/notification/delete_all.php')
        .then(res => {
            if (!res.ok) throw new Error('Failed to delete');
            return res.text();
        })
        .then(data => {
            if (data === "OK") {
                // Bersihkan dropdown dan update count notifikasi jadi 0
                document.getElementById("notifDropdown").innerHTML = "<p>Tidak ada notifikasi baru.</p>";
                document.querySelector('.notif-count').textContent = "0 Pesan";
            } else {
                alert('Gagal menghapus notifikasi');
            }
        })
        .catch(() => alert('Gagal menghapus notifikasi'));
}

</script>

