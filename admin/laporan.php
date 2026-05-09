<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); // Diperbarui ke login.php
    exit;
}

// PERBAIKAN: Mengambil nama admin dari session
$nama_admin = $_SESSION['nama_lengkap'];

// Inisialisasi variabel filter tanggal
$tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : '';
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : '';

// Query dasar dengan JOIN
$query = "
    SELECT t.*, u.nama_lengkap, u.identitas, b.judul_buku 
    FROM transaksi t 
    JOIN users u ON t.id_user = u.id_user 
    JOIN buku b ON t.id_buku = b.id_buku 
";

// Jika filter tanggal diisi, tambahkan kondisi WHERE
if ($tgl_awal != '' && $tgl_akhir != '') {
    $query .= " WHERE t.tanggal_pinjam BETWEEN :tgl_awal AND :tgl_akhir ";
}

$query .= " ORDER BY t.tanggal_pinjam DESC";

$stmt = $pdo->prepare($query);

// Bind parameter jika filter aktif
if ($tgl_awal != '' && $tgl_akhir != '') {
    $stmt->bindParam(':tgl_awal', $tgl_awal);
    $stmt->bindParam(':tgl_akhir', $tgl_akhir);
}

$stmt->execute();
$laporan = $stmt->fetchAll();

// Hitung total denda untuk laporan
$total_denda = 0;
foreach ($laporan as $row) {
    $total_denda += $row['denda'];
}

