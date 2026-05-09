<?php
session_start();

// Redirect jika sudah login
if(isset($_SESSION['role'])){
    if($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        // Arahkan siswa ke dashboard.php
        header("Location: siswa/dashboard.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SIMPUS</title>
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
            /* Background gradien bersih selaras dengan halaman admin */
            background: linear-gradient(135deg, #f0fdf4 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative; /* Diperlukan untuk tombol back melayang */
        }

        /* Tombol Kembali Floating */
        .back-btn {
            position: absolute;
            top: 2rem;
            left: 2.5rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: var(--text-gray);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            background: rgba(255, 255, 255, 0.8);
            padding: 0.8rem 1.2rem;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            z-index: 10;
        }
        .back-btn:hover {
            color: var(--primary);
            background: #ffffff;
            transform: translateX(-5px); /* Efek geser ke kiri sedikit */
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }
        
        /* Modern Panel Container */
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 1);
            border-radius: 24px;
            padding: 3.5rem 3rem; 
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
        }

        /* Typography */
        .brand-logo {
            text-align: center;
            font-size: 2.5rem;
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
            font-size: 0.85rem;
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
            padding: 1rem 1.2rem 1rem 3rem; /* Padding kiri lebih besar untuk icon */
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
        
        /* Link Pendaftaran */
        .register-link {
            text-align: center; 
            margin-top: 2rem; 
            font-size: 0.95rem; 
            color: var(--text-gray);
        }
        .register-link a { 
            color: var(--primary); 
            font-weight: 700; 
            text-decoration: none; 
            transition: color 0.3s ease;
        }
        .register-link a:hover { color: var(--primary-light); text-decoration: underline; }

        /* Responsivitas untuk mobile */
        @media (max-width: 768px) {
            .back-btn {
                top: 1rem;
                left: 1rem;
                padding: 0.6rem 1rem;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>

<!-- Tombol Kembali Melayang -->
<a href="index.php" class="back-btn">
    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
</a>

<div class="login-container">
    <div class="brand-logo">
        <i class="fas fa-book-reader"></i>
    </div>
    <h2>SIMPUS</h2>
    <div class="system-name">Sistem Informasi Manajemen Perpustakaan</div>
    
    <p class="subtitle">Silakan masuk menggunakan kredensial Anda untuk melanjutkan ke sistem.</p>

    <form action="proses_login.php" method="POST">
        <div class="form-group">
            <label for="identitas">NIS / Username Admin</label>
            <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input type="text" id="identitas" name="identitas" placeholder="Masukkan identitas..." required autofocus>
            </div>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Masukkan password..." required>
            </div>
        </div>

        <button type="submit" name="login">
            Masuk ke Sistem <i class="fas fa-arrow-right"></i>
        </button>
    </form>

    <div class="register-link">
        Belum memiliki akun? <a href="register.php">Daftar di sini</a>
    </div>
</div>

</body>
</html>