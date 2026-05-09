<?php
require 'config/koneksi.php';

// Hash yang valid
$password_baru = password_hash('password123', PASSWORD_DEFAULT);

// Reset semua password di database
$stmt = $pdo->prepare("UPDATE users SET password = ?");
if ($stmt->execute([$password_baru])) {
    echo "<div style='font-family: sans-serif; padding: 30px; text-align: center;'>";
    echo "<h2 style='color: #1e8e3e;'>✅ Sistem Berhasil Di-Reset!</h2>";
    echo "<p>Semua password pengguna sekarang adalah: <b style='color:red;'>password123</b></p>";
    echo "<hr style='margin: 20px 0;'>";
    
    // Tampilkan daftar pengguna
    echo "<h3>Daftar Akun yang Bisa Dipakai:</h3>";
    $users = $pdo->query("SELECT identitas, nama_lengkap, role FROM users")->fetchAll();
    
    echo "<table border='1' cellpadding='10' style='margin: 0 auto; border-collapse: collapse;'>";
    echo "<tr style='background: #f4f7fe;'><th>Role</th><th>Identitas (Username/NIS)</th><th>Nama Lengkap</th></tr>";
    foreach ($users as $u) {
        echo "<tr>";
        echo "<td>" . strtoupper($u['role']) . "</td>";
        echo "<td><b>" . htmlspecialchars($u['identitas']) . "</b></td>";
        echo "<td>" . htmlspecialchars($u['nama_lengkap']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<br><br><a href='index.php' style='padding: 12px 24px; background: #4318FF; color: white; text-decoration: none; border-radius: 8px;'>Kembali ke Halaman Login</a>";
    echo "</div>";
} else {
    echo "Gagal mereset database!";
}
?>