$title = 'Laporan Transaksi | SIMPUS';
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<main class="main-content">

    <style>
        /* Desain Tabel Web */
        .table-container { overflow-x: auto; margin-top: 1.5rem; }
        .modern-table { width: 100%; border-collapse: collapse; min-width: 800px; }
        .modern-table th {
            padding: 1.2rem 1rem; text-align: left; border-bottom: 2px solid rgba(0,0,0,0.05);
            color: var(--text-gray); font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;
        }
        .modern-table td {
            padding: 1.2rem 1rem; border-bottom: 1px solid rgba(0,0,0,0.03);
            color: var(--text-dark); font-weight: 500; vertical-align: middle;
        }
        .modern-table tbody tr { transition: background-color 0.2s ease; }
        .modern-table tbody tr:hover { background-color: rgba(255, 255, 255, 0.5); }

        /* Badge Status */
        .badge { padding: 0.4rem 0.8rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; white-space: nowrap; }
        .badge-pending { background: #fef3c7; color: #d97706; }
        .badge-active { background: #e0f2fe; color: #0284c7; }
        .badge-success { background: var(--primary-soft); color: var(--primary); }
        .badge-danger { background: #fee2e2; color: #ef4444; }

        /* Form Filter Style */
        .form-label { display: block; margin-bottom: 0.6rem; font-weight: 600; color: var(--text-dark); font-size: 0.9rem;}
        .form-input { 
            width: 100%; padding: 0.8rem 1.2rem; border-radius: 12px; 
            border: 1px solid rgba(0,0,0,0.1); font-family: inherit;
            background: rgba(255,255,255,0.9); outline: none; transition: all 0.3s ease;
        }
        .form-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-soft); }

        /* Media Print (Khusus Cetak Kertas/PDF) */
        @media print {
            /* Sembunyikan elemen web yang tidak perlu dicetak */
            .sidebar, .topbar, .filter-card, .btn-print, .action-col { display: none !important; }
            
            /* Reset layout agar memenuhi halaman kertas */
            body { background: white; color: black; }
            .main-content { padding: 0 !important; margin: 0 !important; }
            
            /* Matikan efek kaca saat di-print */
            .glass-panel { 
                background: none !important; border: none !important; 
                box-shadow: none !important; padding: 0 !important; 
                backdrop-filter: none !important; -webkit-backdrop-filter: none !important;
            }
            
            /* Header Laporan Print */
            .print-header { display: block !important; text-align: center; margin-bottom: 2rem; border-bottom: 2px solid #000; padding-bottom: 1rem; }
            .print-header h2 { font-size: 24px; margin-bottom: 5px; font-weight: bold; color: black; }
            .print-header p { font-size: 14px; color: #333; margin-bottom: 2px; }

            /* Tabel Print Solid */
            .modern-table { width: 100%; border-collapse: collapse; }
            .modern-table th, .modern-table td { 
                border: 1px solid #000 !important; 
                padding: 10px !important; 
                color: #000 !important; 
                font-size: 12px !important; 
            }
            .modern-table th { background-color: #f0f0f0 !important; }
            
            /* Tampilkan status teks saja, buang background badge saat print */
            .badge { background: none !important; color: black !important; padding: 0 !important; border: none !important; }
        }
        
        /* Sembunyikan header print saat tampilan web biasa */
        .print-header { display: none; }
    </style>

    <header class="topbar">
        <div class="topbar-text">
            <p>Rekapitulasi</p>
            <h1>Laporan Perpustakaan</h1>
        </div>
        <div class="user-profile glass-panel">
            <i class="fas fa-user-circle"></i>
            <?= htmlspecialchars($nama_admin); ?>
        </div>
    </header>

    <div class="glass-panel filter-card" style="margin-bottom: 2rem; padding: 2rem 2.5rem; border-radius: 24px;">
        <form action="" method="GET" style="display: flex; gap: 1.5rem; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="tgl_awal" value="<?= htmlspecialchars($tgl_awal); ?>" class="form-input" required>
            </div>
            <div style="flex: 1; min-width: 200px;">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="tgl_akhir" value="<?= htmlspecialchars($tgl_akhir); ?>" class="form-input" required>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn-primary" style="padding: 0.8rem 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-filter"></i> Tampilkan
                </button>
                <a href="laporan.php" style="padding: 0.8rem 1.5rem; background: #f1f5f9; color: #475569; border-radius: 12px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; transition: background 0.3s ease;">
                    <i class="fas fa-undo"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <div class="glass-panel" style="padding: 2.5rem; border-radius: 24px;">
        
        <div class="print-header">
            <h2>SISTEM INFORMASI PERPUSTAKAAN (SIMPUS)</h2>
            <p>Laporan Rekapitulasi Transaksi Peminjaman Buku</p>
            <?php if ($tgl_awal && $tgl_akhir): ?>
                <p>Periode: <?= date('d/m/Y', strtotime($tgl_awal)); ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)); ?></p>
            <?php else: ?>
                <p>Periode: Semua Data</p>
            <?php endif; ?>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;" class="filter-card">
            <h2 style="font-size: 1.4rem; font-weight: 700; color: var(--text-dark);">Data Transaksi</h2>
            <button onclick="window.print()" class="btn-primary btn-print" style="background: var(--text-dark); display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-print"></i> Cetak / Export PDF
            </button>
        </div>

        <div class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">Tgl Pinjam</th>
                        <th style="width: 25%;">Nama Siswa</th>
                        <th style="width: 30%;">Judul Buku</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 15%;">Denda</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($laporan) > 0): ?>
                        <?php $no = 1; foreach ($laporan as $row): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <?= $row['tanggal_pinjam'] ? date('d M Y', strtotime($row['tanggal_pinjam'])) : '-'; ?>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--text-dark); margin-bottom: 0.2rem;"><?= htmlspecialchars($row['nama_lengkap']); ?></div>
                                <div style="font-size: 0.85rem; color: var(--text-gray); font-family: monospace;">NIS: <?= htmlspecialchars($row['identitas']); ?></div>
                            </td>
                            <td style="font-weight: 600;">
                                <?= htmlspecialchars($row['judul_buku']); ?>
                            </td>
                            <td>
                                <?php 
                                    if ($row['status'] == 'pending') echo '<span class="badge badge-pending">Pending</span>';
                                    elseif ($row['status'] == 'dipinjam') echo '<span class="badge badge-active">Dipinjam</span>';
                                    elseif ($row['status'] == 'dikembalikan') echo '<span class="badge badge-success">Selesai</span>';
                                    else echo '<span class="badge badge-danger">Ditolak</span>';
                                ?>
                            </td>
                            <td style="font-weight: 700; color: <?= $row['denda'] > 0 ? '#ef4444' : 'var(--text-gray)'; ?>;">
                                <?= $row['denda'] > 0 ? 'Rp ' . number_format($row['denda'], 0, ',', '.') : '-'; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <tr style="background-color: var(--primary-soft);">
                            <td colspan="5" style="text-align: right; font-weight: 700; color: var(--primary);">Total Denda Terkumpul:</td>
                            <td style="font-weight: 800; color: #ef4444; font-size: 1.1rem;">Rp <?= number_format($total_denda, 0, ',', '.'); ?></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 4rem 2rem; color: var(--text-gray);">
                                <i class="fas fa-folder-open" style="font-size: 3rem; color: rgba(0,0,0,0.1); margin-bottom: 1rem; display: block;"></i>
                                Tidak ada data transaksi pada periode yang dipilih.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php include '../includes/footer.php'; ?>