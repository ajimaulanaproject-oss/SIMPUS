<?php
session_start();
require '../config/koneksi.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$nama_admin = $_SESSION['nama_lengkap'];

// ==========================================
// LOGIKA PEMROSESAN TRANSAKSI
// ==========================================
if (isset($_POST['proses'])) {
    $id_transaksi = $_POST['id_transaksi'];
    $id_buku = $_POST['id_buku'];
    $aksi = $_POST['aksi'];

    if ($aksi == 'konfirmasi') {
        // Cek stok buku terakhir
        $cek_stok = $pdo->prepare("SELECT stok FROM buku WHERE id_buku = ?");
        $cek_stok->execute([$id_buku]);
        $buku = $cek_stok->fetch();

        if ($buku['stok'] > 0) {
            $tanggal_pinjam = date('Y-m-d');
            $tenggat_waktu = date('Y-m-d', strtotime('+7 days')); // Pinjam 7 hari

            $pdo->beginTransaction();
            try {
                // Update transaksi
                $stmt = $pdo->prepare("UPDATE transaksi SET tanggal_pinjam = ?, tenggat_waktu = ?, status = 'dipinjam' WHERE id_transaksi = ?");
                $stmt->execute([$tanggal_pinjam, $tenggat_waktu, $id_transaksi]);
                
                // Kurangi stok buku
                $stmt2 = $pdo->prepare("UPDATE buku SET stok = stok - 1 WHERE id_buku = ?");
                $stmt2->execute([$id_buku]);
                
                $pdo->commit();
                echo "<script>alert('Peminjaman disetujui. Stok buku otomatis dikurangi!'); window.location.href='transaksi.php';</script>";
            } catch (Exception $e) {
                $pdo->rollBack();
                echo "<script>alert('Terjadi kesalahan sistem.');</script>";
            }
        } else {
            echo "<script>alert('Gagal! Stok buku sudah habis.');</script>";
        }

    } elseif ($aksi == 'tolak') {
        $stmt = $pdo->prepare("UPDATE transaksi SET status = 'ditolak' WHERE id_transaksi = ?");
        $stmt->execute([$id_transaksi]);
        echo "<script>alert('Peminjaman ditolak.'); window.location.href='transaksi.php';</script>";

    } elseif ($aksi == 'kembalikan') {
        $cek_trx = $pdo->prepare("SELECT tenggat_waktu FROM transaksi WHERE id_transaksi = ?");
        $cek_trx->execute([$id_transaksi]);
        $trx = $cek_trx->fetch();

        $tanggal_dikembalikan = date('Y-m-d');
        $denda = 0;
        $tarif_denda_per_hari = 1000;

        if ($tanggal_dikembalikan > $trx['tenggat_waktu']) {
            $selisih = strtotime($tanggal_dikembalikan) - strtotime($trx['tenggat_waktu']);
            $hari_telat = floor($selisih / (60 * 60 * 24));
            $denda = $hari_telat * $tarif_denda_per_hari;
        }

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("UPDATE transaksi SET tanggal_dikembalikan = ?, status = 'dikembalikan', denda = ? WHERE id_transaksi = ?");
            $stmt->execute([$tanggal_dikembalikan, $denda, $id_transaksi]);
            
            $stmt2 = $pdo->prepare("UPDATE buku SET stok = stok + 1 WHERE id_buku = ?");
            $stmt2->execute([$id_buku]);
            
            $pdo->commit();
            
            if ($denda > 0) {
                echo "<script>alert('Buku dikembalikan. Siswa terlambat dan dikenakan denda Rp " . number_format($denda, 0, ',', '.') . "'); window.location.href='transaksi.php';</script>";
            } else {
                echo "<script>alert('Buku berhasil dikembalikan tepat waktu. Stok bertambah.'); window.location.href='transaksi.php';</script>";
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<script>alert('Terjadi kesalahan sistem.');</script>";
        }
    }
}

