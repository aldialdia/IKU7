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
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .main-wrapper {
            display: flex;
            flex: 1;
        }
        
        /* --- STYLE SIDEBAR BARU --- */
        .sidebar {
            width: 280px;
            min-height: 100vh;
            /* Latar belakang putih */
            background-color: #ffffff; 
            color: #333; /* Warna teks default gelap */
            padding: 1.25rem;
            /* Tambahkan bayangan/border di kanan */
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            border-right: 1px solid #dee2e6;
        }
        .sidebar .sidebar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            /* Warna brand biru */
            color: #0d6efd; 
            text-decoration: none;
            display: block;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .sidebar .nav-link {
            /* Warna teks link default */
            color: #343a40; 
            font-size: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem; /* Sudut melengkung untuk link */
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
        /* Efek hover: latar biru transparan & teks biru solid */
        .sidebar .nav-link:hover {
            background-color: rgba(13, 110, 253, 0.1); 
            color: #0d6efd;
        }
        /* Style untuk link yang aktif: latar biru solid, teks putih */
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: white; 
            font-weight: 500;
        }
        /* --- AKHIR STYLE SIDEBAR --- */

        .content-wrapper {
            flex: 1;
            padding: 2rem;
            background-color: #f8f9fa; /* Latar belakang konten abu-abu muda */
        }
    </style>
</head>
<body>

    <div class="main-wrapper">
        @include('layouts.partials.sidebar')

        <div class="d-flex flex-column w-100">
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