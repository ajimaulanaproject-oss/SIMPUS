<?php
session_start();
require 'config/koneksi.php';

// Jika sudah login, tendang kembali ke dalam sistem
if(isset($_SESSION['role'])){
    if($_SESSION['role'] == 'admin') header("Location: admin/dashboard.php");
    else header("Location: siswa/dashboard.php");
    exit;
}

if (isset($_POST['register'])) {
    $identitas = trim($_POST['identitas']);
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $password = trim($_POST['password']);
    
    // Hash password yang dimasukkan siswa demi keamanan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'siswa';

    try {
        $stmt = $pdo->prepare("INSERT INTO users (identitas, nama_lengkap, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$identitas, $nama_lengkap, $hashed_password, $role]);
        
        echo "<script>
            alert('Pendaftaran berhasil! Silakan masuk menggunakan akun Anda.'); 
            window.location.href='login.php';
        </script>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<script>alert('Gagal! NIS tersebut sudah terdaftar di sistem.');</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | SIMPUS</title>
    <!-- Font Plus Jakarta Sans & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Reset & Base */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        :root {
            --primary: #059669; /* Emerald Green */
            --primary-light: #10b981;
            --primary-soft: #d1fae5;
            --text-dark: #0f172a;
            --text-gray: #64748b;
        }

        body {
            /* Background gradien bersih */
            background: linear-gradient(135deg, #f0fdf4 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        /* Modern Panel Container */
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 1);
            border-radius: 24px;
            padding: 3.5rem 3rem; 
            width: 100%;
            max-width: 480px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
        }

        /* Typography */
        .brand-logo {
            text-align: center;
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        h2 { 
            color: var(--text-dark); 
            margin-bottom: 0.2rem; 
            font-size: 1.8rem; 
            font-weight: 800; 
            text-align: center; 
            letter-spacing: -0.5px;
        }
        .system-name {
            text-align: center;
            color: var(--primary);
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2rem;
        }
        p.subtitle { 
            color: var(--text-gray); 
            text-align: center; 
            margin-bottom: 2.5rem; 
            font-size: 0.95rem; 
            line-height: 1.5; 
        }

        /* Form Elements */
        .form-group { margin-bottom: 1.5rem; }
        label { 
            display: block; 
            margin-bottom: 0.6rem; 
            color: var(--text-dark); 
            font-size: 0.9rem; 
            font-weight: 600; 
        }
        
        .input-wrapper {
            position: relative;
        }
        .input-wrapper i {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 1rem 1.2rem 1rem 3rem; /* Padding kiri untuk area icon */
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 12px;
            background: #f8fafc;
            font-size: 1rem;
            color: var(--text-dark);
            transition: all 0.3s ease;
        }
        
        input:focus { 
            outline: none; 
            border-color: var(--primary); 
            box-shadow: 0 0 0 3px var(--primary-soft); 
            background: #ffffff; 
        }
        input:focus + i { color: var(--primary); }
        
        /* Button */
        button {
            width: 100%;
            padding: 1.1rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }
        button:hover { 
            background: var(--primary-light); 
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(5, 150, 105, 0.25);
        }
        button:active { transform: scale(0.98); }
        
        /* Link Login */
        .login-link {
            text-align: center; 
            margin-top: 2rem; 
            font-size: 0.95rem; 
            color: var(--text-gray);
        }
        .login-link a { 
            color: var(--primary); 
            font-weight: 700; 
            text-decoration: none; 
            transition: color 0.3s ease;
        }
        .login-link a:hover { color: var(--primary-light); text-decoration: underline; }
    </style>
</head>
<body>

<div class="register-container">
    <div class="brand-logo">
    <i class="fas fa-book-reader"></i>
    </div>
    <h2>Pendaftaran 
      <p><b>SIMPUS</b>
    </h2>
    <div class="system-name">Sistem Informasi Manajemen Perpustakaan</div>
    
    <p class="subtitle">Daftarkan akun Anda untuk meminjam buku dan mengakses layanan perpustakaan.</p>

    <form action="" method="POST">
        <div class="form-group">
            <label for="identitas">Nomor Induk Siswa (NIS)</label>
            <div class="input-wrapper">
                <i class="fas fa-id-card"></i>
                <input type="text" id="identitas" name="identitas" placeholder="Masukkan NIS Anda..." required autofocus>
            </div>
        </div>
        
        <div class="form-group">
            <label for="nama_lengkap">Nama Lengkap</label>
            <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap Anda..." required>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Buat Password</label>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Buat password yang aman..." required>
            </div>
        </div>

        <button type="submit" name="register">
            Daftar Sekarang <i class="fas fa-arrow-right"></i>
        </button>
    </form>

    <div class="login-link">
        Sudah memiliki akun? <a href="login.php">Masuk di sini</a>
    </div>
</div>

</body>
</html>