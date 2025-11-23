<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistem Metode Pembelajaran')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .main-wrapper {
            display: flex;
            flex: 1;
            height: 100%;
            width: 100%;
        }
        
        /* --- SIDEBAR --- */
        .sidebar {
            width: 280px;
            height: 100%;
            background-color: #ffffff; 
            color: #333; 
            padding: 0; /* Reset padding agar brand bisa mentok ke tepi */
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            border-right: 1px solid #dee2e6;
            z-index: 10;
            display: flex;
            flex-direction: column; /* Susunan vertikal: Brand di atas, Menu di bawah */
        }

        /* Header Sidebar (Metode Pembelajaran) */
        .sidebar .sidebar-brand {
            height: 70px; /* TINGGI YANG SAMA DENGAN NAVBAR */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: #0d6efd; 
            text-decoration: none;
            border-bottom: 1px solid #dee2e6; /* Garis pemisah */
            flex-shrink: 0; /* Agar tidak mengecil */
        }

        /* Container Menu (Scrollable) */
        .sidebar .sidebar-menu {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1.25rem; /* Padding dipindah ke sini */
        }

        .sidebar .nav-link {
            color: #343a40; 
            font-size: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            transition: all 0.2s ease-in-out;
        }
        .sidebar .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
            width: 20px;
            text-align: center;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(13, 110, 253, 0.1); 
            color: #0d6efd;
        }
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: white; 
            font-weight: 500;
        }

        /* --- KONTEN KANAN --- */
        .app-content {
            display: flex;
            flex-direction: column;
            flex: 1;
            height: 100%;
            min-width: 0;
        }

        /* Navbar Atas */
        .app-content .navbar {
            height: 70px; /* TINGGI YANG SAMA DENGAN SIDEBAR BRAND */
            padding-top: 0;
            padding-bottom: 0;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
        }

        /* Konten Utama (Scrollable) */
        .content-wrapper {
            flex: 1;
            padding: 2rem;
            background-color: #f8f9fa;
            overflow-y: auto; 
            height: 100%;
        }
    </style>
</head>
<body>

    <div class="main-wrapper">
        @include('layouts.partials.sidebar')

        <div class="app-content w-100">
            @include('layouts.partials.navigation')

            <main class="content-wrapper">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>