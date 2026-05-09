<?php
// Mendapatkan nama file yang sedang dibuka (contoh: dashboard.php)
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
    /* Styling khusus Sidebar */
    .sidebar {
        position: fixed;
        top: 1.5rem;
        left: 1.5rem;
        bottom: 1.5rem;
        width: 270px; 
        border-radius: 24px;
        padding: 2.5rem 1.5rem;
        display: flex;
        flex-direction: column;
        z-index: 100;
    }
    .brand-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 3.5rem;
        text-align: center;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
    }
    .nav-links { list-style: none; display: flex; flex-direction: column; gap: 0.6rem; padding: 0; }
    .nav-links a {
        text-decoration: none;
        color: var(--text-gray);
        font-weight: 600;
        font-size: 0.95rem;
        padding: 1.1rem 1.2rem;
        border-radius: 14px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .nav-links a i { font-size: 1.1rem; width: 24px; text-align: center; }
    
    .nav-links a:hover {
        background: var(--primary-soft);
        color: var(--primary);
        transform: translateX(4px);
    }
    .nav-links a.active {
        background: var(--primary);
        color: #ffffff;
        box-shadow: 0 8px 20px rgba(5, 150, 105, 0.25);
    }
    .nav-links a.logout-btn {
        background: rgba(239, 68, 68, 0.05);
        color: #ef4444;
        margin-top: 3rem;
    }
    .nav-links a.logout-btn:hover {
        background: #ef4444;
        color: #ffffff;
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.25);
        transform: none;
    }
</style>

<aside class="sidebar glass-panel">
    <div class="brand-title">
        <i class="fas fa-book-reader"></i> SIMPUS
    </div>
    <ul class="nav-links">
        <li><a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : ''; ?>"><i class="fas fa-layer-group"></i> Dashboard</a></li>
        <li><a href="kelola_buku.php" class="<?= $current_page == 'kelola_buku.php' ? 'active' : ''; ?>"><i class="fas fa-book"></i> Katalog Buku</a></li>
        <li><a href="kelola_siswa.php" class="<?= $current_page == 'kelola_siswa.php' ? 'active' : ''; ?>"><i class="fas fa-users"></i> Data Anggota</a></li>
        <li><a href="transaksi.php" class="<?= $current_page == 'transaksi.php' ? 'active' : ''; ?>"><i class="fas fa-exchange-alt"></i> Transaksi</a></li>
        <li><a href="laporan.php" class="<?= $current_page == 'laporan.php' ? 'active' : ''; ?>"><i class="fas fa-file-alt"></i> Laporan</a></li>
        <li><a href="../proses_login.php?logout=true" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Keluar</a></li>
    </ul>
</aside>