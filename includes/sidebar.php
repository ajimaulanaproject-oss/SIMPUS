<?php
$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? '';
?>
<aside class="sidebar">
    <div class="brand-title">SIMPUS</div>
    <ul class="nav-links">
        <?php if ($role === 'admin'): ?>
            <li><a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a></li>
            <li><a href="kelola_buku.php" class="<?= in_array($current_page, ['kelola_buku.php', 'tambah_buku.php', 'edit_buku.php']) ? 'active' : ''; ?>">Data Buku</a></li>
            <li><a href="kelola_siswa.php" class="<?= $current_page == 'kelola_siswa.php' ? 'active' : ''; ?>">Data Siswa</a></li>
            <li><a href="transaksi.php" class="<?= $current_page == 'transaksi.php' ? 'active' : ''; ?>">Transaksi</a></li>
            <li><a href="laporan.php" class="<?= $current_page == 'laporan.php' ? 'active' : ''; ?>">Laporan</a></li>
        <?php else: ?>
            <li><a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a></li>
            <li><a href="katalog_buku.php" class="<?= $current_page == 'katalog_buku.php' ? 'active' : ''; ?>">Katalog Buku</a></li>
            <li><a href="riwayat_pinjam.php" class="<?= $current_page == 'riwayat_pinjam.php' ? 'active' : ''; ?>">Riwayat Pinjam</a></li>
        <?php endif; ?>
        <li style="margin-top: 2rem;"><a href="../proses_login.php?logout=true" style="background: #ffebee; color: #d32f2f;">Logout</a></li>
    </ul>
</aside>
<main class="main-content">