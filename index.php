<?php
session_start();
// Redirect jika sudah login
if(isset($_SESSION['role'])){
    if($_SESSION['role'] == 'admin') header("Location: admin/dashboard.php");
    else header("Location: siswa/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Perpustakaan</title>
    <style>
        /* Reset & Base */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body {
            /* Background gradien bersih ala SaaS */
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Glassmorphism Container dengan ruang lega */
        .login-container {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            padding: 4rem 3.5rem; /* Padding ekstra agar elemen tidak mepet */
            width: 100%;
            max-width: 480px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        }

        /* Typography */
        h2 { color: #1a202c; margin-bottom: 0.8rem; font-size: 2rem; font-weight: 600; text-align: center; }
        p.subtitle { color: #4a5568; text-align: center; margin-bottom: 3rem; font-size: 1rem; line-height: 1.5; }

        /* Form Elements dengan jarak lapang */
        .form-group { margin-bottom: 2rem; }
        label { display: block; margin-bottom: 0.8rem; color: #2d3748; font-size: 0.95rem; font-weight: 500; }
        
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 1.2rem 1.5rem; /* Input yang luas */
            border: 1px solid #cbd5e0;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.8);
            font-size: 1.05rem;
            color: #1a202c;
            transition: all 0.3s ease;
        }
        
        input:focus { outline: none; border-color: #4299e1; box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2); background: #ffffff; }
        
        /* Button */
        button {
            width: 100%;
            padding: 1.2rem;
            background: #3182ce;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.1s ease;
            margin-top: 1.5rem;
        }
        button:hover { background: #2b6cb0; }
        button:active { transform: scale(0.98); }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Sistem Perpustakaan</h2>
    <p class="subtitle">Masuk dengan kredensial Anda untuk melanjutkan ke dashboard.</p>

    <form action="proses_login.php" method="POST">
        <div class="form-group">
            <label for="identitas">NIS / Username Admin</label>
            <input type="text" id="identitas" name="identitas" placeholder="Masukkan identitas..." required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Masukkan password..." required>
        </div>

        <button type="submit" name="login">Masuk ke Sistem</button>
    </form>
</div>

</body>
</html>