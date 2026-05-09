<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'SIMPUS - Sistem Informasi Perpustakaan'; ?></title>
    <!-- Font & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js (Dipanggil di header agar tersedia di semua halaman) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
            background: linear-gradient(135deg, #f0fdf4 0%, #e2e8f0 100%); 
            color: var(--text-dark); 
            min-height: 100vh; 
            overflow-x: hidden;
            display: flex; /* Membantu penataan layout utama */
        }

        /* Glassmorphism Panel Class */
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 1);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
        }

        /* Main Content Area (Berlaku untuk semua halaman) */
        .main-content {
            margin-left: calc(270px + 3rem); /* Menyesuaikan lebar sidebar */
            padding: 2rem 3rem 0 0; /* Padding lega */
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Topbar / Header Utama */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3.5rem;
        }
        .topbar-text p {
            color: var(--text-gray);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 0.4rem;
        }
        .topbar-text h1 { 
            font-size: 2.2rem; 
            font-weight: 800; 
            color: var(--text-dark);
            letter-spacing: -0.5px;
        }
        .user-profile { 
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            font-size: 0.95rem; 
            font-weight: 700; 
            color: var(--primary); 
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .user-profile i { font-size: 1.4rem; }

        /* Komponen Pendukung Global */
        .btn-primary {
            background: var(--primary);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: var(--primary-light);
            box-shadow: 0 5px 15px rgba(5, 150, 105, 0.3);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>