<?php
session_start();
require '../config/koneksi.php';

// Proteksi halaman, pastikan hanya admin yang bisa masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Mengambil nama admin dari session
$nama_admin = $_SESSION['nama_lengkap'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | SIMPUS</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Reset & Base */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f4f7fe; color: #2b3674; display: flex; min-height: 100vh; }

        /* Floating Glassmorphism Sidebar */
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.5);
            padding: 3rem 2rem; /* Jarak atas dan samping yang lega */
            display: flex;
            flex-direction: column;
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.02);
            margin: 1.5rem;
            border-radius: 24px;
        }

        .brand-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: #4318FF;
            margin-bottom: 4rem; /* Jarak lega antara judul dan menu */
            letter-spacing: 1px;
            text-align: center;
        }

        .nav-links { list-style: none; display: flex; flex-direction: column; gap: 1.5rem; }
        .nav-links a {
            text-decoration: none;
            color: #a3aed1;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 1.2rem 1.5rem;
            border-radius: 16px;
            transition: all 0.3s ease;
            display: block;
        }
        .nav-links a:hover, .nav-links a.active {
            background: #4318FF;
            color: #ffffff;
            box-shadow: 0 10px 20px rgba(67, 24, 255, 0.2);
        }

        /* Main Content Area */
        .main-content {
            flex: 1;
            padding: 2.5rem 3rem 2.5rem 1rem; /* Padding konten utama yang luas */
            display: flex;
            flex-direction: column;
        }

        /* Header / Topbar */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3.5rem;
            padding: 1.5rem 3rem;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        }
        .topbar h1 { font-size: 1.8rem; font-weight: 700; }
        .user-profile { font-size: 1.1rem; font-weight: 600; color: #4318FF; }

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
        }
        .stat-card h3 { font-size: 1.1rem; color: #a3aed1; font-weight: 500; }
        .stat-card .value { font-size: 2.5rem; font-weight: 700; color: #2b3674; }

        /* Chart Section */
        .chart-container {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            padding: 3rem; /* Ruang bernafas ekstra untuk chart */
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
    </style>
</head>
<body>

    <!-- Sidebar SIMPUS -->
    <aside class="sidebar">
        <div class="brand-title">SIMPUS</div>
        <ul class="nav-links">
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="kelola_buku.php">Data Buku</a></li>
            <li><a href="kelola_siswa.php">Data Siswa</a></li>
            <li><a href="transaksi.php">Transaksi</a></li>
            <li><a href="laporan.php">Laporan</a></li>
            <li style="margin-top: 2rem;"><a href="../proses_login.php?logout=true" style="background: #ffebee; color: #d32f2f;">Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div>
                <p style="color: #a3aed1; font-weight: 500; margin-bottom: 0.3rem;">Halaman Admin</p>
                <h1>Dashboard Statistik</h1>
            </div>
            <div class="user-profile">
                👋 Halo, <?= htmlspecialchars($nama_admin); ?>
            </div>
        </header>

        <!-- Statistic Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Buku</h3>
                <div class="value">0</div>
            </div>
            <div class="stat-card">
                <h3>Total Siswa</h3>
                <div class="value">0</div>
            </div>
            <div class="stat-card">
                <h3>Peminjaman Aktif</h3>
                <div class="value">0</div>
            </div>
            <div class="stat-card">
                <h3>Menunggu Konfirmasi</h3>
                <div class="value">0</div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="chart-container">
            <h2 style="margin-bottom: 2rem; font-size: 1.4rem;">Statistik Peminjaman Bulan Ini</h2>
            <canvas id="peminjamanChart" height="80"></canvas>
        </div>
    </main>

    <!-- Script untuk inisialisasi Chart.js Dummy -->
    <script>
        const ctx = document.getElementById('peminjamanChart').getContext('2d');
        const peminjamanChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: [12, 19, 8, 15], // Data statis sementara
                    backgroundColor: '#4318FF',
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</body>
</html>