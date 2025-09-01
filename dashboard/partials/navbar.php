<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
include_once __DIR__ . '/../../config.php';
include_once __DIR__ . '/../../actions/auth_check.php';
include_once __DIR__ . '/../../config/koneksi.php';

// session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$username = ucfirst($_SESSION['user']['username'] ?? 'Pengguna');
?>

<style>
    .navbar {
        position: sticky;
        top: 0;
        z-index: 1000;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #ffffff;
        padding: 12px 24px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        font-family: 'Segoe UI', sans-serif;
        font-size: 16px;
        color: #333;
        border: 2px;
        border-radius: 10px;
    }

    /* User Info Styling */
    .navbar .username {
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .navbar .username::before {
        content: '👤';
        font-size: 18px;
    }

    /* Logout Button */
    .navbar a.logout {
        text-decoration: none;
        color: #e63946;
        background-color: #fceaea;
        padding: 6px 14px;
        border-radius: 6px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .navbar a.logout:hover {
        background-color: #f8d7da;
    }

    .navbar-message {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 10px
    }

    /* Hide navbar on screen width <= 768px */
    @media screen and (max-width: 768px) {
        .navbar {
            display: none;
        }
    }
</style>

<nav class="navbar">
    <div class="username">
        <?= htmlspecialchars($username) ?>
    </div>
    <div>
        <div class="navbar-message">
            <?php include 'message_notif.php'; ?>
            <a href="<?= $base_url ?>/actions/logout.php" class="logout">Logout</a>
        </div>
    </div>
</nav>
