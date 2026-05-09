<?php
session_start();
require '../config/koneksi.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); // Diperbarui ke login.php
    exit;
}

// Mengambil nama admin dari session untuk profil
$nama_admin = $_SESSION['nama_lengkap'];

// Ambil ID buku dari URL
if (!isset($_GET['id'])) {
    header("Location: kelola_buku.php");
    exit;
}

$id_buku = $_GET['id'];

// Ambil data buku yang akan diedit
$stmt = $pdo->prepare("SELECT * FROM buku WHERE id_buku = ?");
$stmt->execute([$id_buku]);
$buku = $stmt->fetch();

if (!$buku) {
    echo "<script>alert('Buku tidak ditemukan!'); window.location.href='kelola_buku.php';</script>";
    exit;
}

// Proses update jika form disubmit
if (isset($_POST['update'])) {
    $kode_barcode = $_POST['kode_barcode'];
    $judul_buku = $_POST['judul_buku'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $stok = $_POST['stok'];

    try {
        $stmt = $pdo->prepare("UPDATE buku SET kode_barcode = ?, judul_buku = ?, penulis = ?, penerbit = ?, stok = ? WHERE id_buku = ?");
        $stmt->execute([$kode_barcode, $judul_buku, $penulis, $penerbit, $stok, $id_buku]);
        echo "<script>alert('Data buku berhasil diperbarui!'); window.location.href='kelola_buku.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Gagal mengupdate data: " . $e->getMessage() . "');</script>";
    }
}

$title = 'Edit Buku | SIMPUS';
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<!-- Membuka area konten utama -->
<main class="main-content">

    <style>
        /* Gaya Form Modern */
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.6rem; font-weight: 600; color: var(--text-dark); font-size: 0.95rem; }
        .form-input {
            width: 100%; padding: 1rem 1.2rem; border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.1); font-family: inherit; font-size: 0.95rem;
            background: rgba(255,255,255,0.9); outline: none; transition: all 0.3s ease; color: var(--text-dark);
        }
        .form-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-soft); }
        
        /* Tombol Batal */
        .btn-cancel {
            background: #f1f5f9; color: #475569; padding: 1rem; border-radius: 12px;
            text-decoration: none; font-weight: 600; text-align: center;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            transition: all 0.3s ease; border: 1px solid rgba(0,0,0,0.05);
        }
        .btn-cancel:hover { background: #e2e8f0; color: #0f172a; transform: translateY(-2px); }
    </style>

    <!-- Topbar / Header Halaman -->
    <header class="topbar">
        <div class="topbar-text">
            <p>Manajemen Katalog</p>
            <h1>Edit Data Buku</h1>
        </div>
        <div class="user-profile glass-panel">
            <i class="fas fa-user-circle"></i>
            <?= htmlspecialchars($nama_admin); ?>
        </div>
    </header>

    <!-- Kontainer Formulir Edit dipusatkan -->
    <div class="glass-panel" style="padding: 3rem; border-radius: 24px; max-width: 800px; margin: 0 auto;">
        
        <h2 style="font-size: 1.3rem; font-weight: 700; color: var(--text-dark); margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 2px solid rgba(0,0,0,0.05); padding-bottom: 1rem;">
            <i class="fas fa-edit" style="color: var(--primary);"></i> Perbarui Informasi Buku
        </h2>

        <form action="" method="POST">
            <div class="form-group">
                <label class="form-label">Kode Barcode (ISBN)</label>
                <input type="text" name="kode_barcode" class="form-input" value="<?= htmlspecialchars($buku['kode_barcode']); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Judul Buku</label>
                <input type="text" name="judul_buku" class="form-input" value="<?= htmlspecialchars($buku['judul_buku']); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Penulis</label>
                <input type="text" name="penulis" class="form-input" value="<?= htmlspecialchars($buku['penulis']); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Penerbit</label>
                <input type="text" name="penerbit" class="form-input" value="<?= htmlspecialchars($buku['penerbit']); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah Stok</label>
                <input type="number" name="stok" class="form-input" value="<?= htmlspecialchars($buku['stok']); ?>" min="0" required>
            </div>
            
            <div style="display: flex; gap: 1.5rem; margin-top: 3rem;">
                <a href="kelola_buku.php" class="btn-cancel" style="flex: 1;">
                    <i class="fas fa-arrow-left"></i> Batal / Kembali
                </a>
                <button type="submit" name="update" class="btn-primary" style="flex: 1; padding: 1rem; font-size: 1.05rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

<?php include '../includes/footer.php'; ?>