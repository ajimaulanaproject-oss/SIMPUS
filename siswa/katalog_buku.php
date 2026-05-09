<?php
session_start();
require '../config/koneksi.php';

// Pastikan hanya siswa yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../index.php");
    exit;
}

$id_siswa = $_SESSION['id_user'];

// Proses ketika tombol "Ajukan Pinjaman" ditekan
if (isset($_POST['pinjam'])) {
    $id_buku = $_POST['id_buku'];

    // Cek apakah buku masih berstatus pending atau sedang dipinjam oleh siswa ini
    $cek_pinjam = $pdo->prepare("SELECT * FROM transaksi WHERE id_user = ? AND id_buku = ? AND status IN ('pending', 'dipinjam')");
    $cek_pinjam->execute([$id_siswa, $id_buku]);

    if ($cek_pinjam->rowCount() > 0) {
        echo "<script>alert('Gagal: Anda sedang meminjam atau sudah mengajukan peminjaman untuk buku ini!');</script>";
    } else {
        // Masukkan data ke tabel transaksi dengan status 'pending'
        $stmt = $pdo->prepare("INSERT INTO transaksi (id_user, id_buku, status) VALUES (?, ?, 'pending')");
        if ($stmt->execute([$id_siswa, $id_buku])) {
            echo "<script>alert('Pengajuan berhasil! Silakan tunggu konfirmasi dari Admin.'); window.location.href='riwayat_pinjam.php';</script>";
        }
    }
}

// Ambil data buku yang stoknya masih ada
$stmt = $pdo->query("SELECT * FROM buku WHERE stok > 0 ORDER BY id_buku DESC");
$buku_list = $stmt->fetchAll();

$title = 'Katalog Buku | SIMPUS';
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<header class="topbar">
    <div>
        <p style="color: #a3aed1; font-weight: 500; margin-bottom: 0.3rem;">Koleksi Perpustakaan</p>
        <h1>Katalog Buku</h1>
    </div>
</header>

<!-- CSS tambahan khusus untuk layout kartu buku yang lapang -->
<style>
    .book-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 3rem; /* Jarak antar kartu sangat lega */
    }
    .book-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        padding: 2.5rem;
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.6);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(67, 24, 255, 0.1);
    }
    .book-title { font-size: 1.3rem; color: #2b3674; font-weight: 700; margin-bottom: 0.5rem; line-height: 1.4; }
    .book-author { color: #a3aed1; font-size: 0.95rem; margin-bottom: 2rem; font-weight: 500; }
    .book-stock { background: #eef2ff; color: #4318FF; padding: 0.6rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem; align-self: flex-start; margin-bottom: 2rem; }
</style>

<div class="book-grid">
    <?php if (count($buku_list) > 0): ?>
        <?php foreach ($buku_list as $buku): ?>
            <div class="book-card">
                <div class="book-stock">Stok: <?= htmlspecialchars($buku['stok']); ?></div>
                <h3 class="book-title"><?= htmlspecialchars($buku['judul_buku']); ?></h3>
                <p class="book-author">Oleh: <?= htmlspecialchars($buku['penulis']); ?></p>
                
                <!-- Spacer untuk mendorong tombol ke bawah -->
                <div style="flex-grow: 1;"></div> 

                <form action="" method="POST" style="margin-top: 1rem;">
                    <input type="hidden" name="id_buku" value="<?= $buku['id_buku']; ?>">
                    <button type="submit" name="pinjam" class="btn-primary" style="width: 100%; font-size: 1rem; padding: 1rem;">Ajukan Pinjaman</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="glass-card" style="grid-column: 1 / -1; text-align: center;">
            <p style="color: #a3aed1; font-size: 1.2rem;">Maaf, saat ini belum ada buku yang tersedia.</p>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>