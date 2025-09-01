<?php
include_once __DIR__ . '/../../config.php';


$current_page = basename($_SERVER['PHP_SELF']);

$relative_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(__DIR__ . '/..'));
$relative_url = trim(str_replace('\\', '/', $relative_path), '/'); // handle Windows path

function isActive($path) {
    return strpos($_SERVER['REQUEST_URI'], $path) !== false ? 'active' : '';
}

?>

<aside class="sidebar">
    <span class="logo">SMAN 2 Simeulue Barat</span>
    <nav> 
        <ul>
            <h2 class="sub-title">Menu</h2>
            <?php if (isAdmin()): ?>
                <li>
                    <a href="<?= $base_url ?>/dashboard/index.php"  class="<?= isActive('/dashboard/index.php') ?>">
                        <svg viewBox="0 0 24 24"><path d="M3 13h2v-2H3v2zm0 6h2v-2H3v2zm0-12h2V5H3v2zm4 6h14v-2H7v2zm0 6h14v-2H7v2zm0-12v2h14V7H7z"/></svg>
                    Beranda
                    </a>
                </li>
                <li>
                    <a href="<?= $base_url ?>/dashboard/pendaftaran/index.php"  class="<?= isActive('/dashboard/pendaftaran/index.php') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-database-icon lucide-database"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>
                    Pendaftaran
                    </a>
                </li>
                <li>
                    <a href="<?= $base_url ?>/dashboard/users.php"  class="<?= isActive('/dashboard/users.php') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-user-icon lucide-square-user"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="12" cy="10" r="3"/><path d="M7 21v-2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2"/></svg>
                    Users
                    </a>
                </li>
                <li>
                    <a href="<?= $base_url ?>/dashboard/divisi.php"  class="<?= isActive('/dashboard/divisi.php') ?>">
                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-codesandbox-icon lucide-codesandbox"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="7.5 4.21 12 6.81 16.5 4.21"/><polyline points="7.5 19.79 7.5 14.6 3 12"/><polyline points="21 12 16.5 14.6 16.5 19.79"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" x2="12" y1="22.08" y2="12"/></svg>
                    Divisi
                    </a>
                </li>
                <li>
                    <a href="<?= $base_url ?>/dashboard/berita/index.php" class="<?= isActive('/dashboard/berita/index.php') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-newspaper-icon lucide-newspaper"><path d="M15 18h-5"/><path d="M18 14h-8"/><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0v-9a2 2 0 0 1 2-2h2"/><rect width="8" height="4" x="10" y="6" rx="1"/></svg>
                        Kelola Berita 
                    </a>
                </li>
                <li>
                    <a href="<?= $base_url ?>/dashboard/kegiatan/index.php" class="<?= isActive('/dashboard/kegiatan/index.php') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-check2-icon lucide-calendar-check-2"><path d="M8 2v4"/><path d="M16 2v4"/><path d="M21 14V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8"/><path d="M3 10h18"/><path d="m16 20 2 2 4-4"/></svg>
                        Kelola Kegiatan 
                    </a>
                </li>
                 <li>
                    <a href="<?= $base_url ?>/dashboard/my-profile.php" class="<?= isActive('/dashboard/my-profile.php') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-cog-icon lucide-user-cog"><path d="M10 15H6a4 4 0 0 0-4 4v2"/><path d="m14.305 16.53.923-.382"/><path d="m15.228 13.852-.923-.383"/><path d="m16.852 12.228-.383-.923"/><path d="m16.852 17.772-.383.924"/><path d="m19.148 12.228.383-.923"/><path d="m19.53 18.696-.382-.924"/><path d="m20.772 13.852.924-.383"/><path d="m20.772 16.148.924.383"/><circle cx="18" cy="15" r="3"/><circle cx="9" cy="7" r="4"/></svg>
                        My Profile
                    </a>
                </li>
            <?php elseif (isKepalaSekolah()): ?>
                <li>
                    <a href="<?= $base_url ?>/dashboard/index.php"  class="<?= isActive('/dashboard/index.php') ?>">
                        <svg viewBox="0 0 24 24"><path d="M3 13h2v-2H3v2zm0 6h2v-2H3v2zm0-12h2V5H3v2zm4 6h14v-2H7v2zm0 6h14v-2H7v2zm0-12v2h14V7H7z"/></svg>
                    Beranda
                    </a>
                </li>
                <li>
                    <a href="<?= $base_url ?>/dashboard/pendaftaran/index.php"  class="<?= isActive('/dashboard/pendaftaran/index.php') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-database-icon lucide-database"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>
                    Pendaftaran
                    </a>
                </li>
                <li>
                    <a href="<?= $base_url ?>/dashboard/my-profile.php" class="<?= isActive('/dashboard/my-profile.php') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-cog-icon lucide-user-cog"><path d="M10 15H6a4 4 0 0 0-4 4v2"/><path d="m14.305 16.53.923-.382"/><path d="m15.228 13.852-.923-.383"/><path d="m16.852 12.228-.383-.923"/><path d="m16.852 17.772-.383.924"/><path d="m19.148 12.228.383-.923"/><path d="m19.53 18.696-.382-.924"/><path d="m20.772 13.852.924-.383"/><path d="m20.772 16.148.924.383"/><circle cx="18" cy="15" r="3"/><circle cx="9" cy="7" r="4"/></svg>
                        My Profile
                    </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="<?= $base_url ?>/dashboard/siswa/index.php"  class="<?= isActive('/dashboard/siswa/index.php') ?>">
                    <svg viewBox="0 0 24 24"><path d="M3 13h2v-2H3v2zm0 6h2v-2H3v2zm0-12h2V5H3v2zm4 6h14v-2H7v2zm0 6h14v-2H7v2zm0-12v2h14V7H7z"/></svg>
                    Beranda
                    </a>
                </li>
                <li>
                    <a href="<?= $base_url ?>/dashboard/siswa/pendaftaran.php"  class="<?= isActive('/dashboard/siswa/pendaftaran.php') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text-icon lucide-file-text"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                    Pendaftaran
                    </a>
                </li>
                    <li>
                    <a href="<?= $base_url ?>/dashboard/my-profile.php" class="<?= isActive('/dashboard/my-profile.php') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-cog-icon lucide-user-cog"><path d="M10 15H6a4 4 0 0 0-4 4v2"/><path d="m14.305 16.53.923-.382"/><path d="m15.228 13.852-.923-.383"/><path d="m16.852 12.228-.383-.923"/><path d="m16.852 17.772-.383.924"/><path d="m19.148 12.228.383-.923"/><path d="m19.53 18.696-.382-.924"/><path d="m20.772 13.852.924-.383"/><path d="m20.772 16.148.924.383"/><circle cx="18" cy="15" r="3"/><circle cx="9" cy="7" r="4"/></svg>
                        My Profile
                    </a>
                </li>
            <?php endif; ?>

            <li class="mobile-only">
                <a href="<?= $base_url ?>/actions/logout.php" class="logout">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                    Logout
                </a>
            </li>
        </ul>
    </nav>   
</aside>



