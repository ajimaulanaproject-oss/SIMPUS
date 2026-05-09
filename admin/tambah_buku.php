<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

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

<header class="topbar">
    <div>
        <p style="color: #a3aed1; font-weight: 500; margin-bottom: 0.3rem;">Manajemen Katalog</p>
        <h1>Tambah Buku Baru</h1>
    </div>
</header>

<div class="glass-card" style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem;">
    
    <!-- Kolom Kiri: Form Input -->
    <div>
        <form action="" method="POST">
            <div class="form-group">
                <label>Kode Barcode (ISBN)</label>
                <input type="text" id="kode_barcode" name="kode_barcode" placeholder="Scan barcode atau ketik manual..." required>
            </div>
            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" name="judul_buku" placeholder="Contoh: Pemrograman Web dengan PHP" required>
            </div>
            <div class="form-group">
                <label>Penulis</label>
                <input type="text" name="penulis" placeholder="Nama penulis..." required>
            </div>
            <div class="form-group">
                <label>Penerbit</label>
                <input type="text" name="penerbit" placeholder="Nama penerbit..." required>
            </div>
            <div class="form-group">
                <label>Jumlah Stok</label>
                <input type="number" name="stok" placeholder="Contoh: 10" min="1" required>
            </div>
            <button type="submit" name="simpan" class="btn-primary" style="width: 100%;">Simpan Data Buku</button>
        </form>
    </div>

    <!-- Kolom Kanan: Area Scanner -->
    <div>
        <div style="background: #f4f7fe; border-radius: 16px; padding: 2rem; text-align: center; border: 2px dashed #cbd5e0;">
            <h3 style="margin-bottom: 1.5rem; color: #2b3674;">Scan Barcode Buku</h3>
            <!-- Area kamera akan muncul di dalam div ini -->
            <div id="reader" style="width: 100%; border-radius: 12px; overflow: hidden; margin-bottom: 1rem;"></div>
            <p style="color: #4a5568; font-size: 0.95rem;">Arahkan kamera ke barcode di belakang buku. Kode otomatis terisi ke form.</p>
        </div>
    </div>

</div>

<!-- Script Logika Scanner -->
<script>
    function onScanSuccess(decodedText, decodedResult) {
        // Hentikan scan jika berhasil terbaca, lalu masukkan nilai ke input
        document.getElementById('kode_barcode').value = decodedText;
        
        // Memainkan efek suara (opsional) atau memberi feedback visual
        document.getElementById('kode_barcode').style.borderColor = "#48bb78";
        document.getElementById('kode_barcode').style.boxShadow = "0 0 0 3px rgba(72, 187, 120, 0.2)";
        
        // Hentikan kamera agar tidak scan terus menerus
        html5QrcodeScanner.clear();
        alert("Barcode berhasil discan: " + decodedText);
    }

    // Inisialisasi Scanner
    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", 
        { fps: 10, qrbox: {width: 250, height: 100} }, // Kotak scan dibuat pipih untuk barcode
        /* verbose= */ false
    );
    html5QrcodeScanner.render(onScanSuccess);
</script>

<?php include '../includes/footer.php'; ?>