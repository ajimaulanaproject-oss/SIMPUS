<?php
session_start();
require '../config/koneksi.php';

// Pastikan hanya siswa yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../index.php");
    exit;
}

$id_siswa = $_SESSION['id_user'];

// Ambil riwayat transaksi khusus untuk siswa yang sedang login
// Kita gunakan JOIN untuk mengambil data judul buku dari tabel buku
$stmt = $pdo->prepare("
    SELECT t.*, b.judul_buku, b.kode_barcode 
    FROM transaksi t 
    JOIN buku b ON t.id_buku = b.id_buku 
    WHERE t.id_user = ? 
    ORDER BY t.id_transaksi DESC
");
$stmt->execute([$id_siswa]);
$riwayat = $stmt->fetchAll();

$title = 'Riwayat Pinjam | SIMPUS';
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<header class="topbar">
    <div>
        <p style="color: #a3aed1; font-weight: 500; margin-bottom: 0.3rem;">Aktivitas Saya</p>
        <h1>Riwayat Peminjaman Buku</h1>
    </div>
</header>

<div class="glass-card">
    <h2 style="font-size: 1.4rem; margin-bottom: 2.5rem;">Daftar Transaksi Saya</h2>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="padding: 1.5rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; color: #a3aed1; font-weight: 600; font-size: 0.95rem; text-transform: uppercase;">Buku</th>
                <th style="padding: 1.5rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; color: #a3aed1; font-weight: 600; font-size: 0.95rem; text-transform: uppercase;">Tanggal Pinjam</th>
                <th style="padding: 1.5rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; color: #a3aed1; font-weight: 600; font-size: 0.95rem; text-transform: uppercase;">Batas Waktu</th>
                <th style="padding: 1.5rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; color: #a3aed1; font-weight: 600; font-size: 0.95rem; text-transform: uppercase;">Status</th>
                <th style="padding: 1.5rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; color: #a3aed1; font-weight: 600; font-size: 0.95rem; text-transform: uppercase;">Denda</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($riwayat) > 0): ?>
                <?php foreach ($riwayat as $row): ?>
                <tr>
                    <td style="padding: 1.5rem 1rem; border-bottom: 1px solid #e2e8f0;">
                        <div style="font-weight: 600; color: #2b3674; margin-bottom: 0.3rem;"><?= htmlspecialchars($row['judul_buku']); ?></div>
                        <div style="font-size: 0.85rem; color: #a3aed1; font-family: monospace;">ISBN: <?= htmlspecialchars($row['kode_barcode']); ?></div>
                    </td>
                    <td style="padding: 1.5rem 1rem; border-bottom: 1px solid #e2e8f0; font-weight: 500;">
                        <?= $row['tanggal_pinjam'] ? date('d M Y', strtotime($row['tanggal_pinjam'])) : '<span style="color: #a3aed1;">Menunggu...</span>'; ?>
                    </td>
                    <td style="padding: 1.5rem 1rem; border-bottom: 1px solid #e2e8f0; font-weight: 500;">
                        <?= $row['tenggat_waktu'] ? date('d M Y', strtotime($row['tenggat_waktu'])) : '<span style="color: #a3aed1;">-</span>'; ?>
                    </td>
                    <td style="padding: 1.5rem 1rem; border-bottom: 1px solid #e2e8f0;">
                        <?php 
                            // Logika warna badge status
                            if ($row['status'] == 'pending') {
                                echo '<span style="padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; background: #fffbeb; color: #f59e0b;">Menunggu Konfirmasi</span>';
                            } elseif ($row['status'] == 'dipinjam') {
                                echo '<span style="padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; background: #eef2ff; color: #4318FF;">Sedang Dipinjam</span>';
                            } elseif ($row['status'] == 'dikembalikan') {
                                echo '<span style="padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; background: #e6f4ea; color: #1e8e3e;">Dikembalikan</span>';
                            } elseif ($row['status'] == 'telat') {
                                echo '<span style="padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; background: #fce8e6; color: #d93025;">Terlambat</span>';
                            } else {
                                echo '<span style="padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; background: #fce8e6; color: #d93025;">Ditolak</span>';
                            }
                        ?>
                    </td>
                    <td style="padding: 1.5rem 1rem; border-bottom: 1px solid #e2e8f0; font-weight: 700; color: <?= $row['denda'] > 0 ? '#d93025' : '#1e8e3e'; ?>;">
                        Rp <?= number_format($row['denda'], 0, ',', '.'); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 3rem; color: #a3aed1; font-size: 1.1rem;">Anda belum pernah meminjam buku.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>