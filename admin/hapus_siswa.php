<?php
session_start();
require '../config/koneksi.php';

// Proteksi halaman admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Pastikan yang dihapus benar-benar role siswa
        $stmt = $pdo->prepare("DELETE FROM users WHERE id_user = ? AND role = 'siswa'");
        $stmt->execute([$id]);
        
        echo "<script>
            alert('Data siswa berhasil dihapus dari sistem!'); 
            window.location.href='kelola_siswa.php';
        </script>";
    } catch (PDOException $e) {
        echo "<script>
            alert('Gagal menghapus data: " . $e->getMessage() . "'); 
            window.location.href='kelola_siswa.php';
        </script>";
    }
} else {
    header("Location: kelola_siswa.php");
}
?>