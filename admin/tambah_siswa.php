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

if (isset($_POST['simpan'])) {
    $identitas = trim($_POST['identitas']);
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $role = 'siswa';
    
    // Password default diset 'password123', di-hash demi keamanan
    $password_default = password_hash('password123', PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (identitas, nama_lengkap, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$identitas, $nama_lengkap, $password_default, $role]);
        echo "<script>alert('Siswa berhasil didaftarkan!'); window.location.href='kelola_siswa.php';</script>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<script>alert('Gagal! NIS tersebut sudah terdaftar di sistem.');</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "');</script>";
        }
    }
}

$title = 'Tambah Siswa | SIMPUS';
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
        
        /* Kotak Informasi */
        .info-box {
            background: var(--primary-soft);
            border-left: 4px solid var(--primary);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }
        .info-box i { color: var(--primary); font-size: 1.2rem; margin-top: 0.2rem; }
        .info-box p { color: var(--text-dark); font-size: 0.95rem; line-height: 1.6; }

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
            <p>Manajemen Pengguna</p>
            <h1>Tambah Siswa Baru</h1>
        </div>
        <div class="user-profile glass-panel">
            <i class="fas fa-user-circle"></i>
            <?= htmlspecialchars($nama_admin); ?>
        </div>
    </header>

    <!-- Kontainer Formulir dipusatkan -->
    <div class="glass-panel" style="padding: 3rem; border-radius: 24px; max-width: 650px; margin: 0 auto;">
        
        <h2 style="font-size: 1.3rem; font-weight: 700; color: var(--text-dark); margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 2px solid rgba(0,0,0,0.05); padding-bottom: 1rem;">
            <i class="fas fa-user-plus" style="color: var(--primary);"></i> Form Pendaftaran Anggota
        </h2>

        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <p>
                <strong>Informasi Penting:</strong><br>
                Akun siswa yang ditambahkan otomatis akan memiliki password bawaan: <b>password123</b>. Siswa dapat login ke sistem menggunakan <b>Nomor Induk Siswa (NIS)</b> mereka.
            </p>
        </div>

        <form action="" method="POST">
            <div class="form-group">
                <label class="form-label">Nomor Induk Siswa (NIS)</label>
                <input type="text" name="identitas" class="form-input" placeholder="Masukkan NIS siswa..." required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-input" placeholder="Contoh: Budi Santoso" required>
            </div>
            
            <div style="display: flex; gap: 1.5rem; margin-top: 3rem;">
                <a href="kelola_siswa.php" class="btn-cancel" style="flex: 1;">
                    <i class="fas fa-arrow-left"></i> Batal / Kembali
                </a>
                <button type="submit" name="simpan" class="btn-primary" style="flex: 1; padding: 1rem; font-size: 1.05rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <i class="fas fa-save"></i> Simpan Data Siswa
                </button>
            </div>
        </form>
    </div>

<?php include '../includes/footer.php'; ?>