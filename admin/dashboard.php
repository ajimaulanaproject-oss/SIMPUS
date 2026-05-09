<?php
session_start();
require '../config/koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$nama_admin = $_SESSION['nama_lengkap'];

// ==========================================
// MENGAMBIL DATA STATISTIK REAL-TIME
// ==========================================
$stmt_buku = $pdo->query("SELECT COUNT(*) as total FROM buku WHERE stok > 0");
$total_buku = $stmt_buku->fetch()['total'];

$stmt_siswa = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'siswa'");
$total_siswa = $stmt_siswa->fetch()['total'];

$stmt_pinjam = $pdo->query("SELECT COUNT(*) as total FROM transaksi WHERE status = 'dipinjam'");
$peminjaman_aktif = $stmt_pinjam->fetch()['total'];

$stmt_pending = $pdo->query("SELECT COUNT(*) as total FROM transaksi WHERE status = 'pending'");
$menunggu_konfirmasi = $stmt_pending->fetch()['total'];

$stmt_grafik = $pdo->query("SELECT status, COUNT(*) as jumlah FROM transaksi GROUP BY status");
$data_grafik = $stmt_grafik->fetchAll(PDO::FETCH_KEY_PAIR);

$chart_pending = isset($data_grafik['pending']) ? $data_grafik['pending'] : 0;
$chart_dipinjam = isset($data_grafik['dipinjam']) ? $data_grafik['dipinjam'] : 0;
$chart_dikembalikan = isset($data_grafik['dikembalikan']) ? $data_grafik['dikembalikan'] : 0;
$chart_ditolak = isset($data_grafik['ditolak']) ? $data_grafik['ditolak'] : 0;

// Set Title Dinamis untuk Header
$title = 'Dashboard Admin | SIMPUS';
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<!-- CSS Khusus untuk Kartu di Dashboard -->
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 2rem;
        margin-bottom: 3.5rem;
    }
    .stat-card {
        border-radius: 24px;
        padding: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
        background: #ffffff;
    }
    .stat-info h3 { font-size: 0.85rem; color: var(--text-gray); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; }
    .stat-info .value { font-size: 2.5rem; font-weight: 800; color: var(--text-dark); line-height: 1; }
    
    .stat-icon {
        width: 60px; height: 60px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; transform: rotate(-5deg); transition: transform 0.3s ease;
    }
    .stat-card:hover .stat-icon { transform: rotate(0deg) scale(1.1); }

    .icon-books { background: var(--primary-soft); color: var(--primary); }
    .icon-users { background: #e0f2fe; color: #0284c7; }
    .icon-active { background: #fef3c7; color: #d97706; }
    .icon-pending { background: #fee2e2; color: #ef4444; }

    .chart-container { border-radius: 24px; padding: 3rem; }
    .chart-container h2 { margin-bottom: 2.5rem; font-size: 1.3rem; font-weight: 700; color: var(--text-dark); }
</style>

<!-- Main Content Terbuka dari header.php -->
<main class="main-content">
    
    <header class="topbar">
        <div class="topbar-text">
            <p>Overview Perpustakaan</p>
            <h1>Dashboard Statistik</h1>
        </div>
        <div class="user-profile glass-panel">
            <i class="fas fa-user-circle"></i>
            <?= htmlspecialchars($nama_admin); ?>
        </div>
    </header>

    <div class="stats-grid">
        <div class="stat-card glass-panel">
            <div class="stat-info">
                <h3>Total Buku</h3>
                <div class="value"><?= $total_buku; ?></div>
            </div>
            <div class="stat-icon icon-books"><i class="fas fa-book"></i></div>
        </div>
        
        <div class="stat-card glass-panel">
            <div class="stat-info">
                <h3>Anggota</h3>
                <div class="value"><?= $total_siswa; ?></div>
            </div>
            <div class="stat-icon icon-users"><i class="fas fa-id-card"></i></div>
        </div>
        
        <div class="stat-card glass-panel">
            <div class="stat-info">
                <h3>Dipinjam</h3>
                <div class="value"><?= $peminjaman_aktif; ?></div>
            </div>
            <div class="stat-icon icon-active"><i class="fas fa-book-reader"></i></div>
        </div>
        
        <div class="stat-card glass-panel">
            <div class="stat-info">
                <h3>Konfirmasi</h3>
                <div class="value"><?= $menunggu_konfirmasi; ?></div>
            </div>
            <div class="stat-icon icon-pending"><i class="fas fa-bell"></i></div>
        </div>
    </div>

    <div class="chart-container glass-panel">
        <h2>Statistik Status Peminjaman</h2>
        <canvas id="peminjamanChart" height="70"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('peminjamanChart').getContext('2d');
        const peminjamanChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Menunggu Konfirmasi', 'Sedang Dipinjam', 'Selesai Dikembalikan', 'Ditolak/Dibatalkan'],
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: [<?= $chart_pending; ?>, <?= $chart_dipinjam; ?>, <?= $chart_dikembalikan; ?>, <?= $chart_ditolak; ?>],
                    backgroundColor: ['#f59e0b', '#0284c7', '#059669', '#ef4444'],
                    borderRadius: 8, maxBarThickness: 55 
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { drawBorder: false } },
                    x: { grid: { display: false, drawBorder: false } }
                }
            }
        });
    </script>

<!-- Footer ditutup di footer.php -->
<?php include '../includes/footer.php'; ?>