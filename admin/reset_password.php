<?php
session_start();
require '../config/koneksi.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_user = $_GET['id'];
    
    // Siapkan hash untuk password default
    $password_default = password_hash('password123', PASSWORD_DEFAULT);
    
    try {
        // Update password di database
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id_user = ?");
        $stmt->execute([$password_default, $id_user]);
        
        echo "<script>
            alert('Sukses! Password akun ini telah direset kembali menjadi: password123'); 
            window.location.href='kelola_siswa.php';
        </script>";
    } catch (PDOException $e) {
        echo "<script>
            alert('Gagal mereset password: " . $e->getMessage() . "'); 
            window.location.href='kelola_siswa.php';
        </script>";
    }
} else {
    header("Location: kelola_siswa.php");
}
?>