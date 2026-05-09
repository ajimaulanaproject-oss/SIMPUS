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
        $stmt = $pdo->prepare("DELETE FROM buku WHERE id_buku = ?");
        $stmt->execute([$id]);
        
        echo "<script>
            alert('Data buku berhasil dihapus dari sistem!'); 
            window.location.href='kelola_buku.php';
        </script>";
    } catch (PDOException $e) {
        echo "<script>
            alert('Gagal menghapus data: " . $e->getMessage() . "'); 
            window.location.href='kelola_buku.php';
        </script>";
    }
} else {
    header("Location: kelola_buku.php");
}
?>