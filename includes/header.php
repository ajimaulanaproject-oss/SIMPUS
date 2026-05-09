<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'SIMPUS Dashboard'; ?></title>
    <!-- Plugin Chart & Scanner -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        /* Base & Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f4f7fe; color: #2b3674; display: flex; min-height: 100vh; }

        /* Sidebar Glassmorphism */
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.5);
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.02);
            margin: 1.5rem;
            border-radius: 24px;
        }
        .brand-title { font-size: 2.2rem; font-weight: 800; color: #4318FF; margin-bottom: 4rem; text-align: center; }
        .nav-links { list-style: none; display: flex; flex-direction: column; gap: 1.5rem; }
        .nav-links a { text-decoration: none; color: #a3aed1; font-weight: 600; font-size: 1.1rem; padding: 1.2rem 1.5rem; border-radius: 16px; transition: all 0.3s ease; display: block; }
        .nav-links a:hover, .nav-links a.active { background: #4318FF; color: #ffffff; box-shadow: 0 10px 20px rgba(67, 24, 255, 0.2); }

        /* Main Content Layout */
        .main-content { flex: 1; padding: 2.5rem 3rem 2.5rem 1rem; display: flex; flex-direction: column; }
        .topbar { margin-bottom: 3rem; display: flex; justify-content: space-between; align-items: center; }
        .topbar h1 { font-size: 2rem; font-weight: 700; }

        /* Card / Container (Tabel & Form) - Spacing Lega */
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            padding: 3rem; 
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        /* Form Styles - Inputan Luas */
        .form-group { margin-bottom: 2rem; }
        .form-group label { display: block; margin-bottom: 0.8rem; font-weight: 600; color: #2b3674; font-size: 1.05rem; }
        .form-group input, .form-group select {
            width: 100%;
            padding: 1.2rem 1.5rem;
            border: 1px solid #cbd5e0;
            border-radius: 12px;
            font-size: 1.05rem;
            background: #ffffff;
            transition: all 0.3s;
        }
        .form-group input:focus { outline: none; border-color: #4318FF; box-shadow: 0 0 0 3px rgba(67, 24, 255, 0.1); }
        
        /* Buttons */
        .btn-primary { background: #4318FF; color: white; padding: 1.2rem 2rem; border: none; border-radius: 14px; font-weight: 600; font-size: 1.1rem; cursor: pointer; transition: all 0.3s; }
        .btn-primary:hover { background: #2b0bb5; transform: translateY(-2px); }
    </style>
</head>
<body>