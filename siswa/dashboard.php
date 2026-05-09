<?php
session_start();
require '../config/koneksi.php';

// Pastikan hanya siswa yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../index.php");
    exit;
}

$id_siswa = $_SESSION['id_user'];
$nama_siswa = $_SESSION['nama_lengkap'];

// Mengambil statistik pribadi siswa
// 1. Buku yang sedang dipinjam
$stmt1 = $pdo->prepare("SELECT COUNT(*) as total_dipinjam FROM transaksi WHERE id_user = ? AND status = 'dipinjam'");
$stmt1->execute([$id_siswa]);
$buku_dipinjam = $stmt1->fetch()['total_dipinjam'];

// 2. Menunggu konfirmasi
$stmt2 = $pdo->prepare("SELECT COUNT(*) as total_pending FROM transaksi WHERE id_user = ? AND status = 'pending'");
$stmt2->execute([$id_siswa]);
$buku_pending = $stmt2->fetch()['total_pending'];

// 3. Total Denda (akumulasi dari seluruh riwayat)
$stmt3 = $pdo->prepare("SELECT SUM(denda) as total_denda FROM transaksi WHERE id_user = ?");
$stmt3->execute([$id_siswa]);
$total_denda = $stmt3->fetch()['total_denda'];
if (!$total_denda) $total_denda = 0; // Set ke 0 jika NULL

$title = 'Dashboard Siswa | SIMPUS';
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<style>
    /* Stats Cards Container */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2.5rem; /* Jarak antar kartu sangat lega */
        margin-bottom: 3.5rem;
    }

    /* Glassmorphism Card */
    .stat-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        padding: 2.5rem;
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.6);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        gap: 1rem;
        transition: transform 0.3s ease;
    }
    .stat-card:hover { transform: translateY(-5px); }
    .stat-card h3 { font-size: 1.1rem; color: #a3aed1; font-weight: 500; }
    .stat-card .value { font-size: 2.5rem; font-weight: 700; color: #2b3674; }

    /* Welcome Banner */
    .welcome-banner {
        background: linear-gradient(135deg, #4318FF, #8a2be2);
        color: white;
        padding: 3rem;
        border-radius: 24px;
        margin-bottom: 3.5rem;
        box-shadow: 0 20px 40px rgba(67, 24, 255, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .welcome-banner h2 { font-size: 2.2rem; font-weight: 700; margin-bottom: 0.5rem; }
    .welcome-banner p { font-size: 1.1rem; opacity: 0.9; }
</style>

<header class="topbar">
    <div>
        <p style="color: #a3aed1; font-weight: 500; margin-bottom: 0.3rem;">Halaman Utama</p>
        <h1>Dashboard Siswa</h1>
    </div>
</header>

<div class="welcome-banner">
    <div>
        <h2>👋 Halo, <?= htmlspecialchars($nama_siswa); ?>!</h2>
        <p>Selamat datang di SIMPUS. Temukan dan pinjam buku favoritmu hari ini.</p>
    </div>
    <div>
        <a href="katalog_buku.php" style="background: white; color: #4318FF; padding: 1rem 2rem; border-radius: 12px; text-decoration: none; font-weight: 700; display: inline-block;">Lihat Katalog</a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Sedang Dipinjam</h3>
        <div class="value"><?= $buku_dipinjam; ?> <span style="font-size: 1rem; color: #a3aed1; font-weight: 500;">Buku</span></div>
    </div>
    <div class="stat-card">
        <h3>Menunggu Konfirmasi</h3>
        <div class="value"><?= $buku_pending; ?> <span style="font-size: 1rem; color: #a3aed1; font-weight: 500;">Buku</span></div>
    </div>
    <div class="stat-card" style="<?= $total_denda > 0 ? 'border-color: #fce8e6; background: #fffaf9;' : ''; ?>">
        <h3>Total Denda</h3>
        <div class="value" style="<?= $total_denda > 0 ? 'color: #d93025;' : 'color: #1e8e3e;'; ?>">
            Rp <?= number_format($total_denda, 0, ',', '.'); ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>