// Ambil semua data transaksi dengan nama siswa dan judul buku
$stmt = $pdo->query("
    SELECT t.*, u.nama_lengkap, u.identitas, b.judul_buku 
    FROM transaksi t 
    JOIN users u ON t.id_user = u.id_user 
    JOIN buku b ON t.id_buku = b.id_buku 
    ORDER BY t.id_transaksi DESC
");
$transaksi_list = $stmt->fetchAll();

$title = 'Sirkulasi Transaksi | SIMPUS';
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<!-- Buka Main Content -->
<main class="main-content">

    <style>
        .table-container { overflow-x: auto; margin-top: 1.5rem; }
        .modern-table { width: 100%; border-collapse: collapse; min-width: 900px; }
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
        .badge { padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.85rem; white-space: nowrap; }
        .badge-pending { background: #fef3c7; color: #d97706; }
        .badge-active { background: #e0f2fe; color: #0284c7; }
        .badge-success { background: var(--primary-soft); color: var(--primary); }
        .badge-danger { background: #fee2e2; color: #ef4444; }

        /* Tombol Aksi */
        .btn-action {
            padding: 0.6rem 1rem; border-radius: 10px; font-size: 0.85rem; font-weight: 600;
            display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem;
            transition: all 0.2s ease; border: none; cursor: pointer; white-space: nowrap;
        }
        .btn-approve { background: var(--primary); color: white; }
        .btn-approve:hover { background: var(--primary-light); }
        .btn-reject { background: #fee2e2; color: #ef4444; }
        .btn-reject:hover { background: #fecaca; color: #dc2626; }
    </style>

    <!-- Topbar / Header Halaman -->
    <header class="topbar">
        <div class="topbar-text">
            <p>Sirkulasi Perpustakaan</p>
            <h1>Kelola Transaksi Peminjaman</h1>
        </div>
        <div class="user-profile glass-panel">
            <i class="fas fa-user-circle"></i>
            <?= htmlspecialchars($nama_admin); ?>
        </div>
    </header>

    <!-- Kontainer Tabel Transaksi -->
    <div class="glass-panel" style="padding: 2.5rem; border-radius: 24px;">
        <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.4rem; font-weight: 700; color: var(--text-dark);">Daftar Pengajuan & Peminjaman Aktif</h2>
        </div>

        <div class="table-container">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Data Peminjam (Siswa)</th>
                        <th style="width: 30%;">Judul Buku</th>
                        <th style="width: 20%;">Informasi Tanggal</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 15%; text-align: center;">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($transaksi_list) > 0): ?>
                        <?php foreach ($transaksi_list as $row): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 700; color: var(--text-dark); margin-bottom: 0.3rem;">
                                    <?= htmlspecialchars($row['nama_lengkap']); ?>
                                </div>
                                <div style="font-size: 0.85rem; color: var(--text-gray); font-family: monospace;">
                                    <i class="fas fa-id-card" style="margin-right: 0.3rem;"></i> NIS: <?= htmlspecialchars($row['identitas']); ?>
                                </div>
                            </td>
                            
                            <td style="font-weight: 600; color: var(--text-dark);">
                                <?= htmlspecialchars($row['judul_buku']); ?>
                            </td>
                            
                            <td>
                                <?php if ($row['status'] == 'pending'): ?>
                                    <span style="color: var(--text-gray); font-size: 0.9rem; font-style: italic;">Menunggu Persetujuan...</span>
                                <?php else: ?>
                                    <div style="font-size: 0.9rem; margin-bottom: 0.3rem; color: var(--text-gray);">
                                        <strong>Pinjam:</strong> <?= date('d/m/Y', strtotime($row['tanggal_pinjam'])); ?>
                                    </div>
                                    <div style="font-size: 0.9rem; color: #ef4444;">
                                        <strong>Kembali:</strong> <?= date('d/m/Y', strtotime($row['tenggat_waktu'])); ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <?php 
                                    if ($row['status'] == 'pending') echo '<span class="badge badge-pending">Pending</span>';
                                    elseif ($row['status'] == 'dipinjam') echo '<span class="badge badge-active">Dipinjam</span>';
                                    elseif ($row['status'] == 'dikembalikan') echo '<span class="badge badge-success">Selesai</span>';
                                    else echo '<span class="badge badge-danger">Ditolak</span>';
                                ?>
                            </td>
                            
                            <td style="text-align: center;">
                                <form action="" method="POST" style="display: flex; gap: 0.5rem; justify-content: center;">
                                    <input type="hidden" name="id_transaksi" value="<?= $row['id_transaksi']; ?>">
                                    <input type="hidden" name="id_buku" value="<?= $row['id_buku']; ?>">
                                    
                                    <?php if ($row['status'] == 'pending'): ?>
                                        <button type="submit" name="proses" value="yes" class="btn-action btn-approve" onclick="document.getElementById('aksi_<?= $row['id_transaksi']; ?>').value='konfirmasi';" title="Setujui Peminjaman">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                        <button type="submit" name="proses" value="yes" class="btn-action btn-reject" onclick="document.getElementById('aksi_<?= $row['id_transaksi']; ?>').value='tolak';" title="Tolak Peminjaman">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        
                                    <?php elseif ($row['status'] == 'dipinjam' || $row['status'] == 'telat'): ?>
                                        <button type="submit" name="proses" value="yes" class="btn-action btn-approve" onclick="document.getElementById('aksi_<?= $row['id_transaksi']; ?>').value='kembalikan';" style="width: 100%;">
                                            <i class="fas fa-undo"></i> Terima Buku
                                        </button>
                                        
                                    <?php else: ?>
                                        <span style="color: var(--text-gray); font-size: 0.9rem;">-</span>
                                    <?php endif; ?>
                                    
                                    <input type="hidden" id="aksi_<?= $row['id_transaksi']; ?>" name="aksi" value="">
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 4rem 2rem; color: var(--text-gray);">
                                <i class="fas fa-inbox" style="font-size: 3rem; color: rgba(0,0,0,0.1); margin-bottom: 1rem; display: block;"></i>
                                Belum ada aktivitas transaksi peminjaman.
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