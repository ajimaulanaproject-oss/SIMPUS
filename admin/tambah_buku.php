<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Mengambil nama admin dari session untuk profil
$nama_admin = $_SESSION['nama_lengkap'];

// Proses jika form disubmit
if (isset($_POST['simpan'])) {
    $kode_barcode = $_POST['kode_barcode'];
    $judul_buku = $_POST['judul_buku'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $stok = $_POST['stok'];

    try {
        $stmt = $pdo->prepare("INSERT INTO buku (kode_barcode, judul_buku, penulis, penerbit, stok) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$kode_barcode, $judul_buku, $penulis, $penerbit, $stok]);
        echo "<script>alert('Buku berhasil ditambahkan!'); window.location.href='kelola_buku.php';</script>";
    } catch (PDOException $e) {
        // Cek jika kode barcode duplikat
        if ($e->getCode() == 23000) {
            echo "<script>alert('Gagal! Kode Barcode tersebut sudah terdaftar di sistem.');</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "');</script>";
        }
    }
}

$title = 'Tambah Buku | SIMPUS';
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<!-- Pastikan Library HTML5 QRCode dimuat -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

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
        
        /* Area Scanner */
        .scanner-container {
            background: #f8fafc; border-radius: 20px; padding: 2.5rem 2rem;
            text-align: center; border: 2px dashed #cbd5e0;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            height: 100%; min-height: 400px;
        }
        
        /* Layout Grid Responsif */
        .grid-layout {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 3.5rem;
        }
    </style>

    <!-- Topbar / Header Halaman -->
    <header class="topbar">
        <div class="topbar-text">
            <p>Manajemen Katalog</p>
            <h1>Tambah Buku Baru</h1>
        </div>
        <div class="user-profile glass-panel">
            <i class="fas fa-user-circle"></i>
            <?= htmlspecialchars($nama_admin); ?>
        </div>
    </header>

    <!-- Kontainer Formulir & Scanner -->
    <div class="glass-panel" style="padding: 3rem; border-radius: 24px;">
        <div class="grid-layout">
            
            <!-- Kolom Kiri: Form Input -->
            <div>
                <h2 style="font-size: 1.3rem; font-weight: 700; color: var(--text-dark); margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-edit" style="color: var(--primary);"></i> Informasi Buku
                </h2>
                <form action="" method="POST">
                    <div class="form-group">
                        <label class="form-label">Kode Barcode (ISBN)</label>
                        <input type="text" id="kode_barcode" name="kode_barcode" class="form-input" placeholder="Scan barcode atau ketik manual..." required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Judul Buku</label>
                        <input type="text" name="judul_buku" class="form-input" placeholder="Contoh: Algoritma dan Pemrograman Dasar" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Penulis</label>
                        <input type="text" name="penulis" class="form-input" placeholder="Nama lengkap penulis..." required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Penerbit</label>
                        <input type="text" name="penerbit" class="form-input" placeholder="Nama penerbit buku..." required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah Stok</label>
                        <input type="number" name="stok" class="form-input" placeholder="Contoh: 10" min="1" required>
                    </div>
                    <button type="submit" name="simpan" class="btn-primary" style="width: 100%; padding: 1rem; font-size: 1.05rem; margin-top: 1rem;">
                        <i class="fas fa-save"></i> Simpan Data Buku
                    </button>
                </form>
            </div>

            <!-- Kolom Kanan: Area Scanner -->
            <div>
                <div class="scanner-container">
                    <h3 style="margin-bottom: 1rem; font-weight: 700; color: var(--text-dark); font-size: 1.2rem;">
                        <i class="fas fa-barcode" style="color: var(--primary); margin-right: 0.5rem;"></i> Scan Barcode Otomatis
                    </h3>
                    <p style="color: var(--text-gray); font-size: 0.9rem; margin-bottom: 2rem; line-height: 1.5;">
                        Arahkan kamera perangkat Anda ke barcode di belakang sampul buku. Kode akan otomatis terisi ke form.
                    </p>
                    
                    <!-- Kotak kamera -->
                    <div id="reader" style="width: 100%; max-width: 400px; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: white;"></div>
                </div>
            </div>

        </div>
    </div>

    <!-- Script Logika Scanner -->
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Masukkan nilai ke input
            const inputBarcode = document.getElementById('kode_barcode');
            inputBarcode.value = decodedText;
            
            // Memberi feedback visual (warna hijau emerald)
            inputBarcode.style.borderColor = "var(--primary)";
            inputBarcode.style.boxShadow = "0 0 0 4px var(--primary-soft)";
            inputBarcode.style.backgroundColor = "#f0fdf4";
            
            // Hentikan kamera agar tidak scan berulang kali
            html5QrcodeScanner.clear();
            
            // Tampilkan pesan sukses ringan
            alert("✅ Barcode berhasil discan: " + decodedText);
            
            // Kembalikan warna form setelah 2 detik
            setTimeout(() => {
                inputBarcode.style.backgroundColor = "rgba(255,255,255,0.9)";
            }, 2000);
        }

        // Inisialisasi Scanner
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { fps: 10, qrbox: {width: 250, height: 100} }, // Kotak scan pipih untuk barcode
            /* verbose= */ false
        );
        html5QrcodeScanner.render(onScanSuccess);
    </script>

<?php include '../includes/footer.php'; ?>