<?php
session_start();
require '../config/koneksi.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$nama_admin = $_SESSION['nama_lengkap'];

// Mengambil data pengguna dengan role 'siswa'
$stmt = $pdo->query("SELECT * FROM users WHERE role = 'siswa' ORDER BY id_user DESC");
$siswa_list = $stmt->fetchAll();

$title = 'Kelola Data Siswa | SIMPUS';
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<!-- Membuka area konten utama agar tidak tertutup sidebar -->
<main class="main-content">

    <style>
        .table-container {
            overflow-x: auto;
            margin-top: 1.5rem;
        }
        .modern-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }
        .modern-table th {
            padding: 1.2rem 1rem;
            text-align: left;
            border-bottom: 2px solid rgba(0,0,0,0.05);
            color: var(--text-gray);
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .modern-table td {
            padding: 1.2rem 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.03);
            color: var(--text-dark);
            font-weight: 500;
            vertical-align: middle;
        }
        .modern-table tbody tr {
            transition: background-color 0.2s ease;
        }
        .modern-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }
        
        /* Tombol Aksi */
        .btn-action {
            padding: 0.6rem 1rem;
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .btn-reset { background: #fef3c7; color: #d97706; } /* Warna Kuning/Amber */
        .btn-reset:hover { background: #fde68a; color: #b45309; }
        
        .btn-edit { background: #f1f5f9; color: #475569; }
        .btn-edit:hover { background: #e2e8f0; color: #0f172a; }
        
        .btn-delete { background: #fee2e2; color: #ef4444; }
        .btn-delete:hover { background: #fecaca; color: #dc2626; }
    </style>

    <!-- Topbar / Header Halaman -->
    <header class="topbar">
        <div class="topbar-text">
            <p>Manajemen Pengguna</p>
            <h1>Kelola Data Siswa</h1>
        </div>
        <div class="user-profile glass-panel">
            <i class="fas fa-user-circle"></i>
            <?= htmlspecialchars($nama_admin); ?>
        </div>
    </header>

    <!-- Kontainer Utama Data Siswa -->
    <div class="glass-panel" style="padding: 2.5rem; border-radius: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 1.4rem; font-weight: 700; color: var(--text-dark);">Daftar Anggota Peminjam</h2>
            <a href="tambah_siswa.php" class="btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-user-plus"></i> Tambah Siswa Baru
            </a>
        </div>

        <div class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 25%;">Nomor Induk Siswa (NIS)</th>
                        <th style="width: 35%;">Nama Lengkap</th>
                        <th style="width: 35%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($siswa_list) > 0): ?>
                        <?php $no = 1; foreach ($siswa_list as $siswa): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <span style="font-family: monospace; background: rgba(0,0,0,0.04); padding: 0.4rem 0.6rem; border-radius: 8px; font-weight: 600; color: #475569;">
                                    <?= htmlspecialchars($siswa['identitas']); ?>
                                </span>
                            </td>
                            <td style="font-weight: 600; color: var(--text-dark);">
                                <?= htmlspecialchars($siswa['nama_lengkap']); ?>
                            </td>
                            <td style="text-align: center; white-space: nowrap;">
                                <!-- Tombol Reset Sandi -->
                                <a href="reset_password.php?id=<?= $siswa['id_user']; ?>" class="btn-action btn-reset" onclick="return confirm('Anda yakin ingin mereset sandi siswa ini menjadi: password123 ?');">
                                    <i class="fas fa-key"></i> Reset Sandi
                                </a>
                                
                                <!-- Tombol Edit -->
                                <a href="edit_siswa.php?id=<?= $siswa['id_user']; ?>" class="btn-action btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                
                                <!-- Tombol Hapus -->
                                <a href="hapus_siswa.php?id=<?= $siswa['id_user']; ?>" class="btn-action btn-delete" onclick="return confirm('Yakin ingin menghapus data siswa ini? Semua riwayat pinjamannya juga akan terhapus.');">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 4rem 2rem; color: var(--text-gray);">
                                <i class="fas fa-users-slash" style="font-size: 3rem; color: rgba(0,0,0,0.1); margin-bottom: 1rem; display: block;"></i>
                                Belum ada data siswa yang terdaftar di sistem.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php 
// Memanggil Footer (Otomatis menutup tag </main>)
include '../includes/footer.php'; 
?>