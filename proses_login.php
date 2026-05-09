<?php
session_start();
require 'config/koneksi.php';

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
        // Jika gagal, kembalikan ke index dengan alert
        echo "<script>
            alert('Identitas atau Password salah!'); 
            window.location.href='index.php';
        </script>";
    }
} else {
    header("Location: index.php");
}
?>