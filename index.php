<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPUS - Sistem Informasi Manajemen Perpustakaan</title>
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
            /* Gradien bersih ala SaaS */
            background: linear-gradient(135deg, #f0fdf4 0%, #e2e8f0 100%); 
            color: var(--text-dark); 
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Floating Navbar Glassmorphism */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 5%;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
        }
        .logo { 
            font-size: 1.6rem; 
            font-weight: 800; 
            color: var(--primary); 
            text-decoration: none; 
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        
        .nav-links { display: flex; gap: 2.5rem; align-items: center; }
        .nav-links a.menu-link { 
            text-decoration: none; 
            color: var(--text-gray); 
            font-weight: 600; 
            transition: color 0.3s ease; 
        }
        .nav-links a.menu-link:hover { color: var(--primary); }
        
        /* Primary Button Style */
        .btn-primary {
            background: var(--primary);
            color: white !important;
            padding: 0.8rem 1.8rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            box-shadow: 0 10px 20px rgba(5, 150, 105, 0.2);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-primary:hover { 
            background: var(--primary-light); 
            transform: translateY(-2px); 
            box-shadow: 0 15px 25px rgba(5, 150, 105, 0.3);
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 8rem 2rem 6rem;
            max-width: 900px;
            margin: 0 auto;
            flex: 1;
        }
        .system-badge {
            display: inline-block;
            background: var(--primary-soft);
            color: var(--primary);
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1.5rem;
        }
        .hero h1 { 
            font-size: 3.8rem; 
            font-weight: 800; 
            color: var(--text-dark); 
            margin-bottom: 1.5rem; 
            line-height: 1.2; 
            letter-spacing: -1px;
        }
        .hero h1 span { color: var(--primary); }
        .hero p { 
            font-size: 1.2rem; 
            color: var(--text-gray); 
            margin-bottom: 3rem; 
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Profile Section */
        .profile-section {
            padding: 2rem 5% 8rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 3rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            padding: 3rem; 
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.04);
            transition: transform 0.3s ease;
        }
        .glass-panel:hover {
            transform: translateY(-5px);
        }

        .glass-panel h3 { 
            font-size: 1.4rem; 
            color: var(--text-dark); 
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .glass-panel h3 i { color: var(--primary); font-size: 1.6rem; }
        
        .glass-panel p { color: var(--text-gray); font-size: 1.05rem; margin-bottom: 1rem; }
        
        /* Styling untuk List Visi Misi */
        .custom-list { list-style: none; padding-left: 0; }
        .custom-list li {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 0.8rem;
            color: var(--text-gray);
            font-size: 1.05rem;
        }
        .custom-list li::before {
            content: '\f058'; /* FontAwesome check-circle */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 0;
            top: 2px;
            color: var(--primary);
        }

        /* Styling Jadwal Operasional */
        .schedule-list { list-style: none; padding-left: 0; margin-top: 1.5rem; }
        .schedule-list li {
            display: flex; 
            justify-content: space-between; 
            border-bottom: 1px solid rgba(0,0,0,0.05); 
            padding: 0.8rem 0;
            color: var(--text-gray);
            font-size: 1.05rem;
        }
        .schedule-list li:last-child { border-bottom: none; }
        .schedule-list li strong { color: var(--text-dark); }
        .schedule-list li .closed { color: #ef4444; font-weight: 700; background: #fee2e2; padding: 0.2rem 0.8rem; border-radius: 8px; font-size: 0.9rem;}

        /* Footer */
        footer {
            text-align: center;
            padding: 3rem;
            color: var(--text-gray);
            font-weight: 500;
            font-size: 0.95rem;
            margin-top: auto;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 { font-size: 2.8rem; }
            .nav-links .menu-link { display: none; } /* Sembunyikan menu teks di HP, sisakan tombol login */
        }
    </style>
</head>
<body>

    <!-- Navigasi Atas -->
    <nav>
        <a href="#" class="logo">
            <i class="fas fa-book-reader"></i> SIMPUS
        </a>
        <div class="nav-links">
            <a href="#profil" class="menu-link">Profil</a>
            <a href="#layanan" class="menu-link">Layanan</a>
            <a href="login.php" class="btn-primary">
                Masuk <i class="fas fa-sign-in-alt"></i>
            </a>
        </div>
    </nav>

    <!-- Bagian Hero / Sambutan -->
    <section class="hero">
        <div class="system-badge">Sistem Informasi Manajemen Perpustakaan</div>
        <h1>Jendela Dunia Ada<br>di <span>Genggaman Anda.</span></h1>
        <p>Selamat datang di SIMPUS. Temukan ribuan koleksi literatur, jurnal, dan buku terbaru untuk mendukung perjalanan akademik Anda dengan lebih cepat dan modern.</p>
        <a href="login.php" class="btn-primary" style="font-size: 1.15rem; padding: 1.2rem 2.5rem; border-radius: 50px;">
            Mulai Jelajahi Katalog <i class="fas fa-arrow-right"></i>
        </a>
    </section>

    <!-- Bagian Profil Perpustakaan -->
    <section id="profil" class="profile-section">
        <!-- Kartu Visi Misi -->
        <div class="glass-panel">
            <h3><i class="fas fa-bullseye"></i> Visi & Misi</h3>
            <p><strong>Visi:</strong><br>Menjadi pusat informasi dan literasi yang unggul, modern, dan berbasis teknologi untuk mencetak generasi yang cerdas dan kompetitif.</p>
            <p style="margin-top: 1.5rem;"><strong>Misi:</strong></p>
            <ul class="custom-list">
                <li>Menyediakan koleksi pustaka yang lengkap dan <i>up-to-date</i>.</li>
                <li>Memberikan pelayanan peminjaman yang cepat dan mudah.</li>
                <li>Menciptakan ruang baca yang nyaman dan kondusif.</li>
            </ul>
        </div>

        <!-- Kartu Informasi Operasional -->
        <div class="glass-panel">
            <h3><i class="fas fa-clock"></i> Jam Operasional</h3>
            <p>Perpustakaan kami melayani kegiatan sirkulasi (peminjaman dan pengembalian buku) pada jadwal berikut:</p>
            <ul class="schedule-list">
                <li>
                    <span>Senin - Kamis</span> 
                    <strong>08:00 - 15:00 WIB</strong>
                </li>
                <li>
                    <span>Jumat</span> 
                    <strong>08:00 - 11:30 WIB</strong>
                </li>
                <li>
                    <span>Sabtu & Minggu</span> 
                    <span class="closed">Tutup</span>
                </li>
            </ul>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        &copy; <?= date('Y'); ?> SIMPUS. Dibuat dengan <i class="fas fa-heart" style="color: #ef4444;"></i> untuk kemajuan pendidikan.
    </footer>

</body>
</html>