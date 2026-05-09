<?php
session_start();
require '../config/koneksi.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Mengambil semua data buku dari database
$stmt = $pdo->query("SELECT * FROM buku ORDER BY id_buku DESC");
$buku_list = $stmt->fetchAll();

// Set judul halaman untuk header
$title = 'Kelola Data Buku | SIMPUS';

// Memanggil Header dan Sidebar
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<!-- Perhatikan, kita tidak perlu lagi menulis tag <main class="main-content"> 
     karena sudah dibuka di dalam file sidebar.php -->

<header class="topbar">
    <div>
        <p style="color: #a3aed1; font-weight: 500; margin-bottom: 0.3rem;">Manajemen Katalog</p>
        <h1>Kelola Data Buku</h1>
    </div>
</header>

<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
        <h2 style="font-size: 1.4rem;">Daftar Pustaka</h2>
        <a href="tambah_buku.php" class="btn-primary" style="text-decoration: none;">+ Tambah Buku Baru</a>
    </div>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="padding: 1.5rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; color: #a3aed1; font-weight: 600; font-size: 0.95rem; text-transform: uppercase;">No</th>
                <th style="padding: 1.5rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; color: #a3aed1; font-weight: 600; font-size: 0.95rem; text-transform: uppercase;">Kode Barcode</th>
                <th style="padding: 1.5rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; color: #a3aed1; font-weight: 600; font-size: 0.95rem; text-transform: uppercase;">Judul Buku</th>
                <th style="padding: 1.5rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; color: #a3aed1; font-weight: 600; font-size: 0.95rem; text-transform: uppercase;">Penulis</th>
                <th style="padding: 1.5rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; color: #a3aed1; font-weight: 600; font-size: 0.95rem; text-transform: uppercase;">Stok</th>
                <th style="padding: 1.5rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; color: #a3aed1; font-weight: 600; font-size: 0.95rem; text-transform: uppercase;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($buku_list) > 0): ?>
                <?php $no = 1; foreach ($buku_list as $buku): ?>
                <tr>
                    <td style="padding: 1.5rem 1rem; border-bottom: 1px solid #e2e8f0;"><?= $no++; ?></td>
                    <td style="padding: 1.5rem 1rem; border-bottom: 1px solid #e2e8f0;"><span style="font-family: monospace; background: #f4f7fe; padding: 0.4rem; border-radius: 6px;"><?= htmlspecialchars($buku['kode_barcode']); ?></span></td>
                    <td style="padding: 1.5rem 1rem; border-bottom: 1px solid #e2e8f0; font-weight: 500;"><?= htmlspecialchars($buku['judul_buku']); ?></td>
                    <td style="padding: 1.5rem 1rem; border-bottom: 1px solid #e2e8f0;"><?= htmlspecialchars($buku['penulis']); ?></td>
                    <td style="padding: 1.5rem 1rem; border-bottom: 1px solid #e2e8f0;">
                        <?php if ($buku['stok'] > 0): ?>
                            <span style="padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem; background: #e6f4ea; color: #1e8e3e;"><?= htmlspecialchars($buku['stok']); ?> Tersedia</span>
                        <?php else: ?>
                            <span style="padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem; background: #fce8e6; color: #d93025;">Habis</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 1.5rem 1rem; border-bottom: 1px solid #e2e8f0;">
                        <a href="edit_buku.php?id=<?= $buku['id_buku']; ?>" style="padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-size: 0.9rem; font-weight: 600; margin-right: 0.5rem; background: #eef2ff; color: #4318FF;">Edit</a>
                        <a href="hapus_buku.php?id=<?= $buku['id_buku']; ?>" onclick="return confirm('Yakin ingin menghapus buku ini?');" style="padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-size: 0.9rem; font-weight: 600; background: #fce8e6; color: #d93025;">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem; color: #a3aed1;">Belum ada data buku yang ditambahkan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php 
// Memanggil Footer
include '../includes/footer.php'; 
?>