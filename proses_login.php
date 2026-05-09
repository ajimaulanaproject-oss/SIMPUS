<?php
session_start();
require 'config/koneksi.php';

// ==========================================
// 1. LOGIKA LOGOUT
// ==========================================
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    // Hapus semua data sesi
    session_unset();
    session_destroy();
    
    // Arahkan kembali ke halaman form login
    header("Location: login.php");
    exit;
}

// ==========================================
// 2. LOGIKA LOGIN
// ==========================================
if (isset($_POST['login'])) {
    $identitas = trim($_POST['identitas']);
    $password = trim($_POST['password']);

    // Cari user berdasarkan identitas
    $stmt = $pdo->prepare("SELECT * FROM users WHERE identitas = ?");
    $stmt->execute([$identitas]);
    $user = $stmt->fetch();

    // Verifikasi hash password
    if ($user && password_verify($password, $user['password'])) {
        // Set data sesi
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['role'];

        // Arahkan ke dashboard masing-masing
        if ($user['role'] == 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: siswa/dashboard.php");
        }
        exit;
    } else {
        // PERBAIKAN: Jika gagal, kembalikan ke login.php, bukan index.php
        echo "<script>
            alert('Identitas atau Password salah!'); 
            window.location.href='login.php';
        </script>";
    }
} else {
    // Jika ada yang mencoba mengakses file ini langsung tanpa form
    header("Location: login.php");
}
?>