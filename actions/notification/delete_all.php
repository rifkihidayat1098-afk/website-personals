<?php
include '../../config/koneksi.php';
include '../auth_check.php';

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    exit('Unauthorized');
}

$user_id = $_SESSION['user']['id'];

// Jika ingin hanya hapus notifikasi user tersebut:
$query = "DELETE FROM notifications WHERE receiver_id = $user_id";

if (mysqli_query($koneksi, $query)) {
    echo "OK";
} else {
    http_response_code(500);
    echo "Error deleting notifications";
}
?>
