<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Ambil ID siswa dari URL
if (!isset($_GET['id'])) {
    header("Location: kelola_siswa.php");
    exit;
}

$id_user = $_GET['id'];

// Ambil data siswa yang akan diedit
$stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = ? AND role = 'siswa'");
$stmt->execute([$id_user]);
$siswa = $stmt->fetch();

if (!$siswa) {
    echo "<script>alert('Data siswa tidak ditemukan!'); window.location.href='kelola_siswa.php';</script>";
    exit;
}

// Proses update jika form disubmit
if (isset($_POST['update'])) {
    $identitas = trim($_POST['identitas']);
    $nama_lengkap = trim($_POST['nama_lengkap']);

    try {
        $stmt = $pdo->prepare("UPDATE users SET identitas = ?, nama_lengkap = ? WHERE id_user = ?");
        $stmt->execute([$identitas, $nama_lengkap, $id_user]);
        echo "<script>alert('Data siswa berhasil diperbarui!'); window.location.href='kelola_siswa.php';</script>";
    } catch (PDOException $e) {
        // Handle error duplikat NIS
        if ($e->getCode() == 23000) {
             echo "<script>alert('Gagal! NIS tersebut sudah dipakai oleh siswa lain.');</script>";
        } else {
             echo "<script>alert('Gagal mengupdate data: " . $e->getMessage() . "');</script>";
        }
    }
}

$title = 'Edit Siswa | SIMPUS';
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<header class="topbar">
    <div>
        <p style="color: #a3aed1; font-weight: 500; margin-bottom: 0.3rem;">Manajemen Pengguna</p>
        <h1>Edit Data Siswa</h1>
    </div>
</header>

<div class="glass-card" style="max-width: 600px;">
    <form action="" method="POST">
        <div class="form-group">
            <label>Nomor Induk Siswa (NIS)</label>
            <input type="text" name="identitas" value="<?= htmlspecialchars($siswa['identitas']); ?>" required>
        </div>
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($siswa['nama_lengkap']); ?>" required>
        </div>
        
        <div style="display: flex; gap: 1.5rem; margin-top: 3rem;">
            <button type="submit" name="update" class="btn-primary" style="flex: 1;">Simpan Perubahan</button>
            <a href="kelola_siswa.php" style="flex: 1; text-align: center; background: #eef2ff; color: #4318FF; padding: 1.2rem; border-radius: 14px; text-decoration: none; font-weight: 600; transition: all 0.3s;">Batal</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>